<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Category;
use App\Models\ContactSetting;
use App\Models\ContactMessage;
use App\Models\Tag;
use App\Models\Widget;
use App\Services\TemplateRendererService;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    protected TemplateRendererService $renderer;

    public function __construct(TemplateRendererService $renderer)
    {
        $this->renderer = $renderer;
    }

    public function index()
    {
        // Prefer an explicitly configured homepage page (common on production).
        // Note: we intentionally do NOT require published_at here because some
        // installs publish pages without setting that field.
        $homepage = Page::where('is_homepage', true)
            ->where('status', 'published')
            ->first();

        if ($homepage && $homepage->template_id) {
            return response($this->renderer->renderPage($homepage));
        }

        // Widget-based homepage (fallback)
        // Fail-safe: if there are no "active" widgets (common when production DB stores
        // status as boolean/integer or data is inconsistent), fall back to showing all widgets
        // rather than rendering an empty homepage.
        $widgets = Widget::active()->get();
        if ($widgets->isEmpty()) {
            $widgets = Widget::query()->orderBy('order')->get();
        }
        $products = Product::where('is_active', true)->take(6)->get();
        $posts = Post::where('status', 'published')->latest()->take(3)->get();

        return view('frontend.home', compact('widgets', 'products', 'posts', 'homepage'));
    }

    public function page(string $slug)
    {
        // Special handling for portfolios page
        if ($slug === 'portfolios') {
            return view('frontend.pages.portfolios');
        }

        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Use simple view for pages without complex templates
        if (!$page->template_id) {
            return view('frontend.pages.show', compact('page'));
        }

        return response($this->renderer->renderPage($page));
    }

    public function post(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'tags', 'user'])
            ->firstOrFail();

        $post->increment('views');

        $relatedPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->take(3)
            ->get();

        return view('frontend.posts.show', compact('post', 'relatedPosts'));
    }

    public function blog()
    {
        $posts = Post::where('status', 'published')
            ->with(['category', 'user'])
            ->latest()
            ->paginate(12);

        $categories = Category::whereHas('posts', function($query) {
            $query->where('status', 'published');
        })->withCount('posts')->get();

        $tags = Tag::whereHas('posts', function($query) {
            $query->where('status', 'published');
        })->get();

        return view('frontend.blog', compact('posts', 'categories', 'tags'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::where('status', 'published')
            ->where('category_id', $category->id)
            ->with(['user'])
            ->latest()
            ->paginate(12);

        return view('frontend.category', compact('category', 'posts'));
    }

    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $posts = $tag->posts()
            ->where('status', 'published')
            ->with(['category', 'user'])
            ->latest()
            ->paginate(12);

        return view('frontend.tag', compact('tag', 'posts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $posts = Post::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->with(['category', 'user'])
            ->latest()
            ->paginate(12);

        return view('frontend.search', compact('posts', 'query'));
    }

    public function contact()
    {
        $settings = ContactSetting::first();
        return view('frontend.contact', compact('settings'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
