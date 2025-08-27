<div class="rounded-xl bg-white dark:bg-gray-800 shadow p-4 flex flex-col gap-2"
    style="margin-left: {{ $level * 32 }}px; font-size:1.1rem;" id="comment-{{ $comment->id }}">
    <div class="flex items-center gap-2">
        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $comment->user->name ?? 'Khách' }}</span>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->format('d/m/Y H:i:s') }}</span>
    </div>
    <div class="text-gray-800 dark:text-gray-200 font-normal">{{ $comment->content }}</div>
    <div class="flex gap-3 items-center mt-1">
        <!-- Nút thả tym -->
        <button @click="likeComment({{ $comment->id }})"
            :class="{
                'text-violet-600': likedComments && likedComments[{{ $comment->id }}],
                'text-gray-400': !likedComments || !likedComments[{{ $comment->id }}],
                'hover:text-violet-500': true,
                'transition': true,
                'cursor-pointer': true
            }"
            :aria-label="'Thả tym'" type="button">
            <svg class="w-6 h-6 inline align-middle transition"
                :fill="likedComments && likedComments[{{ $comment->id }}] ? 'currentColor' : 'none'"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="like-count ml-1 align-middle select-none font-semibold"
                x-text="commentsLikes && commentsLikes[{{ $comment->id }}] !== undefined ? commentsLikes[{{ $comment->id }}] : {{ (int) ($comment->likes ?? 0) }}"></span>
        </button>
        <!-- Nút phản hồi -->
        <button @click="openReplyModal({{ $comment->id }})"
            class="text-violet-600 hover:text-violet-700 transition cursor-pointer font-semibold" type="button">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h7V3m0 0l11 11-11 11V14H3v-4z" />
            </svg>
            Phản hồi
        </button>
        <!-- Nút gỡ -->
        @if ($comment->replies && $comment->replies->count())
            <button @click="showToast('Không thể xóa bình luận cha do còn bình luận con!', 'error')"
                class="text-red-500 hover:text-red-600 transition cursor-pointer font-semibold" type="button">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Gỡ
            </button>
        @else
            <button @click="openDeleteModal({{ $comment->id }})"
                class="text-red-500 hover:text-red-600 transition cursor-pointer font-semibold" type="button">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Gỡ
            </button>
        @endif
        <!-- Nút xổ xuống nếu có replies -->
        @if ($comment->replies && $comment->replies->count())
            <button @click="toggleReplies({{ $comment->id }})" :aria-label="'Xem phản hồi'" type="button"
                class="ml-2 text-violet-500 hover:text-violet-700 transition cursor-pointer"
                :class="{ 'rotate-180': expandedComments[{{ $comment->id }}] }" style="transition: transform 0.2s;">
                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        @endif
    </div>
    <!-- Hiển thị replies nếu có -->
    @if ($comment->replies && $comment->replies->count())
        <div class="mt-2" :id="'replies-{{ $comment->id }}'" x-show="expandedComments[{{ $comment->id }}]"
            x-transition>
            @foreach ($comment->replies as $reply)
                @include('admin.news.comments._comment_item', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
