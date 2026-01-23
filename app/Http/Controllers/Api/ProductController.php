<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category');
        
        // Filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $products = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Product::class);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|array',
            'name.*' => 'string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'description' => 'nullable|array',
            'content' => 'nullable|array',
            'category_id' => 'nullable|exists:product_categories,id',
            'sku' => 'nullable|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'attributes' => 'nullable|array',
            'meta' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'meta_title' => 'nullable|array',
            'meta_description' => 'nullable|array',
            'meta_keywords' => 'nullable|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $data = $validator->validated();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $name = is_array($data['name']) ? ($data['name']['en'] ?? $data['name']['ro'] ?? reset($data['name'])) : $data['name'];
            $data['slug'] = Str::slug($name);
        }
        
        // Ensure unique slug
        $baseSlug = $data['slug'];
        $counter = 1;
        while (Product::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $baseSlug . '-' . $counter++;
        }
        
        DB::beginTransaction();
        try {
            $product = Product::create($data);
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load('category'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified product
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with('category')->find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
        
        $this->authorize('update', $product);
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|array',
            'name.*' => 'string|max:255',
            'slug' => 'sometimes|string|unique:products,slug,' . $id,
            'description' => 'nullable|array',
            'content' => 'nullable|array',
            'category_id' => 'nullable|exists:product_categories,id',
            'sku' => 'nullable|string|unique:products,sku,' . $id,
            'price' => 'sometimes|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'attributes' => 'nullable|array',
            'meta' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'meta_title' => 'nullable|array',
            'meta_description' => 'nullable|array',
            'meta_keywords' => 'nullable|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            $product->update($validator->validated());
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->fresh()->load('category'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
        
        $this->authorize('delete', $product);
        
        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk import products from CSV
     */
    public function import(Request $request): JsonResponse
    {
        $this->authorize('create', Product::class);
        
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:10240',
            'update_existing' => 'nullable|boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $file = $request->file('file');
        $updateExisting = $request->boolean('update_existing', false);
        
        $path = $file->store('imports', 'local');
        
        // Dispatch job for async processing
        $jobId = Str::uuid()->toString();
        
        \App\Jobs\ImportProductsJob::dispatch(
            storage_path('app/' . $path),
            $updateExisting,
            $jobId,
            auth()->id()
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Import job queued',
            'job_id' => $jobId,
        ], 202);
    }

    /**
     * Bulk update products
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $this->authorize('create', Product::class);
        
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1|max:100',
            'products.*.id' => 'required|exists:products,id',
            'products.*.data' => 'required|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            $updated = [];
            $failed = [];
            
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                if ($product) {
                    $product->update($item['data']);
                    $updated[] = $item['id'];
                } else {
                    $failed[] = $item['id'];
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Bulk update completed',
                'updated' => $updated,
                'failed' => $failed,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
