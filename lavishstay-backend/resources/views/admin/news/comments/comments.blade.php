<x-app-layout>
    <div x-data="comments" class="px-4 sm:px-6 lg:px-8 py-8 max-w-9xl mx-auto">
       

        <!-- Tiêu đề và thống kê -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $news->title }}</h1>
            <div class="flex flex-wrap gap-4 items-center mt-2">
                <span class="text-base text-gray-600 dark:text-gray-400">
                    Tác giả: {{ $news->author->name ?? 'Không rõ' }}
                </span>
                <span class="text-base text-gray-600 dark:text-gray-400">
                    Tổng bình luận: <span class="font-semibold text-violet-600">{{ $news->comments()->count() }}</span>
                </span>
                <span class="text-base text-gray-600 dark:text-gray-400">
                    Lượt thích: {{ $news->getLikesCount() }}
                </span>
                
            </div>

        </div>

        <!-- Thông báo flash -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 animate-slide-in">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 animate-slide-in">
                {{ session('error') }}
            </div>
        @endif

        <!-- Nút lọc bình luận -->
        <div class="mb-4 flex gap-2">
            <button @click="filterType = 'all'; fetchComments()"
                :class="filterType === 'all' ? 'bg-violet-600 text-white' : 'bg-gray-200 text-gray-700'"
                class="px-4 py-2 rounded-lg font-semibold text-base hover:bg-violet-500 hover:text-white transition cursor-pointer">
                Tất cả
            </button>
            <button @click="filterType = 'latest'; fetchComments()"
                :class="filterType === 'latest' ? 'bg-violet-600 text-white' : 'bg-gray-200 text-gray-700'"
                class="px-4 py-2 rounded-lg font-semibold text-base hover:bg-violet-500 hover:text-white transition cursor-pointer">
                Mới nhất
            </button>
            <button @click="filterType = 'oldest'; fetchComments()"
                :class="filterType === 'oldest' ? 'bg-violet-600 text-white' : 'bg-gray-200 text-gray-700'"
                class="px-4 py-2 rounded-lg font-semibold text-base hover:bg-violet-500 hover:text-white transition cursor-pointer">
                Cũ nhất
            </button>
       <div class="flex-grow text-right">
         <a href="{{ route('admin.news.index') }}"
            class="inline-flex items-center mb-4 px-4 py-2 rounded-lg font-semibold text-base transition cursor-pointer
            bg-gray-900 text-white hover:bg-gray-700
            dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Quay lại
        </a>
    </div>
        </div>

        <!-- Ô to chứa bình luận, có thanh cuộn riêng -->
        <div class="rounded-xl bg-white dark:bg-gray-900 shadow p-4" style="max-height: 500px; overflow-y: auto;">
            <div class="space-y-4" x-ref="commentsContainer" id="comments-list">
                @if ($comments->count())
                    @foreach ($comments as $comment)
                        @include('admin.news.comments._comment_item', [
                            'comment' => $comment,
                            'level' => 0,
                        ])
                    @endforeach
                @else
                    <div class="text-center py-4 text-gray-500 dark:text-gray-400 text-base">Không có bình luận nào.
                    </div>
                @endif
            </div>
        </div>

        <!-- Form thêm bình luận mới -->
        <div class="mb-4 flex flex-col gap-2">
            <textarea x-model="newCommentContent" placeholder="Nhập bình luận của bạn..."
                class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-violet-500"
                rows="3"></textarea>
            <div class="flex justify-end">
                <button @click="addComment"
                    class="px-5 py-2 rounded-lg text-base font-semibold transition cursor-pointer
                bg-gray-900 text-white hover:bg-gray-700
                dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
                    :disabled="!newCommentContent">
                    Thêm bình luận
                </button>
            </div>
        </div>

        <!-- Modal thêm phản hồi -->
        <div x-show="showReplyModal" class="fixed inset-0 bg-black opacity-80 flex items-center justify-center z-50"
            x-cloak>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Phản hồi bình luận</h2>
                <form @submit.prevent="submitReply">
                    <textarea x-model="replyContent" placeholder="Nhập phản hồi của bạn..."
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-violet-500"
                        rows="4"></textarea>
                    <div class="mt-4 flex justify-end gap-2">
                        <button @click="showReplyModal = false"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-violet-600 text-white rounded-md hover:bg-violet-700"
                            :disabled="!replyContent">Gửi</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal xóa bình luận -->
        <div x-show="showDeleteModal" class="fixed inset-0 bg-black opacity-80 flex items-center justify-center z-50"
            x-cloak>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Xác nhận gỡ bình luận</h2>
                <p class="text-gray-700 dark:text-gray-300">Bạn có chắc muốn gỡ bình luận này?</p>
                <div class="mt-4 flex justify-end gap-2">
                    <button @click="showDeleteModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Hủy</button>
                    <button @click="deleteComment"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Gỡ</button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('comments', () => ({
                    // Biến trạng thái cho modal thêm phản hồi
                    showReplyModal: false,
                    // Biến trạng thái cho modal xóa bình luận
                    showDeleteModal: false,
                    // Lưu ID của bình luận được chọn để phản hồi hoặc xóa
                    selectedCommentId: null,
                    // Nội dung của phản hồi mới
                    replyContent: '',
                    // Nội dung của bình luận cha mới
                    newCommentContent: '',
                    // ID người dùng hiện tại (nếu đăng nhập)
                    currentUserId: {{ auth()->id() ?? null }},
                    // Loại lọc bình luận: all, latest, oldest
                    filterType: 'all',
                    // Trạng thái "thả tym" của các bình luận
                    likedComments: {},
                    // Số lượt "tym" của các bình luận
                    commentsLikes: {},
                    // Trạng thái mở rộng các phản hồi của bình luận
                    expandedComments: {},
                    // Hàm khởi tạo, tự động gọi khi component được tải
                    init() {
                        this.fetchComments();
                    },
                    // Hàm lấy danh sách bình luận từ server
                    fetchComments() {
                        let url = '{{ route('admin.news.comments.fetch', ['news' => $news->id]) }}';
                        // Thêm query param để sắp xếp theo thời gian
                        if (this.filterType === 'latest') {
                            url += '?sort=latest';
                        } else if (this.filterType === 'oldest') {
                            url += '?sort=oldest';
                        }
                        fetch(url, {
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                // Cập nhật HTML của container bình luận
                                this.$refs.commentsContainer.innerHTML = data.html;
                                // Khởi tạo lại các binding của Alpine.js trong container
                                Alpine.initTree(this.$refs.commentsContainer);
                            });
                    },
                    // Hàm thêm bình luận cha mới
                    addComment() {
                        if (!this.newCommentContent) return;
                        fetch('{{ route('admin.news.comments.store', ['news' => $news->id]) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    news_id: {{ $news->id }},
                                    content: this.newCommentContent
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Cập nhật danh sách bình luận
                                    this.$refs.commentsContainer.innerHTML = data.html;
                                    Alpine.initTree(this.$refs.commentsContainer);
                                    // Xóa nội dung form sau khi gửi
                                    this.newCommentContent = '';
                                    // Hiển thị thông báo thành công
                                    this.showToast(data.message, 'success');
                                } else {
                                    // Hiển thị thông báo lỗi
                                    this.showToast(data.message, 'error');
                                }
                            });
                    },
                    // Hàm gửi phản hồi cho bình luận
                    submitReply() {
                        fetch('{{ route('admin.news.comments.store', ['news' => $news->id]) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    news_id: {{ $news->id }},
                                    parent_id: this.selectedCommentId,
                                    content: this.replyContent
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Cập nhật danh sách bình luận
                                    this.$refs.commentsContainer.innerHTML = data.html;
                                    Alpine.initTree(this.$refs.commentsContainer);
                                    // Xóa nội dung phản hồi và đóng modal
                                    this.replyContent = '';
                                    this.showReplyModal = false;
                                    this.showToast(data.message, 'success');
                                } else {
                                    this.showToast(data.message, 'error');
                                }
                            });
                    },
                    // Hàm xóa bình luận
                    deleteComment() {
                        fetch('{{ route('admin.news.comments.delete', ['comment' => ':id']) }}'.replace(
                                ':id', this.selectedCommentId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Sau khi xoá, gọi lại fetchComments với filterType hiện tại
                                    this.showToast(data.message, 'success');
                                    this.showDeleteModal = false;
                                    this.fetchComments(); // <-- Gọi lại hàm này để giữ đúng filter
                                } else {
                                    this.showToast(data.message, 'error');
                                }
                            });
                    },
                    // Hàm thả/bỏ tym bình luận
                    likeComment(commentId) {
                        fetch('{{ route('admin.news.comments.like', ['comment' => ':id']) }}'.replace(
                                ':id', commentId), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Cập nhật trạng thái tym và số lượt tym
                                    this.likedComments[commentId] = data.is_liked;
                                    this.commentsLikes[commentId] = data.likes;
                                    this.showToast(data.message, 'success');
                                } else {
                                    this.showToast(data.message, 'error');
                                }
                            });
                    },
                    // Hàm mở/đóng danh sách phản hồi
                    toggleReplies(commentId) {
                        this.expandedComments[commentId] = !this.expandedComments[commentId];
                    },
                    // Hàm mở modal phản hồi bình luận
                    openReplyModal(commentId) {
                        // Lưu ID của bình luận được chọn
                        this.selectedCommentId = commentId;
                        // Hiển thị modal phản hồi
                        this.showReplyModal = true;
                    },
                    // Hàm mở modal xóa bình luận
                    openDeleteModal(commentId) {
                        // Lưu ID của bình luận được chọn
                        this.selectedCommentId = commentId;
                        // Hiển thị modal xóa
                        this.showDeleteModal = true;
                    },
                    // Hàm hiển thị thông báo dạng toast
                    showToast(message, type) {
                        const toast = document.createElement('div');
                        toast.className =
                            `fixed bottom-4 right-4 p-4 rounded-lg shadow-md text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                        toast.textContent = message;
                        document.body.appendChild(toast);
                        setTimeout(() => {
                            toast.remove();
                        }, 3000);
                    }
                }));
            });
        </script>

        <style>
            .animate-slide-in {
                animation: slideIn 0.3s ease-in-out;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            [x-cloak] {
                display: none;
            }
        </style>
    </div>
</x-app-layout>
