<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'sku' => $product->sku ?? 'N/A',
                'price' => $product->sale_price ?? $product->price,
                'quantity' => $quantity,
                'image' => $product->featured_image,
                'slug' => $product->slug
            ];
        }
        
        session()->put('cart', $cart);
        session()->save(); // Ensure session is saved
        
        // Calculate total count for notification
        $totalCount = 0;
        foreach ($cart as $item) {
            $totalCount += $item['quantity'];
        }
        
        return redirect()->route('cart.index')->with('success', "Product added! Cart has {$totalCount} item(s)");
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $quantity = $request->input('quantity', 1);
            if ($quantity > 0) {
                $cart[$id]['quantity'] = $quantity;
            } else {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }
        
        return back()->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        session()->forget('cart');
        
        return back()->with('success', 'Cart cleared!');
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return response()->json(['count' => $count]);
    }
}
