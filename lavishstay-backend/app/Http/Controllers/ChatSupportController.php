<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatSupportController extends Controller
{
    public function index(Request $request)
    {
        $conversationId = $request->get('conversation_id');
        
        // Get conversations with message counts and latest message
        $conversations = Conversation::with(['user', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->withCount('messages')
            ->addSelect([
                'last_message' => Message::select('message')
                    ->whereColumn('conversation_id', 'conversations.id')
                    ->latest()
                    ->limit(1),
                'unread_count' => Message::selectRaw('COUNT(*)')
                    ->whereColumn('conversation_id', 'conversations.id')
                    ->where('sender_type', '!=', 'staff')
                    ->where('is_read', false)
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get active conversation if specified
        $activeConversation = null;
        if ($conversationId) {
            $activeConversation = Conversation::with(['user', 'messages' => function($query) {
                $query->orderBy('created_at', 'asc');
            }])->find($conversationId);
            
            // Mark messages as read
            if ($activeConversation) {
                Message::where('conversation_id', $conversationId)
                    ->where('sender_type', '!=', 'staff')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        // Get statistics
        $stats = [
            'pending' => Conversation::where('status', 'pending')->count(),
            'active' => Conversation::where('status', 'active')->count(),
            'resolved' => Conversation::where('status', 'closed')->count(),
        ];

        return view('admin.chat-support', compact('conversations', 'activeConversation', 'stats'));
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::findOrFail($conversationId);
        
        // Create message
        $message = Message::create([
            'conversation_id' => $conversationId,
            'sender_type' => 'staff',
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'is_from_bot' => false,
        ]);

        // Update conversation status and timestamp
        $conversation->update([
            'status' => 'active',
            'updated_at' => now(),
        ]);

        // Redirect back with success message
        return redirect()->route('admin.chat-support', ['conversation_id' => $conversationId])
            ->with('success', 'Tin nhắn đã được gửi thành công!');
    }

    public function updateStatus(Request $request, $conversationId)
    {
        $request->validate([
            'status' => 'required|in:pending,active,closed',
        ]);

        $conversation = Conversation::findOrFail($conversationId);
        $conversation->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật',
        ]);
    }

    public function getLatestMessages(Request $request, $conversationId)
    {
                $lastMessageId = $request->get('last_message_id', 0);
        
        $messages = Message::where('conversation_id', $conversationId)
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'count' => $messages->count(),
        ]);
    }

    public function markAsRead(Request $request, $conversationId)
    {
        Message::where('conversation_id', $conversationId)
            ->where('sender_type', '!=', 'staff')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu là đã đọc',
        ]);
    }

    public function deleteConversation($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        // Delete all messages first
        Message::where('conversation_id', $conversationId)->delete();
        
        // Delete conversation
        $conversation->delete();

        return redirect()->route('admin.chat-support')
            ->with('success', 'Cuộc hội thoại đã được xóa thành công!');
    }

    public function exportConversation($conversationId)
    {
        $conversation = Conversation::with(['user', 'messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail($conversationId);

        $content = "=== XUẤT CUỘC HỘI THOẠI ===\n";
        $content .= "Thời gian: " . now()->format('d/m/Y H:i:s') . "\n";
        $content .= "Khách hàng: " . ($conversation->user ? $conversation->user->name : 'Ẩn danh #' . substr($conversation->client_token, 0, 6)) . "\n";
        $content .= "Trạng thái: " . $conversation->status . "\n";
        $content .= "Tổng tin nhắn: " . $conversation->messages->count() . "\n\n";

        foreach ($conversation->messages as $message) {
            $sender = $message->sender_type === 'staff' ? 'Nhân viên' : 
                     ($message->is_from_bot ? 'Bot' : 'Khách hàng');
            $content .= "[{$message->created_at->format('d/m/Y H:i:s')}] {$sender}: {$message->message}\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="conversation_' . $conversationId . '_' . now()->format('Y-m-d_H-i-s') . '.txt"');
    }
}

