<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = ChatConversation::with(['user', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])
        ->withCount(['messages as unread_count' => function($q) {
            $q->where('is_admin', false)->where('is_read', false);
        }])
        ->latest('last_message_at')
        ->paginate(20);

        return view('admin.chat.index', compact('conversations'));
    }

    public function show($id)
    {
        $conversation = ChatConversation::with(['user'])->findOrFail($id);
        
        // Mark user messages as read
        $conversation->messages()
            ->where('is_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Get messages for initial load
        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) use ($conversation) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_admin' => (bool) $msg->is_admin,
                    'sender_name' => $msg->is_admin ? ($msg->user->name ?? 'Admin') : ($conversation->participant_name ?? 'Guest'),
                    'created_at' => $msg->created_at->toIso8601String(),
                ];
            });

        return view('admin.chat.show', compact('conversation', 'messages'));
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $conversation = ChatConversation::findOrFail($id);
        $admin = Auth::user();

        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $admin->id,
            'is_admin' => true,
            'message' => $request->message,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'assigned_admin_id' => $admin->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chatMessage->id,
                'message' => $chatMessage->message,
                'is_admin' => true,
                'sender_name' => $admin->name,
                'created_at' => $chatMessage->created_at->toIso8601String(),
            ],
        ]);
    }

    public function getMessages($id)
    {
        $conversation = ChatConversation::findOrFail($id);
        
        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) use ($conversation) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_admin' => (bool) $msg->is_admin,
                    'sender_name' => $msg->is_admin ? ($msg->user->name ?? 'Admin') : ($conversation->participant_name ?? 'Guest'),
                    'created_at' => $msg->created_at->toIso8601String(),
                ];
            });

        // Mark user messages as read
        $conversation->messages()
            ->where('is_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }

    public function closeConversation($id)
    {
        $conversation = ChatConversation::findOrFail($id);
        $conversation->update(['status' => 'closed']);

        return back()->with('success', 'Conversation closed.');
    }

    public function getUnreadCount()
    {
        $count = ChatMessage::where('is_admin', false)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
