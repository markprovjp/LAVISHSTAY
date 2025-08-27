<x-app-layout>
    <div x-data="mediaHandler()" class="px-4 sm:px-6 lg:px-8 py-8 max-w-9xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Chỉnh sửa bài viết</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Cập nhật bài viết tin tức cho website khách sạn</p>
        </div>

        <!-- Flash messages -->
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

        <!-- Thông báo tải ảnh -->
        <div x-show="uploadSuccessMessage" x-cloak
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 animate-slide-in">
            <p x-text="uploadSuccessMessage"></p>
        </div>

        <form method="POST" action="{{ route('admin.news.update', $news->id) }}" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            @method('PUT')

            <!-- MAIN CONTENT -->
            <section class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md space-y-6">
                <!-- Meta Title -->
                <div x-data="{ metaTitle: '{{ old('meta_title', $news->meta_title) }}', maxMetaTitle: 60, hasError: {{ $errors->has('meta_title') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề bài viết (Meta Title)</label>
                    <input type="text" name="meta_title" x-model="metaTitle"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        required placeholder="Nhập tiêu đề bài viết (50-60 ký tự)">
                    <p class="text-xs mt-1" x-show="!hasError"
                        x-text="metaTitle.length > maxMetaTitle ? `Google cắt bớt sau 60 ký tự (Đã nhập ${metaTitle.length})` : `Còn ${maxMetaTitle - metaTitle.length} ký tự`"
                        :class="metaTitle.length > maxMetaTitle ? 'text-red-500' : 'text-gray-500'"></p>
                    @error('meta_title')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Description -->
                <div x-data="{ metaDescription: '{{ old('meta_description', $news->meta_description) }}', maxMetaDescription: 160, hasError: {{ $errors->has('meta_description') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô tả ngắn (Meta Description)</label>
                    <textarea name="meta_description" rows="3" x-model="metaDescription"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        placeholder="Nhập mô tả ngắn (dưới 160 ký tự)"></textarea>
                    <p class="text-xs mt-1" x-show="!hasError"
                        x-text="metaDescription.length > maxMetaDescription ? `Google cắt bớt sau 160 ký tự (Đã nhập ${metaDescription.length})` : `Còn ${maxMetaDescription - metaDescription.length} ký tự`"
                        :class="metaDescription.length > maxMetaDescription ? 'text-red-500' : 'text-gray-500'"></p>
                    @error('meta_description')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Keywords -->
                <div x-data="{ metaKeywords: '{{ old('meta_keywords', $news->meta_keywords) }}', maxMetaKeywords: 100, hasError: {{ $errors->has('meta_keywords') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ khóa SEO (Meta Keywords)</label>
                    <input type="text" name="meta_keywords" x-model="metaKeywords"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        placeholder="Nhập từ khóa, cách nhau bởi dấu phẩy">
                    <p class="text-xs mt-1" x-show="!hasError"
                        x-text="metaKeywords.length > maxMetaKeywords ? `Google cắt bớt sau 100 ký tự (Đã nhập ${metaKeywords.length})` : `Còn ${maxMetaKeywords - metaKeywords.length} ký tự`"
                        :class="metaKeywords.length > maxMetaKeywords ? 'text-red-500' : 'text-gray-500'"></p>
                    @error('meta_keywords')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Summary -->
                <div x-data="{ summary: '{{ old('summary', $news->summary) }}', maxSummary: 500, hasError: {{ $errors->has('summary') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tóm tắt bài viết</label>
                    <textarea name="summary" rows="3" x-model="summary"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        placeholder="Nhập tóm tắt ngắn gọn (dưới 500 ký tự)"></textarea>
                    <p class="text-xs mt-1" x-show="!hasError"
                        x-text="summary.length > maxSummary ? `Tóm tắt vượt quá giới hạn (Đã nhập ${summary.length})` : `Còn ${maxSummary - summary.length} ký tự`"
                        :class="summary.length > maxSummary ? 'text-red-500' : 'text-gray-500'"></p>
                    @error('summary')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div x-data="{ tags: '{{ old('tags', $news->tags) }}', hasError: {{ $errors->has('tags') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tags (phân cách bởi dấu phẩy)</label>
                    <input type="text" name="tags" x-model="tags"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        placeholder="Nhập tags, ví dụ: resort, luxury, travel">
                    <!-- - Dòng trên: Hiển thị tags dưới dạng chuỗi thô từ $news->tags.
                         + Lợi ích: Đồng nhất với cột tags (TEXT) trong bảng news, hiển thị đúng giá trị như "Tôi là Phước,Phước ơi,Tôi đây,Tin hot".
                         + Lý do: Bỏ json_decode vì tags không còn là JSON, sử dụng trực tiếp chuỗi thô. -->
                    @error('tags')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div x-data="{ hasError: {{ $errors->has('content') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung bài viết</label>
                    <textarea name="content" id="ckeditor" rows="10"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        x-on:input="hasError = false">{{ old('content', $news->content) }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <!-- SIDEBAR -->
            <aside class="space-y-6">
                <!-- Category -->
                <div x-data="{ categoryId: '{{ old('category_id', $news->category_id) }}', hasError: {{ $errors->has('category_id') ? 'true' : 'false' }} }">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Danh mục bài viết</label>
                    <select name="category_id" id="category_id"
                        class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        x-model="categoryId" x-on:change="hasError = false">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $news->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thumbnail -->
                <div x-data="{ hasThumbnailError: {{ $errors->has('thumbnail') || $errors->has('thumbnail_id') ? 'true' : 'false' }} }" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ảnh đại diện bài viết</label>
                    <img id="thumbnail_preview"
                        src="{{ old('thumbnail_id', $news->thumbnail_id) ? optional($mediaFiles->firstWhere('id', old('thumbnail_id', $news->thumbnail_id)))->filepath : asset('images/placeholder.png') }}"
                        class="w-40 h-24 object-cover border rounded-lg mt-2"
                        x-show="hasSelectedThumbnail || {{ old('thumbnail_id', $news->thumbnail_id) ? 'true' : 'false' }}">
                    <div class="flex gap-2 mt-2">
                        <button type="button" @click="$refs.uploadInput.click()"
                            class="px-4 py-2 rounded-md text-sm bg-purple-600 text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200 cursor-pointer">
                            Tải ảnh mới
                        </button>
                        <input type="file" accept="image/*" x-ref="uploadInput" class="hidden"
                            @change="uploadFiles($event); hasThumbnailError = false">
                    </div>
                    <input type="hidden" id="thumbnail_id" name="thumbnail_id" value="{{ old('thumbnail_id', $news->thumbnail_id) }}"
                        x-on:change="hasThumbnailError = false">
                    @error('thumbnail')
                        <p class="text-xs text-red-500 mt-1" x-show="hasThumbnailError">{{ $message }}</p>
                    @enderror
                    @error('thumbnail_id')
                        <p class="text-xs text-red-500 mt-1" x-show="hasThumbnailError">{{ $message }}</p>
                    @enderror
                    <div x-show="{{ old('thumbnail_id', $news->thumbnail_id) ? 'true' : 'false' }}" class="mt-2">
                        <button type="button" @click="openMediaLibrary()"
                            class="px-3 py-1 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700 transition-all duration-200 cursor-pointer">
                            Chi tiết hình ảnh
                        </button>
                    </div>
                </div>

                <!-- Is Featured -->
                <div x-data="{ hasError: {{ $errors->has('is_featured') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vị trí hiển thị</label>
                    <input type="checkbox" name="is_featured" value="1"
                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                        {{ old('is_featured', $news->is_featured) == 1 ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Nổi bật</span>
                    <!-- - Dòng input: Checkbox gửi giá trị 1 nếu được chọn, không gửi gì nếu không chọn.
                         + Lợi ích: Đảm bảo is_featured được gửi đúng (1 hoặc không có), tránh xung đột với Alpine.js.
                         + Lý do: Loại bỏ x-model để HTML quản lý trực tiếp, đồng nhất với file create. -->
                    @error('is_featured')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Canonical URL -->
                <div x-data="{ canonicalUrl: '{{ old('canonical_url', $news->canonical_url) }}', hasError: {{ $errors->has('canonical_url') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Canonical URL</label>
                    <input type="url" name="canonical_url" x-model="canonicalUrl"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        placeholder="Nhập URL chuẩn (tùy chọn)">
                    @error('canonical_url')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Schema JSON -->
                <div x-data="{ schemaJson: '{{ old('schema_json', $news->schema_json) }}', hasError: {{ $errors->has('schema_json') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Schema JSON (SEO)</label>
                    <textarea name="schema_json" rows="5" x-model="schemaJson"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        placeholder="Nhập JSON schema (tùy chọn)"></textarea>
                    @error('schema_json')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Publish Date -->
                <div x-data="{ publishDate: '{{ old('publish_date', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}', hasError: {{ $errors->has('publish_date') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày đăng bài</label>
                    <input type="datetime-local" name="publish_date" x-model="publishDate"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        x-on:input="hasError = false">
                    @error('publish_date')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div x-data="{ status: '{{ old('status', $news->status) }}', hasError: {{ $errors->has('status') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái bài viết</label>
                    <select name="status"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                        x-model="status" x-on:change="hasError = false">
                        <option value="1" {{ old('status', $news->status) == 1 ? 'selected' : '' }}>Công khai</option>
                        <option value="0" {{ old('status', $news->status) == 0 ? 'selected' : '' }}>Bản nháp</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="text-right">
                    <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-2 rounded-md shadow-md transition-all duration-200 cursor-pointer">
                        <i class="fas fa-save mr-2"></i> Cập nhật bài viết
                    </button>
                </div>
            </aside>
        </form>

        <!-- MEDIA POPUP -->
        <div x-show="showMedia" x-cloak x-transition @keydown.window.escape="closeMediaLibrary"
            @click.self="closeMediaLibrary"
            class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-gray-900 w-full max-w-4xl rounded-xl shadow-2xl flex flex-col overflow-hidden">
                <div class="flex justify-between items-center p-6">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Quản lý ảnh</span>
                    <button @click="closeMediaLibrary"
                        class="bg-gray-200 dark:bg-gray-700 rounded-full w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 text-xl font-bold transition-all duration-200 cursor-pointer"
                        aria-label="Đóng popup">×</button>
                </div>
                <div class="flex flex-col sm:flex-row w-full h-[55vh] overflow-auto">
                    <div class="flex-1 px-6 pb-6 space-y-4 flex flex-col" x-show="selectedMedia">
                        <img :src="selectedMedia?.filepath || '{{ asset('images/placeholder.png') }}'"
                            alt="Media preview" class="w-full h-auto rounded-lg shadow-md object-contain max-h-96">
                        <div class="space-y-2 flex-1">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">Tên file:</span>
                                <span x-text="selectedMedia?.filename || 'Chưa có file'"></span>
                            </p>
                            <p class="text-sm text-gray-500 break-words">
                                <span class="font-semibold">Đường dẫn:</span>
                                <a :href="selectedMedia?.filepath || '#'" class="text-purple-600 hover:underline"
                                    target="_blank" x-text="selectedMedia?.filepath || 'Chưa có đường dẫn'"></a>
                            </p>
                            <p class="text-sm text-gray-500">
                                <span class="font-semibold">Kích thước:</span>
                                <span
                                    x-text="selectedMedia?.width && selectedMedia?.height ? selectedMedia.width + ' x ' + selectedMedia.height + ' px' : 'Chưa có thông tin'"></span>
                            </p>
                            <p class="text-sm text-gray-500">
                                <span class="font-semibold">Dung lượng:</span>
                                <span
                                    x-text="selectedMedia?.size ? formatSize(selectedMedia.size) : 'Chưa có thông tin'"></span>
                            </p>
                        </div>
                    </div>
                    <div class="flex-1 p-6 space-y-4 bg-gray-50 dark:bg-gray-800 flex flex-col">
                        <div class="space-y-4 flex-1">
                            <div>
                                <label for="altText"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Văn bản
                                    thay thế (Alt Text):</label>
                                <input id="altText" type="text" x-model="altText"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label for="titleText"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu
                                    đề:</label>
                                <input id="titleText" type="text" x-model="titleText"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <button x-show="selectedMedia" @click="deleteMedia(selectedMedia?.id)"
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-md text-sm font-medium shadow-md transition-all duration-200 cursor-pointer">
                                Xóa ảnh
                            </button>
                            <button x-show="selectedMedia"
                                @click="updateMediaMeta(selectedMedia?.id, altText, titleText)"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-md text-sm font-medium shadow-md transition-all duration-200 cursor-pointer">
                                Cập nhật thông tin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function mediaHandler() {
                return {
                    showMedia: false,
                    mediaList: @json($mediaJson),
                    selectedMedia: null,
                    hasSelectedThumbnail: {{ old('thumbnail_id', $news->thumbnail_id) ? 'true' : 'false' }},
                    uploadSuccessMessage: '',
                    altText: '',
                    titleText: '',
                    metaTitle: '{{ old('meta_title', $news->meta_title) }}',
                    slug: '{{ old('slug', $news->slug) }}',
                    metaDescription: '{{ old('meta_description', $news->meta_description) }}',
                    metaKeywords: '{{ old('meta_keywords', $news->meta_keywords) }}',
                    summary: '{{ old('summary', $news->summary) }}',
                    tags: '{{ old('tags', $news->tags) }}',
                    canonicalUrl: '{{ old('canonical_url', $news->canonical_url) }}',
                    schemaJson: '{{ old('schema_json', $news->schema_json) }}',
                    maxMetaTitle: 60,
                    maxSlug: 70,
                    maxMetaDescription: 160,
                    maxMetaKeywords: 100,
                    maxSummary: 500,

                    init() {
                        const thumbnailId = document.getElementById('thumbnail_id').value;
                        if (thumbnailId) {
                            const media = this.mediaList.find(item => item.id == thumbnailId);
                            if (media) {
                                this.selectedMedia = media;
                                this.altText = media.alt_text || '';
                                this.titleText = media.title || '';
                                this.hasSelectedThumbnail = true;
                            } else {
                                this.resetThumbnail();
                            }
                        }
                    },

                    resetThumbnail() {
                        this.selectedMedia = null;
                        this.altText = '';
                        this.titleText = '';
                        this.hasSelectedThumbnail = false;
                        document.getElementById('thumbnail_id').value = '';
                        document.getElementById('thumbnail_preview').src = '{{ asset('images/placeholder.png') }}';
                    },

                    formatSize(bytes) {
                        if (bytes < 1024) return bytes + ' B';
                        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                        return (bytes / 1048576).toFixed(1) + ' MB';
                    },

                    openMediaLibrary() {
                        if (this.selectedMedia) {
                            this.showMedia = true;
                            this.altText = this.selectedMedia.alt_text || '';
                            this.titleText = this.selectedMedia.title || '';
                        } else {
                            this.uploadSuccessMessage = 'Vui lòng chọn hoặc tải ảnh trước!';
                            setTimeout(() => this.uploadSuccessMessage = '', 3000);
                        }
                    },

                    closeMediaLibrary() {
                        this.showMedia = false;
                    },

                    uploadFiles(event) {
                        const file = event.target.files[0];
                        if (!file) return this.showError('Vui lòng chọn một file ảnh!');

                        const formData = new FormData();
                        formData.append('file', file);

                        fetch('/admin/news/media/upload', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: formData
                            })
                            .then(res => res.ok ? res.json() : Promise.reject('Failed to upload image'))
                            .then(data => {
                                if (data.success && data.files?.length) {
                                    const newMedia = data.files[0];
                                    this.mediaList.push(newMedia);
                                    this.selectedMedia = newMedia;
                                    this.altText = newMedia.alt_text || '';
                                    this.titleText = newMedia.title || '';
                                    this.hasSelectedThumbnail = true;
                                    document.getElementById('thumbnail_preview').src = newMedia.filepath;
                                    document.getElementById('thumbnail_id').value = newMedia.id;
                                    this.uploadSuccessMessage = 'Ảnh đã tải lên thành công!';
                                    setTimeout(() => this.uploadSuccessMessage = '', 3000);
                                } else {
                                    this.showError('Lỗi khi tải ảnh!');
                                }
                            })
                            .catch(() => this.showError('Lỗi hệ thống khi tải ảnh!'));
                    },

                    updateMediaMeta(mediaId, altText, titleText) {
                        if (!mediaId) return this.showError('Không có ảnh được chọn!');
                        if (!altText) return this.showError('Vui lòng nhập Alt Text!');

                        fetch(`/admin/media/meta/${mediaId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    alt_text: altText,
                                    title: titleText
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    this.uploadSuccessMessage = 'Cập nhật thông tin ảnh thành công!';
                                    setTimeout(() => this.uploadSuccessMessage = '', 3000);
                                    const mediaIndex = this.mediaList.findIndex(item => item.id == mediaId);
                                    if (mediaIndex !== -1) {
                                        this.mediaList[mediaIndex] = data.media;
                                        this.selectedMedia = data.media;
                                        this.altText = data.media.alt_text || '';
                                        this.titleText = data.media.title || '';
                                        document.getElementById('thumbnail_preview').src = data.media.filepath;
                                    }
                                } else {
                                    this.showError(data.message || 'Lỗi khi cập nhật thông tin ảnh!');
                                }
                            })
                            .catch(() => this.showError('Lỗi hệ thống khi cập nhật ảnh!'));
                    },

                    deleteMedia(mediaId) {
                        if (!mediaId) return this.showError('Không có ảnh được chọn để xóa!');
                        if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) return;

                        fetch(`/admin/media/${mediaId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            })
                            .then(res => res.ok ? res.json() : Promise.reject('Failed to delete image'))
                            .then(data => {
                                if (data.success) {
                                    this.uploadSuccessMessage = 'Ảnh đã được xóa thành công!';
                                    this.mediaList = this.mediaList.filter(item => item.id != mediaId);
                                    this.resetThumbnail();
                                    this.closeMediaLibrary();
                                    setTimeout(() => this.uploadSuccessMessage = '', 3000);
                                } else {
                                    this.showError(data.message || 'Lỗi khi xóa ảnh!');
                                }
                            })
                            .catch(() => this.showError('Lỗi hệ thống khi xóa ảnh!'));
                    },

                    showError(message) {
                        this.uploadSuccessMessage = message;
                        setTimeout(() => this.uploadSuccessMessage = '', 3000);
                    }
                }
            }

            // Xử lý ảnh từ CKEditor
            document.addEventListener('imageUploaded', function(event) {
                const filepath = event.detail.filepath;
                Alpine.store('mediaHandler')?.set('uploadedImageUrl', filepath);
            });
        </script>

        <!-- Tailwind CSS Animation -->
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
        </style>

        <script src="{{ asset('ckeditor1/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('ckeditor', {
                filebrowserUploadUrl: "{{ route('admin.news.create.ckeditor') }}?CKEditorFuncNum=1&_token={{ csrf_token() }}&news_id={{ $news->id }}",
                filebrowserUploadMethod: 'form',
                extraPlugins: 'image,uploadimage',
                height: 400,
                skin: 'moono-lisa',
                removeButtons: 'PasteFromWord',
                contentsCss: ['{{ asset('ckeditor1/style.css') }}', '{{ asset('ckeditor1/contents.css') }}'],
                allowedContent: true
            });
        </script>
    </div>
</x-app-layout>