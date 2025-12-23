<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display shop homepage with all products
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->active()->inStock();
        
        // Apply filters
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sorting
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'asc');
        
        if ($sort === 'price') {
            $query->orderBy('price', $order);
        } elseif ($sort === 'name') {
            $query->orderBy('name', $order);
        } elseif ($sort === 'newest') {
            $query->latest();
        }
        
        $products = $query->paginate(12);
        
        // Load categories with children and product counts
        $categories = ProductCategory::with(['children' => function($q) {
                $q->withCount('products')->orderBy('order');
            }])
            ->active()
            ->withCount('products')
            ->orderBy('order')
            ->get();
        
        return view('shop.index', compact('products', 'categories'));
    }

    /**
     * Display all products (same as index but different route)
     */
    public function products(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Display all categories
     */
    public function categories()
    {
        $categories = ProductCategory::with(['children' => function($q) {
                $q->withCount('products')->orderBy('order');
            }])
            ->active()
            ->withCount('products')
            ->orderBy('order')
            ->get();

        return view('shop.categories', compact('categories'));
    }
    
    /**
     * Display single product details
     */
    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->active()->firstOrFail();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
                                   ->where('id', '!=', $product->id)
                                   ->active()
                                   ->inStock()
                                   ->limit(4)
                                   ->get();
        
        return view('shop.show', compact('product', 'relatedProducts'));
    }
    
    /**
     * Display products by category
     */
    public function category($slug)
    {
        $category = ProductCategory::where('slug', $slug)->active()->firstOrFail();
        
        $products = $category->products()->active()->inStock()->paginate(12);
        
        $categories = ProductCategory::with(['children' => function($q) {
                $q->withCount('products')->orderBy('order');
            }])
            ->active()
            ->withCount('products')
            ->orderBy('order')
            ->get();
        
        return view('shop.category', compact('category', 'products', 'categories'));
    }

    /**
     * Display pre-sale form for a product
     */
    public function preSaleForm($productId)
    {
        $product = Product::findOrFail($productId);
        return view('shop.pre-sale', compact('product'));
    }

    /**
     * Handle pre-sale form submission
     */
    public function preSaleSubmit(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:1000',
        ]);
        
        // Store pre-sale interest (you can customize this to send email or save to DB)
        // For now, just redirect back with success
        return redirect()->back()->with('success', __('messages.pre_sale_submitted'));
    }
}
