<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PreSaleRequest;
use App\Models\EmailSetting;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        
        // Save pre-sale request to database
        $preSaleRequest = PreSaleRequest::create([
            'product_id' => $product->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);
        
        // Send notification to admin
        $this->notifyAdminOfPreSale($preSaleRequest, $product);
        
        return redirect()->back()->with('success', __('messages.pre_sale_submitted'));
    }
    
    /**
     * Send email notification to admin about new pre-sale request
     */
    private function notifyAdminOfPreSale(PreSaleRequest $preSaleRequest, Product $product)
    {
        try {
            // Get admin email from email settings
            $emailSettings = EmailSetting::first();
            $adminEmail = $emailSettings?->admin_email ?? config('mail.from.address');
            
            if (!$adminEmail) {
                Log::warning('No admin email configured for pre-sale notifications');
                return;
            }
            
            // Prepare email content
            $siteName = config('app.name', 'Carphatian');
            $subject = "[{$siteName}] Cerere nouă de pre-comandă: {$product->name}";
            
            $body = "
            <h2>Cerere nouă de pre-comandă</h2>
            
            <h3>Detalii Produs:</h3>
            <ul>
                <li><strong>Produs:</strong> {$product->name}</li>
                <li><strong>ID Produs:</strong> #{$product->id}</li>
                <li><strong>Preț:</strong> " . number_format($product->price, 2) . " RON</li>
            </ul>
            
            <h3>Detalii Client:</h3>
            <ul>
                <li><strong>Nume:</strong> {$preSaleRequest->name}</li>
                <li><strong>Email:</strong> <a href='mailto:{$preSaleRequest->email}'>{$preSaleRequest->email}</a></li>
                <li><strong>Telefon:</strong> " . ($preSaleRequest->phone ?: 'Nespecificat') . "</li>
            </ul>
            
            <h3>Mesaj:</h3>
            <p>" . ($preSaleRequest->message ?: 'Fără mesaj') . "</p>
            
            <hr>
            <p><small>Gestionați această cerere din <a href='" . url('/admin/pre-sale-requests/' . $preSaleRequest->id) . "'>panoul de administrare</a>.</small></p>
            ";
            
            // Send email using Laravel Mail
            Mail::html($body, function ($message) use ($adminEmail, $subject, $preSaleRequest) {
                $message->to($adminEmail)
                        ->replyTo($preSaleRequest->email, $preSaleRequest->name)
                        ->subject($subject);
            });
            
            Log::info('Pre-sale notification sent to admin', [
                'admin_email' => $adminEmail,
                'product_id' => $product->id,
                'request_id' => $preSaleRequest->id,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send pre-sale notification to admin: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'request_id' => $preSaleRequest->id,
            ]);
        }
    }
}
