<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-4 w-full max-w-9xl mx-auto">
        <!-- Header với thống kê tổng quan -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Hỗ trợ khách hàng</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Quản lý và trả lời tin nhắn từ khách hàng</p>
                </div>
                <div class="flex gap-4 mt-4 sm:mt-0">
                    <div class="bg-blue-50 dark:bg-blue-900/20 px-4 py-2 rounded-lg">
                        <div class="text-sm text-blue-600 dark:text-blue-400">Chờ trả lời</div>
                        <div class="text-lg font-semibold text-blue-700 dark:text-blue-300">{{ $stats['pending'] ?? 0 }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded-lg">
                        <div class="text-sm text-green-600 dark:text-green-400">Đã xử lý</div>
                        <div class="text-lg font-semibold text-green-700 dark:text-green-300">{{ $stats['resolved'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[calc(100vh-200px)]">
            
            {{-- Sidebar danh sách hội thoại --}}
            <div class="col-span-1 bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Header sidebar với filter -->
                <div class="p-4 border-b dark:bg-gray-800  border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-750">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Hội thoại</h2>
                        <button class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Filter tabs -->
                    <div class="flex space-x-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition-colors bg-white dark:bg-gray-600 text-gray-900 dark:text-gray-100 shadow-sm">
                            Tất cả
                        </button>
                        <button class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                            Chờ
                        </button>
                        <button class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                            Đóng
                        </button>
                    </div>
                </div>

                <!-- Danh sách hội thoại -->
                <div class="overflow-y-auto h-full">
                    @forelse ($conversations as $conversation)
                        <a href="{{ route('admin.chat-support', ['conversation_id' => $conversation->id]) }}"
                           class="block border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $activeConversation?->id === $conversation->id ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-l-blue-500' : '' }}">
                            <div class="p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        <!-- Avatar -->
                                        <div class="flex-shrink-0">
                                            @if($conversation->user)
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                    {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                                                </div>
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                    {{ $conversation->user?->name ?? 'Ẩn danh #' . Str::substr($conversation->client_token, 0, 6) }}
                                                </p>
                                                @if($conversation->unread_count > 0)
                                                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                                        {{ $conversation->unread_count }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-1">
                                                {{ Str::limit($conversation->last_message ?? 'Chưa có tin nhắn', 40) }}
                                            </p>
                                            
                                            <div class="flex items-center justify-between mt-2">
                                                <span class="text-xs text-gray-400">
                                                    {{ $conversation->updated_at->diffForHumans() }}
                                                </span>
                                                
                                                <!-- Status badge -->
                                                @switch($conversation->status)
                                                    @case('pending')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                                            Chờ
                                                        </span>
                                                        @break
                                                    @case('active')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                            Đang xử lý
                                                        </span>
                                                        @break
                                                    @case('closed')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                            Đã đóng
                                                        </span>
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có hội thoại nào</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Khung chi tiết hội thoại --}}
            <div class="col-span-3 bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
                @if ($activeConversation)
                    <!-- Header hội thoại -->
                    <div class="px-6 dark:bg-gray-800  py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-750">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Avatar lớn -->
                                @if($activeConversation->user)
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($activeConversation->user->name, 0, 1)) }}
                                    </div>
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $activeConversation->user?->name ?? 'Ẩn danh #' . Str::substr($activeConversation->client_token, 0, 6) }}
                                    </h2>
                                    @if($activeConversation->user)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $activeConversation->user->email }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Khách vãng lai</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center space-x-2">
                                <!-- Status dropdown -->
                                <select class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="pending" {{ $activeConversation->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="active" {{ $activeConversation->status === 'active' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="closed" {{ $activeConversation->status === 'closed' ? 'selected' : '' }}>Đã đóng</option>
                                </select>
                                
                                <!-- More actions -->
                                <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Nội dung tin nhắn --}}
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900" id="messages-container">
                        @foreach ($activeConversation->messages as $msg)
                            <div class="flex {{ $msg->sender_type === 'staff' ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-end space-x-2 max-w-xs lg:max-w-md">
                                    @if($msg->sender_type !== 'staff')
                                        <!-- Avatar người gửi -->
                                        <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 flex-shrink-0 flex items-center justify-center">
                                            @if($msg->is_from_bot)
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="flex flex-col">
                                        <!-- Message bubble -->
                                        <div class="px-4 py-3 rounded-2xl text-sm relative
                                            {{ $msg->sender_type === 'staff' 
                                                ? 'bg-blue-600 text-white rounded-br-md' 
                                                : ($msg->is_from_bot 
                                                    ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-900 dark:text-purple-100 rounded-bl-md border border-purple-200 dark:border-purple-700' 
                                                    : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-bl-md shadow-sm border border-gray-200 dark:border-gray-600') }}">
                                            
                                            @if($msg->is_from_bot)
                                                <div class="flex items-center mb-1">
                                                    <svg class="w-3 h-3 mr-1 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-xs font-medium text-purple-600 dark:text-purple-400">Bot tự động</span>
                                                </div>
                                            @endif
                                            
                                            <div class="whitespace-pre-wrap">{{ $msg->message }}</div>
                                        </div>
                                        
                                        <!-- Timestamp -->
                                        <div class="flex items-center mt-1 {{ $msg->sender_type === 'staff' ? 'justify-end' : 'justify-start' }}">
                                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ $msg->created_at->format('H:i') }}
                                            </span>
                                            @if($msg->sender_type === 'staff')
                                                <!-- Delivery status -->
                                                <svg class="w-3 h-3 ml-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($msg->sender_type === 'staff')
                                        <!-- Staff avatar -->
                                        <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Typing indicator (hidden by default) -->
                        <div id="typing-indicator" class="flex justify-start hidden">
                            <div class="flex items-end space-x-2 max-w-xs">
                                <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 flex-shrink-0"></div>
                                <div class="bg-white dark:bg-gray-700 px-4 py-3 rounded-2xl rounded-bl-md shadow-sm border border-gray-200 dark:border-gray-600">
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick replies templates --}}
                    <div class="px-6 py-3 dark:bg-gray-800  border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-750">
                        <div class="flex space-x-2 overflow-x-auto">
                            <button class="flex-shrink-0 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors" 
                                    onclick="insertQuickReply('Xin chào! Tôi có thể giúp gì cho bạn?')">
                                👋 Chào hỏi
                            </button>
                            <button class="flex-shrink-0 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                    onclick="insertQuickReply('Cảm ơn bạn đã liên hệ. Chúng tôi sẽ xử lý yêu cầu của bạn sớm nhất có thể.')">
                                ✅ Xác nhận
                            </button>
                            <button class="flex-shrink-0 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                    onclick="insertQuickReply('Bạn có thể xem thông tin chi tiết tại: {{ route('home') }}')">
                                🔗 Gửi link
                            </button>
                            <button class="flex-shrink-0 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                    onclick="insertQuickReply('Cảm ơn bạn! Chúc bạn có trải nghiệm tuyệt vời tại LavishStay.')">
                                🙏 Cảm ơn
                            </button>
                        </div>
                    </div>

                    {{-- Form gửi tin nhắn --}}
                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <form action="{{ route('admin.chat-support.send', $activeConversation->id) }}" method="POST" id="message-form" class="flex items-end space-x-4">
                            @csrf
                            <div class="flex-1">
                                <div class="relative">
                                    <textarea 
                                        name="message" 
                                        id="message-input"
                                        rows="1"
                                        class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                        placeholder="Nhập tin nhắn... (Enter để gửi, Shift+Enter để xuống dòng)"
                                        required></textarea>
                                    
                                    <!-- Emoji button -->
                                    <button type="button" class="absolute right-3 bottom-3 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Character count -->
                                <div class="flex justify-between items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span>Shift + Enter để xuống dòng</span>
                                    <span id="char-count">0/1000</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <!-- Attach file button -->
                                <button type="button" class="p-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                </button>
                                
                                <!-- Send button -->
                                <button type="submit" id="send-button"
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white rounded-xl font-medium transition-colors flex items-center space-x-2">
                                    <span>Gửi</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    {{-- Empty state --}}
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chọn một hội thoại</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                                Chọn một cuộc hội thoại từ danh sách bên trái để bắt đầu trả lời khách hàng
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- JavaScript cho chat functionality --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageInput = document.getElementById('message-input');
            const messageForm = document.getElementById('message-form');
            const messagesContainer = document.getElementById('messages-container');
            const charCount = document.getElementById('char-count');
            const sendButton = document.getElementById('send-button');

            // Auto-resize textarea
            if (messageInput) {
                messageInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                    
                    // Update character count
                    const count = this.value.length;
                    if (charCount) {
                        charCount.textContent = `${count}/1000`;
                        charCount.className = count > 1000 ? 'text-red-500' : 'text-gray-500 dark:text-gray-400';
                    }
                    
                    // Enable/disable send button
                    if (sendButton) {
                        sendButton.disabled = count === 0 || count > 1000;
                    }
                });

                // Handle Enter key
                messageInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        if (this.value.trim() && this.value.length <= 1000) {
                            messageForm.submit();
                        }
                    }
                });
            }

            // Auto-scroll to bottom
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Form submission handling
            if (messageForm) {
                messageForm.addEventListener('submit', function(e) {
                    const message = messageInput.value.trim();
                    if (!message || message.length > 1000) {
                        e.preventDefault();
                        return;
                    }
                    
                    // Show sending state
                    sendButton.disabled = true;
                    sendButton.innerHTML = `
                        <span>Đang gửi...</span>
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    `;
                });
            }

            // Quick reply functionality
            window.insertQuickReply = function(text) {
                if (messageInput) {
                    messageInput.value = text;
                    messageInput.focus();
                    messageInput.dispatchEvent(new Event('input'));
                }
            };

            // Status change handling
            const statusSelect = document.querySelector('select');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    const conversationId = {{ $activeConversation->id ?? 'null' }};
                    if (conversationId) {
                        fetch(`/admin/chat-support/${conversationId}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                status: this.value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                showNotification('Đã cập nhật trạng thái', 'success');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Có lỗi xảy ra', 'error');
                        });
                    }
                });
            }

            // Notification function
            window.showNotification = function(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                    type === 'success' ? 'bg-green-500 text-white' :
                    type === 'error' ? 'bg-red-500 text-white' :
                    'bg-blue-500 text-white'
                }`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                // Animate in
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            };

            // Real-time updates (if using WebSocket or polling)
            // This is a placeholder for real-time functionality
            function initializeRealTime() {
                // WebSocket connection or polling logic here
                // Example with polling:
                /*
                setInterval(() => {
                    if ({{ $activeConversation->id ?? 'null' }}) {
                        checkForNewMessages();
                    }
                }, 5000);
                */
            }

            function checkForNewMessages() {
                const conversationId = {{ $activeConversation->id ?? 'null' }};
                if (!conversationId) return;

                fetch(`/admin/chat-support/${conversationId}/messages/latest`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            // Add new messages to the chat
                            data.messages.forEach(message => {
                                addMessageToChat(message);
                            });
                            
                            // Scroll to bottom
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    })
                    .catch(error => console.error('Error checking for new messages:', error));
            }

            function addMessageToChat(message) {
                // Create message element and append to chat
                // This would need to match the message HTML structure above
            }

            // Initialize real-time if needed
            // initializeRealTime();
        });

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-button');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-gray-100', 'shadow-sm');
                        btn.classList.add('text-gray-600', 'dark:text-gray-400');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-gray-100', 'shadow-sm');
                    this.classList.remove('text-gray-600', 'dark:text-gray-400');
                    
                    // Filter conversations based on status
                    const filter = this.dataset.filter;
                    filterConversations(filter);
                });
            });
        });

        function filterConversations(filter) {
            const conversations = document.querySelectorAll('.conversation-item');
            
            conversations.forEach(conversation => {
                const status = conversation.dataset.status;
                
                if (filter === 'all' || status === filter) {
                    conversation.style.display = 'block';
                } else {
                    conversation.style.display = 'none';
                }
            });
        }
    </script>

    {{-- Custom CSS for better styling --}}
    <style>
        .submenu-transition {
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
        }
        
        .conversation-item:hover {
            transform: translateX(2px);
            transition: transform 0.2s ease;
        }
        
        .message-bubble {
            animation: fadeInUp 0.3s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Custom scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }
        
        /* Dark mode scrollbar */
        .dark .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.5);
        }
        
        .dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.7);
        }
    </style>
</x-app-layout>


