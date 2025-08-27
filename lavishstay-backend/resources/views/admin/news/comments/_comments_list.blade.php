@if ($comments->count())
    @foreach ($comments as $comment)
        @include('admin.news.comments._comment_item', ['comment' => $comment, 'level' => 0])
    @endforeach
@else
    <div class="text-center py-4 text-gray-500 dark:text-gray-400 text-base">Không có bình luận nào.</div>
@endif