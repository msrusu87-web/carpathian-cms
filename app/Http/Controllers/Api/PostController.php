<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $posts = Post::query()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(50)
            ->get([
                'id',
                'title',
                'slug',
                'excerpt',
                'status',
                'published_at',
                'featured_image',
            ]);

        return response()->json([
            'data' => $posts,
        ]);
    }
}
