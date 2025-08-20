<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * NEW: Get the hotel context from the markdown file.
     * This endpoint provides the necessary information for the AI on the frontend.
     */
    public function getHotelContext()
    {
        try {
            // Correctly build the path to the markdown file from the Laravel project root
            $path = base_path('../lavishstay.mm.md');

            if (!File::exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy tệp dữ liệu của khách sạn.',
                ], 404);
            }

            $content = File::get($path);

            return response()->json([
                'success' => true,
                'context' => $content,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to read hotel context file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi máy chủ khi đọc dữ liệu khách sạn.',
            ], 500);
        }
    }

    /**
     * MODIFIED: Log the conversation (user message and AI response).
     * The AI response is now generated on the frontend.
     */
    public function logConversation(Request $request)
    {
        $request->validate([
            'user_message' => 'required|string|max:2000',
            'ai_response' => 'required|string|max:5000',
            'client_token' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $clientToken = $request->client_token ?? Str::uuid()->toString();

        // Find or create conversation
        $conversation = $this->findOrCreateConversation($userId, $clientToken);

        // Save user message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => $userId ? 'user' : 'guest',
            'sender_id' => $userId,
            'message' => $request->user_message,
            'is_from_bot' => false,
        ]);

        // Save AI response
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'staff',
            'sender_id' => null,
            'message' => $request->ai_response,
            'is_from_bot' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cuộc trò chuyện đã được ghi lại.',
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Find or create a conversation record.
     */
    private function findOrCreateConversation($userId, $clientToken)
    {
        $query = Conversation::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('client_token', $clientToken);
        }

        $conversation = $query->where('status', '!=', 'closed')
            ->latest()
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $userId,
                'client_token' => $clientToken,
                'status' => 'open', // Bot conversations are always open
                'is_bot_only' => true,
            ]);
        }

        return $conversation;
    }
    
    // Note: The original sendMessage, getConversation, etc. can be kept, modified, or removed
    // depending on whether you want to support a hybrid mode or just the AI logging.
    // For clarity, I'm assuming they are no longer the primary flow.
}
