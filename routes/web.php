<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

// Auth Test Route - for debugging authentication
Route::get('/auth-test', function() {
    if (Auth::check()) {
        $user = Auth::user();
        return response()->json([
            'authenticated' => true,
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'roles' => $user->getRoleNames(),
            'canAccessAdmin' => $user->canAccessAdmin(),
            'session_id' => session()->getId(),
        ]);
    }
    return response()->json([
        'authenticated' => false, 
        'session_id' => session()->getId()
    ]);
})->name('auth.test');

// Language Switcher Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ro', 'de', 'es', 'fr', 'it'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
        // Persist across sessions as well (1 year)
        cookie()->queue('locale', $locale, 60 * 24 * 365);
    }
    // Safe redirect back (falls back to homepage if no referrer)
    $previous = url()->previous();
    $current = url()->current();
    $redirectTo = (!empty($previous) && $previous !== $current) ? $previous : url('/');

    return redirect()->to($redirectTo);
})->name('lang.switch');

// Backward-compatible locale shortcuts
Route::get('/en', fn () => redirect()->route('lang.switch', ['locale' => 'en']))->name('locale.en');
Route::get('/ro', fn () => redirect()->route('lang.switch', ['locale' => 'ro']))->name('locale.ro');

// Currency Switcher Route
Route::post('/currency/switch', function (\Illuminate\Http\Request $request) {
    $currency = $request->input('currency', 'EUR');
    if (in_array($currency, ['EUR', 'USD', 'GBP', 'RON'])) {
        Session::put('currency', $currency);
        cookie()->queue('currency', $currency, 60 * 24 * 365);
    }
    return redirect()->back();
})->name('currency.switch');

// Homepage
Route::get('/', [FrontendController::class, 'index'])->name('home');

// Blog Routes
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [FrontendController::class, 'post'])->name('blog.show');
Route::get('/posts/{slug}', [FrontendController::class, 'post'])->name('post.show');
Route::get('/category/{slug}', [FrontendController::class, 'category'])->name('category');
Route::get('/tag/{slug}', [FrontendController::class, 'tag'])->name('tag');
Route::get('/search', [FrontendController::class, 'search'])->name('search');

// Contact Routes
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'contactSubmit'])->name('contact.submit');

// Portfolio Routes
Route::get('/portfolio', [\App\Http\Controllers\PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [\App\Http\Controllers\PortfolioController::class, 'show'])->name('portfolio.show');

// Shop Routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/products', [ShopController::class, 'products'])->name('shop.products');
Route::get('/shop/categories', [ShopController::class, 'categories'])->name('shop.categories');
Route::get('/shop/products/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/pre-sale/{product}', [ShopController::class, 'preSaleForm'])->name('pre-sale.form');
Route::post('/pre-sale/{product}', [ShopController::class, 'preSaleSubmit'])->name('pre-sale.submit');

// Sitemap & SEO Routes (MUST BE BEFORE DYNAMIC PAGES)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.xml');
Route::get('/sitemap-news.xml', [\App\Http\Controllers\NewsSitemapController::class, 'index'])->name('sitemap.news');
Route::get('/generate-sitemap', [SitemapController::class, 'generate'])->name('sitemap.generate');
Route::get('/generate-news-sitemap', [\App\Http\Controllers\NewsSitemapController::class, 'generate'])->name('sitemap.news.generate');
Route::get('/robots.txt', function () {
    return response()->file(public_path('robots.txt'), ['Content-Type' => 'text/plain']);
})->name('robots.txt');

Route::get('/dashboard', function () {
    return redirect()->route('client.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Cart Routes - available to all users
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout Routes - available to all users (guest or authenticated)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order?}', [CheckoutController::class, 'success'])->name('checkout.success');

require __DIR__.'/auth.php';

// Admin Chat Routes
Route::middleware(['auth'])->prefix('admin-chat')->name('admin.chat.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('index');
    Route::get('/{id}', [\App\Http\Controllers\Admin\ChatController::class, 'show'])->name('show');
    Route::post('/{id}/send', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('send');
    Route::get('/{id}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('messages');
    Route::post('/{id}/close', [\App\Http\Controllers\Admin\ChatController::class, 'closeConversation'])->name('close');
    Route::get('/unread-count', [\App\Http\Controllers\Admin\ChatController::class, 'getUnreadCount'])->name('unread');
});

// Dynamic Pages Route (MUST BE LAST - catch-all)
Route::get('/{slug}', [FrontendController::class, 'page'])->name('page.show');

// Client area routes
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [\App\Http\Controllers\ClientController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [\App\Http\Controllers\ClientController::class, 'orderShow'])->name('orders.show');
    Route::get('/order/{id}', [\App\Http\Controllers\ClientController::class, 'orderShow'])->name('order.show');
    Route::get('/support', [\App\Http\Controllers\ClientController::class, 'support'])->name('support');
    Route::get('/chat', [\App\Http\Controllers\ClientController::class, 'supportChat'])->name('chat');
    Route::get('/chat/{id}', [\App\Http\Controllers\ClientController::class, 'supportChat'])->name('chat.show');
    Route::post('/chat/new', [\App\Http\Controllers\ClientController::class, 'newConversation'])->name('chat.new');
    Route::post('/chat/{id}/send', [\App\Http\Controllers\ClientController::class, 'sendMessage'])->name('chat.send');
    Route::get('/invoices', [\App\Http\Controllers\Client\InvoiceController::class, 'index'])->name('invoices');
    Route::get('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\Client\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [\App\Http\Controllers\Client\InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('/invoices/{invoice}/preview', [\App\Http\Controllers\Client\InvoiceController::class, 'preview'])->name('invoices.preview');
});
