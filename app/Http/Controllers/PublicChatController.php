<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicChatController extends Controller
{
    public function startChat(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:1000',
        ]);

        // Create new conversation
        $conversation = ChatConversation::create([
            'user_id' => null,
            'guest_name' => $request->name,
            'guest_email' => $request->email,
            'guest_phone' => $request->phone,
            'type' => 'presale',
            'status' => 'open',
            'subject' => 'Product Inquiry',
            'last_message_at' => now(),
        ]);

        // Create first message
        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => null,
            'is_admin' => false,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'is_admin' => false,
                'sender_name' => $request->name,
                'created_at' => $message->created_at->toIso8601String(),
            ],
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer|exists:chat_conversations,id',
            'message' => 'required|string|max:1000',
        ]);

        $conversation = ChatConversation::findOrFail($request->conversation_id);

        // Create message
        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => null,
            'is_admin' => false,
            'message' => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'is_admin' => false,
                'sender_name' => $conversation->guest_name ?? 'Guest',
                'created_at' => $message->created_at->toIso8601String(),
            ],
        ]);
    }

    public function getMessages(Request $request)
    {
        $conversationId = $request->query('conversation_id');
        
        if (!$conversationId) {
            return response()->json(['messages' => []]);
        }

        $conversation = ChatConversation::find($conversationId);
        
        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) use ($conversation) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_admin' => (bool) $msg->is_admin,
                    'sender_name' => $msg->is_admin ? 'Support Team' : ($conversation->guest_name ?? 'Guest'),
                    'created_at' => $msg->created_at->toIso8601String(),
                ];
            });

        // Mark admin messages as read
        $conversation->messages()
            ->where('is_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }

    public function checkConversation(Request $request)
    {
        $conversationId = $request->query('conversation_id');
        
        if (!$conversationId) {
            return response()->json(['exists' => false]);
        }

        $conversation = ChatConversation::find($conversationId);

        if (!$conversation) {
            return response()->json(['exists' => false]);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) use ($conversation) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_admin' => (bool) $msg->is_admin,
                    'sender_name' => $msg->is_admin ? 'Support Team' : ($conversation->guest_name ?? 'Guest'),
                    'created_at' => $msg->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'exists' => true,
            'messages' => $messages,
        ]);
    }
}
