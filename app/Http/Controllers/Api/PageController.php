<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of pages
     */
    public function index(Request $request): JsonResponse
    {
        $query = Page::with(['user', 'template']);
        
        // Filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('is_homepage')) {
            $query->where('is_homepage', $request->boolean('is_homepage'));
        }
        
        if ($request->has('show_in_menu')) {
            $query->where('show_in_menu', $request->boolean('show_in_menu'));
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'order');
        $sortDir = $request->get('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);
        
        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $pages = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $pages,
        ]);
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Page::class);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|array',
            'title.*' => 'string|max:255',
            'slug' => 'nullable|string|unique:pages,slug',
            'content' => 'nullable|array',
            'excerpt' => 'nullable|array',
            'featured_image' => 'nullable|string',
            'status' => 'nullable|in:draft,published,scheduled',
            'template_id' => 'nullable|exists:templates,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'robots_meta' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_homepage' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'custom_fields' => 'nullable|array',
            'menu_locations' => 'nullable|array',
            'published_at' => 'nullable|date',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $data = $validator->validated();
        $data['user_id'] = auth()->id();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $title = is_array($data['title']) ? ($data['title']['en'] ?? $data['title']['ro'] ?? reset($data['title'])) : $data['title'];
            $data['slug'] = Str::slug($title);
        }
        
        // Ensure unique slug
        $baseSlug = $data['slug'];
        $counter = 1;
        while (Page::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $baseSlug . '-' . $counter++;
        }
        
        // If setting as homepage, unset other homepages
        if (!empty($data['is_homepage']) && $data['is_homepage']) {
            Page::where('is_homepage', true)->update(['is_homepage' => false]);
        }
        
        DB::beginTransaction();
        try {
            $page = Page::create($data);
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Page created successfully',
                'data' => $page->load(['user', 'template']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create page: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified page
     */
    public function show(int $id): JsonResponse
    {
        $page = Page::with(['user', 'template'])->find($id);
        
        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }

    /**
     * Update the specified page
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $page = Page::find($id);
        
        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }
        
        $this->authorize('update', $page);
        
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|array',
            'title.*' => 'string|max:255',
            'slug' => 'sometimes|string|unique:pages,slug,' . $id,
            'content' => 'nullable|array',
            'excerpt' => 'nullable|array',
            'featured_image' => 'nullable|string',
            'status' => 'nullable|in:draft,published,scheduled',
            'template_id' => 'nullable|exists:templates,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'robots_meta' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_homepage' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'custom_fields' => 'nullable|array',
            'menu_locations' => 'nullable|array',
            'published_at' => 'nullable|date',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $data = $validator->validated();
        
        // If setting as homepage, unset other homepages
        if (!empty($data['is_homepage']) && $data['is_homepage'] && !$page->is_homepage) {
            Page::where('is_homepage', true)->where('id', '!=', $id)->update(['is_homepage' => false]);
        }
        
        DB::beginTransaction();
        try {
            $page->update($data);
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully',
                'data' => $page->fresh()->load(['user', 'template']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update page: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified page
     */
    public function destroy(int $id): JsonResponse
    {
        $page = Page::find($id);
        
        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }
        
        $this->authorize('delete', $page);
        
        if ($page->is_homepage) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the homepage',
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            $page->delete();
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Page deleted successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete page: ' . $e->getMessage(),
            ], 500);
        }
    }
}
