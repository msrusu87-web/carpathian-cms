<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display customer dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get user's orders
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems')
            ->latest()
            ->paginate(10);
            
        // Calculate stats
        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('order_status', 'pending')->count();
        $completedOrders = Order::where('user_id', $user->id)->where('order_status', 'completed')->count();
        $totalSpent = Order::where('user_id', $user->id)->where('payment_status', 'completed')->sum('total');
        
        return view('dashboard.index', compact('user', 'orders', 'totalOrders', 'pendingOrders', 'completedOrders', 'totalSpent'));
    }

    /**
     * Display order details
     */
    public function showOrder($orderId)
    {
        $order = Order::with('orderItems')
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        return view('dashboard.orders.show', compact('order'));
    }

    /**
     * Display user profile
     */
    public function profile()
    {
        $user = auth()->user();
        return view('dashboard.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->update($validated);
        
        return back()->with('success', 'Profile updated successfully!');
    }
}
