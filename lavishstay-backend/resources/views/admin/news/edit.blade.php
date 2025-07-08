<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 py-8 max-w-9xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Chỉnh sửa bài viết</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Cập nhật nội dung và SEO cho bài viết này</p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<form method="POST" action="{{ route('admin.news.update', $news) }}">
    @csrf
    @method('PUT')
    <!-- Các input -->

        <!-- Nội dung chính -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề bài viết</label>
                <input type="text" name="title" value="{{ old('title', $news->title) }}" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug URL</label>
                <input type="text" name="slug" value="{{ old('slug', $news->slug) }}" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $news->meta_title) }}" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô tả SEO</label>
                <textarea name="meta_description" rows="3" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">{{ old('meta_description', $news->meta_description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ khoá SEO</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $news->meta_keywords) }}" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Canonical URL</label>
                <input type="text" name="canonical_url" value="{{ old('canonical_url', $news->canonical_url) }}" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung bài viết</label>
                <textarea name="content" id="ckeditor" rows="10" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">{{ old('content', $news->content) }}</textarea>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chuyên mục</label>
                @foreach ($categories as $cat)
                    <label class="inline-flex items-center mt-1">
                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="form-checkbox h-5 w-5 text-blue-600" {{ in_array($cat->id, old('categories', $selectedCategories)) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $cat->name }}</span>
                    </label><br>
                @endforeach
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID ảnh đại diện</label>
                <input type="number" name="thumbnail_id" value="{{ old('thumbnail_id', $news->thumbnail_id) }}" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày đăng</label>
                <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="status" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('status', $news->status) ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Công khai bài viết</span>
                </label>
            </div>
            <div class="text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md shadow-md transition">
                    <i class="fas fa-save mr-2"></i> Cập nhật bài viết
                </button>
            </div>
        </div>
    </form>
</div>

<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('ckeditor', {
        filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form',
        contentsCss: [
            '{{ asset('ckeditor/style.css') }}',
            'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css'
        ],
        skin: 'moono-lisa'
    });
</script>
</x-app-layout>