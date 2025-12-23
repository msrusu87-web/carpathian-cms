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
Route::get('/generate-sitemap', [SitemapController::class, 'generate'])->name('sitemap.generate');
Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml');
    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots.txt');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

// Dynamic Pages Route (MUST BE LAST - catch-all)
Route::get('/{slug}', [FrontendController::class, 'page'])->name('page.show');
