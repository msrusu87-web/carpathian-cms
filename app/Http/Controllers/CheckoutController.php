<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentGateway;
use App\Mail\OrderConfirmation;

class CheckoutController extends Controller
{
    // Removed auth middleware to allow guest checkout

    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $paymentGateways = PaymentGateway::where('is_active', true)->get();

        return view('shop.checkout', compact('cart', 'total', 'paymentGateways'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'payment_gateway_id' => 'required|exists:payment_gateways,id',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty');
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Get payment gateway
        $paymentGateway = PaymentGateway::findOrFail($validated['payment_gateway_id']);

        // Calculate fee
        $fee = ($subtotal * $paymentGateway->fee_percentage / 100) + $paymentGateway->fee_fixed;

        // Calculate total
        $total = $subtotal + $fee;

        // Create order
        $order = Order::create([
            'user_id' => auth()->check() ? auth()->id() : null, // Allow guest orders
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'shipping_address' => $validated['address'] . ', ' . $validated['city'],
            'billing_address' => $validated['address'] . ', ' . $validated['city'],
            'subtotal' => $subtotal,
            'tax' => 0,
            'shipping' => 0,
            'total' => $total,
            'payment_method' => $paymentGateway->provider,
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'payment_details' => json_encode([
                'gateway_id' => $paymentGateway->id,
                'gateway_name' => $paymentGateway->name,
                'fee' => $fee,
            ]),
        ]);

        // Create order items
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $item['name'],
                'product_sku' => $item['sku'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }

        // Auto-assign Customer role if user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
                Log::info('Customer role assigned to user', ['user_id' => $user->id]);
            }
        }

        // Send order confirmation email
        try {
            Mail::to($validated['email'])->send(new OrderConfirmation($order));
            Log::info('Order confirmation email sent', ['order_id' => $order->id, 'email' => $validated['email']]);
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'email' => $validated['email'],
                'error' => $e->getMessage()
            ]);
        }

        // Clear cart
        session()->forget('cart');

        // Process payment based on gateway provider
        switch ($paymentGateway->provider) {
            case 'stripe':
                return $this->processStripePayment($order, $paymentGateway);
            case 'paypal':
                return $this->processPayPalPayment($order, $paymentGateway);
            case 'bank_transfer':
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Order placed successfully. Please complete the bank transfer.');
            default:
                return redirect()->route('checkout.success', $order->id);
        }
    }

    public function success($order = null)
    {
        if ($order) {
            $order = Order::find($order);
        }

        return view('shop.checkout-success', compact('order'));
    }

    protected function processStripePayment($order, $paymentGateway)
    {
        // TODO: Integrate Stripe API
        return redirect()->route('checkout.success', $order->id);
    }

    protected function processPayPalPayment($order, $paymentGateway)
    {
        // TODO: Integrate PayPal API
        return redirect()->route('checkout.success', $order->id);
    }
}
