<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
        public function sendMessage(Request $request)
        {
                $request->validate([
                        'message' => 'required|string|max:2000',
                        'client_token' => 'nullable|string|max:255',
                ]);

                $inputMessage = trim($request->message);
                $user = Auth::user();
                $clientToken = $request->input('client_token');

                // Xác định tiêu chí tìm conversation
                $query = Conversation::query()->where('status', 'open');

                if ($user) {
                        $query->where('user_id', $user->id);
                } elseif ($clientToken) {
                        $query->where('client_token', $clientToken);
                } else {
                        return response()->json(['error' => 'Không thể xác định người dùng'], 400);
                }

                // Tìm hoặc tạo cuộc trò chuyện
                $conversation = $query->first();
                if (!$conversation) {
                        $conversation = Conversation::create([
                                'user_id' => $user?->id,
                                'client_token' => $clientToken,
                                'is_bot_only' => true,
                                'status' => 'open'
                        ]);
                }

                // Lưu tin nhắn người gửi
                Message::create([
                        'conversation_id' => $conversation->id,
                        'sender_type' => $user ? 'user' : 'guest',
                        'sender_id' => $user?->id,
                        'message' => $inputMessage,
                        'is_from_bot' => false,
                ]);

                // Tìm câu trả lời
                $faq = $this->findMatchingFaq($inputMessage);
                if ($faq) {
                        $botReply = $faq->answer_vi;

                        Message::create([
                                'conversation_id' => $conversation->id,
                                'sender_type' => 'bot',
                                'sender_id' => null,
                                'message' => $botReply,
                                'is_from_bot' => true,
                        ]);

                        return response()->json([
                                'reply' => $botReply,
                                'from_bot' => true,
                                'conversation_id' => $conversation->id,
                        ]);
                }

                // Không tìm được câu trả lời → chuyển sang người thật
                $conversation->update([
                        'is_bot_only' => false,
                        'handover_to_user_id' => $this->assignStaff(),
                        'status' => 'active',
                ]);

                return response()->json([
                        'reply' => 'Câu hỏi này hiện chưa có trong dữ liệu. Nhân viên sẽ hỗ trợ bạn sớm nhất.',
                        'from_bot' => false,
                        'conversation_id' => $conversation->id,
                ]);
        }


        public function show(Request $request, $conversation_id = null)
        {
                $conversations = Conversation::with(['user'])
                        ->withCount('messages')
                        ->orderByDesc('updated_at')
                        ->get();

                $activeConversation = $conversation_id
                        ? Conversation::with(['messages'])->find($conversation_id)
                        : null;

                return view('admin.chat-support', compact('conversations', 'activeConversation'));
        }

        public function send(Request $request, $conversation_id)
        {
                $request->validate([
                        'message' => 'required|string|max:1000',
                ]);

                $conversation = Conversation::findOrFail($conversation_id);

                Message::create([
                        'conversation_id' => $conversation->id,
                        'sender_type' => 'staff',
                        'sender_id' => auth()->id(),
                        'message' => $request->message,
                        'is_from_bot' => false,
                ]);

                $conversation->touch(); // cập nhật updated_at

                return redirect()->route('admin.chat-support', ['conversation_id' => $conversation->id]);
        }

        /**
         * Tìm câu trả lời gần giống từ bảng faqs
         */
        private function findMatchingFaq(string $input): ?Faq
        {
                $faqs = Faq::where('is_active', 1)->get();

                $bestMatch = null;
                $highestScore = 0;

                foreach ($faqs as $faq) {
                        similar_text(mb_strtolower($faq->question_vi), mb_strtolower($input), $percent);

                        if ($percent > $highestScore) {
                                $highestScore = $percent;
                                $bestMatch = $faq;
                        }
                }

                return $highestScore >= 80 ? $bestMatch : null;
        }

        /**
         * Hàm gán nhân viên tiếp nhận cuộc trò chuyện (giản lược)
         */
        private function assignStaff(): int
        {
                // TODO: Gán logic phân công nhân viên thật sự ở đây
                // Tạm thời: gán nhân viên ID cố định là 1
                return 1;
        }
}
