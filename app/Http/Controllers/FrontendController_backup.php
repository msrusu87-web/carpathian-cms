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
        $homepage = Page::where('is_homepage', true)
            ->where('status', 'published')
            ->first();

        if ($homepage) {
            // If homepage has a template, render it
            if ($homepage->template_id) {
                return response($this->renderer->renderPage($homepage));
            }
            
            // Otherwise use custom home view with products and posts
            $products = Product::where('is_active', true)->take(6)->get();
            $posts = Post::where('status', 'published')->latest()->take(3)->get();
            
            return view('frontend.home', [
                'page' => $homepage,
                'products' => $products,
                'posts' => $posts
            ]);
        }

        // Fallback to default welcome view if no homepage exists
        $products = Product::where('is_active', true)->take(6)->get();
        $posts = Post::where('status', 'published')->latest()->take(3)->get();
        
        return view('welcome', compact('products', 'posts'));
    }

    public function page(string $slug)
    {
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

        return response($this->renderer->renderPost($post));
    }

    public function blog()
    {
        $posts = Post::where('status', 'published')
            ->with(['category', 'user'])
            ->latest()
            ->paginate(12);

        // Get all categories that have posts
        $categories = Category::whereHas('posts', function($query) {
                $query->where('status', 'published');
            })
            ->withCount(['posts' => function($query) {
                $query->where('status', 'published');
            }])
            ->get();

        return view('frontend.blog', compact('posts', 'categories'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::where('status', 'published')
            ->where('category_id', $category->id)
            ->with(['category', 'user'])
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
        $query = $request->get('q');
        
        $posts = Post::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with(['category', 'user'])
            ->latest()
            ->paginate(12);

        return view('frontend.search', compact('posts', 'query'));
    }

    public function contact()
    {
        $settings = ContactSetting::firstOrCreate(
            [],
            [
                'email' => 'info@webagency.com',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Business Street, Suite 100',
                'business_hours' => 'Mon-Fri: 9AM-6PM',
                'map_embed' => null,
                'show_map' => false,
            ]
        );

        return view('frontend.contact', compact('settings'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
