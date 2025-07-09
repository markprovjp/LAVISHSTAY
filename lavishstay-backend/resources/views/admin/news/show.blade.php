<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Tiêu đề và nút hành động -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.news.index') }}" class="text-sm text-blue-600 hover:underline">← Trở về danh sách bài viết</a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $news->title }}</h1>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">ID: {{ $news->id }}</div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.news.edit', $news->id) }}" class="inline-flex items-center text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    ✏️ Sửa bài viết
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cột trái: Nội dung -->
            <div class="lg:col-span-2">
                <!-- Ảnh đại diện -->
                @if ($news->image_url)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $news->image_url) }}" alt="Ảnh đại diện" class="w-full h-auto rounded-md shadow">
                    </div>
                @endif

                <!-- Nội dung bài viết -->
                <div class="prose max-w-none dark:prose-invert">
                    {!! $news->content !!}
                </div>
            </div>

            <!-- Cột phải: thông tin phụ -->
            <div class="space-y-6">
                <!-- Thông tin chung -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-md shadow">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Thông tin bài viết</h2>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                        @if ($news->category)
                            <li><strong>Danh mục:</strong> {{ $news->category->name }}</li>
                        @endif
                        <li><strong>Tác giả:</strong> {{ $news->author->name ?? 'Không rõ' }}</li>
                        <li><strong>Ngày đăng:</strong> {{ $news->publish_date ? $news->publish_date->format('d/m/Y') : $news->created_at->format('d/m/Y') }}</li>
                        <li><strong>Trạng thái:</strong> 
                            @if ($news->status)
                                <span class="text-green-600 font-medium">Đang hiển thị</span>
                            @else
                                <span class="text-gray-500 font-medium">Đã ẩn</span>
                            @endif
                        </li>
                    </ul>
                </div>

                <!-- Thông tin SEO -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-md shadow">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Thông tin SEO</h2>
                    <p><strong>Meta Description:</strong> {{ $news->meta_description ?: '—' }}</p>
                    <p><strong>Meta Keywords:</strong> {{ $news->meta_keywords ?: '—' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>