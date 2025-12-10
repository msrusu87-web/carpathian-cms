<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        // Get cart from session (assuming CartController manages this)
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Coșul este gol');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('shop.checkout', compact('cart', 'total'));
    }

    /**
     * Process checkout
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'payment_method' => 'required|in:card,paypal,transfer',
        ]);

        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Coșul este gol');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create order (you may need to create the Order model and migration)
        // $order = Order::create([
        //     'user_id' => auth()->id(),
        //     'name' => $validated['name'],
        //     'email' => $validated['email'],
        //     'phone' => $validated['phone'],
        //     'address' => $validated['address'],
        //     'city' => $validated['city'],
        //     'total' => $total,
        //     'payment_method' => $validated['payment_method'],
        //     'status' => 'pending',
        // ]);

        // Clear cart
        session()->forget('cart');

        return redirect()->route('checkout.success')->with('success', 'Comanda a fost plasată cu succes!');
    }

    /**
     * Display success page
     */
    public function success()
    {
        return view('shop.checkout-success');
    }
}
