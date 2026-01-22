<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orWhere('customer_email', $user->email)
            ->latest()
            ->get();
        
        $conversations = ChatConversation::where('user_id', $user->id)
            ->with('messages')
            ->latest()
            ->get();

        $unreadCount = ChatMessage::whereHas('conversation', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('is_admin', true)->where('is_read', false)->count();

        return view('client.dashboard', compact('orders', 'conversations', 'unreadCount'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orWhere('customer_email', $user->email)
            ->latest()
            ->paginate(10);

        return view('client.orders', compact('orders'));
    }

    public function orderShow($id)
    {
        $user = Auth::user();
        $order = Order::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('customer_email', $user->email);
        })->findOrFail($id);

        return view('client.order-detail', compact('order'));
    }

    public function support()
    {
        $user = Auth::user();
        $conversations = ChatConversation::where('user_id', $user->id)
            ->with(['messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->latest('last_message_at')
            ->get();

        return view('client.support', compact('conversations'));
    }

    public function supportChat($id = null)
    {
        $user = Auth::user();
        
        // If no ID provided, redirect to support page
        if (!$id) {
            return redirect()->route('client.support');
        }
        
        $conversation = ChatConversation::where('user_id', $user->id)
            ->with('messages')
            ->findOrFail($id);
        
        // Mark messages as read
        $conversation->messages()
            ->where('is_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $conversations = ChatConversation::where('user_id', $user->id)
            ->latest('last_message_at')
            ->get();
        
        $messages = $conversation->messages;

        return view('client.chat', compact('conversation', 'conversations', 'messages'));
    }

    public function newConversation(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = Auth::user();
        
        $conversation = ChatConversation::create([
            'user_id' => $user->id,
            'type' => 'support',
            'status' => 'open',
            'subject' => $request->subject,
            'last_message_at' => now(),
        ]);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'is_admin' => false,
            'message' => $request->message,
        ]);

        return redirect()->route('client.chat', $conversation->id)
            ->with('success', __('Conversation started!'));
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();
        $conversation = ChatConversation::where('user_id', $user->id)
            ->findOrFail($conversationId);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'is_admin' => false,
            'message' => $request->message,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status' => 'open',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function getMessages($conversationId)
    {
        $user = Auth::user();
        $conversation = ChatConversation::where('user_id', $user->id)
            ->findOrFail($conversationId);

        $messages = $conversation->messages()
            ->with('user')
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        // Mark admin messages as read
        $conversation->messages()
            ->where('is_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json($messages);
    }
}
