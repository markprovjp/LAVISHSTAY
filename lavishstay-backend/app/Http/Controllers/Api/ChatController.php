<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Faq;
use App\Services\ChatBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    protected $chatBotService;

    public function __construct(ChatBotService $chatBotService)
    {
        $this->chatBotService = $chatBotService;
    }

    /**
     * Send a message from user/guest
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'client_token' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $clientToken = $request->client_token;

        // If no user and no client token, generate one
        if (!$userId && !$clientToken) {
            $clientToken = Str::uuid()->toString();
        }

        // Find or create conversation
        $conversation = $this->findOrCreateConversation($userId, $clientToken);

        // Save user message
        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => $userId ? 'user' : 'guest',
            'sender_id' => $userId,
            'message' => $request->message,
            'is_from_bot' => false,
        ]);

        $messages = [
            [
                'id' => $userMessage->id,
                'message' => $userMessage->message,
                'sender_type' => $userMessage->sender_type,
                'is_from_bot' => false,
                'created_at' => $userMessage->created_at->toISOString(),
            ]
        ];

        // Try to get bot response
        $botResponse = $this->chatBotService->getResponse($request->message);

        if ($botResponse) {
            // Save bot response
            $botMessage = Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'staff',
                'sender_id' => null,
                'message' => $botResponse['answer'],
                'is_from_bot' => true,
                'metadata' => [
                    'faq_id' => $botResponse['faq_id'] ?? null,
                    'confidence' => $botResponse['confidence'] ?? null,
                ],
            ]);

            $messages[] = [
                'id' => $botMessage->id,
                'message' => $botMessage->message,
                'sender_type' => $botMessage->sender_type,
                'is_from_bot' => true,
                'created_at' => $botMessage->created_at->toISOString(),
            ];

            // Update conversation timestamp
            $conversation->touch();

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'client_token' => $clientToken,
                'messages' => $messages,
            ]);
        } else {
            // No bot response, escalate to human
            $conversation->update([
                'is_bot_only' => false,
                'status' => 'pending',
            ]);

            // Add system message about escalation
            $systemMessage = Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'staff',
                'sender_id' => null,
                'message' => 'Câu hỏi của bạn đã được chuyển đến nhân viên hỗ trợ. Chúng tôi sẽ trả lời sớm nhất có thể.',
                'is_from_bot' => true,
                'message_type' => 'system',
            ]);

            $messages[] = [
                'id' => $systemMessage->id,
                'message' => $systemMessage->message,
                'sender_type' => $systemMessage->sender_type,
                'is_from_bot' => true,
                'created_at' => $systemMessage->created_at->toISOString(),
            ];

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'client_token' => $clientToken,
                'escalated' => true,
                'message' => 'Câu hỏi của bạn đã được chuyển đến nhân viên hỗ trợ. Chúng tôi sẽ trả lời sớm nhất có thể.',
                'messages' => $messages,
            ]);
        }
    }

    /**
     * Get conversation messages
     */
    public function getConversation(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'client_token' => 'nullable|string',
        ]);

        $conversation = Conversation::with(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }])->find($request->conversation_id);

        // Verify access
        if (!$this->canAccessConversation($conversation, $request->client_token)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'status' => $conversation->status,
                'messages' => $conversation->messages->map(function($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'sender_type' => $message->sender_type,
                        'is_from_bot' => $message->is_from_bot,
                        'created_at' => $message->created_at->toISOString(),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Get new messages since last check
     */
    public function getNewMessages(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'last_message_id' => 'required|integer',
            'client_token' => 'nullable|string',
        ]);

        $conversation = Conversation::find($request->conversation_id);

        // Verify access
        if (!$this->canAccessConversation($conversation, $request->client_token)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $newMessages = Message::where('conversation_id', $request->conversation_id)
            ->where('id', '>', $request->last_message_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $newMessages->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'is_from_bot' => $message->is_from_bot,
                    'created_at' => $message->created_at->toISOString(),
                ];
            }),
        ]);
    }

    /**
     * Get FAQs for quick responses
     */
    public function getFaqs(Request $request)
    {
        $faqs = Faq::active()
            ->byPriority()
            ->limit(10)
            ->get(['id', 'question', 'answer', 'category']);

        return response()->json([
            'success' => true,
            'faqs' => $faqs,
        ]);
    }

    /**
     * Find or create conversation
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
                'status' => 'pending',
                'is_bot_only' => true,
            ]);
        }

        return $conversation;
    }

    /**
     * Check if user can access conversation
     */
    private function canAccessConversation($conversation, $clientToken)
    {
        if (Auth::check() && $conversation->user_id === Auth::id()) {
            return true;
        }

        if (!Auth::check() && $conversation->client_token === $clientToken) {
            return true;
        }

        return false;
    }
}
