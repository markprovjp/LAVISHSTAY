<x-app-layout>
    <div x-data="mediaHandler()" class="px-4 sm:px-6 lg:px-8 py-8 max-w-9xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Tạo bài viết mới</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Đăng bài viết tin tức cho website khách sạn</p>
        </div>

        <!-- Flash messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Thông báo tải ảnh thành công -->
        <div x-show="uploadSuccessMessage" x-cloak
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <p x-text="uploadSuccessMessage"></p>
        </div>

        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <!-- MAIN CONTENT -->
            <section class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow space-y-6">
                <!-- Meta Title -->
                <div x-data="{ metaTitle: '{{ old('meta_title') }}', maxMetaTitle: 60, hasError: {{ $errors->has('meta_title') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề bài viết
                        (Meta Title)</label>
                    <input type="text" name="meta_title" x-model="metaTitle"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required placeholder="Nhập tiêu đề bài viết, tối ưu SEO (50-60 ký tự)"
                        x-on:input="metaTitle = $event.target.value; hasError = false">
                    <p class="text-xs mt-1" x-show="!hasError"
                        x-text="metaTitle.length > maxMetaTitle ? `Google sẽ lấy 60 ký tự đầu tiên (Đã nhập ${metaTitle.length})` : `Còn ${maxMetaTitle - metaTitle.length} ký tự để tối ưu SEO`"
                        :class="{
                            'text-red-500': metaTitle.length > maxMetaTitle,
                            'text-gray-500': metaTitle.length <= maxMetaTitle
                        }">
                    </p>
                    @error('meta_title')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Description -->
                <div x-data="{ metaDescription: '{{ old('meta_description') }}', maxMetaDescription: 160, hasError: {{ $errors->has('meta_description') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô tả ngắn (Meta
                        Description)</label>
                    <textarea name="meta_description" rows="3" x-model="metaDescription"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nhập mô tả ngắn, tối ưu SEO (dưới 160 ký tự)"
                        x-on:input="metaDescription = $event.target.value; hasError = false"></textarea>
                    <p class="text-xs mt-1" x-show="!hasError"
                        x-text="metaDescription.length > maxMetaDescription ? `Google sẽ lấy 160 ký tự đầu tiên (Đã nhập ${metaDescription.length})` : `Còn ${maxMetaDescription - metaDescription.length} ký tự để tối ưu SEO`"
                        :class="{
                            'text-red-500': metaDescription.length > maxMetaDescription,
                            'text-gray-500': metaDescription.length <= maxMetaDescription
                        }">
                    </p>
                    @error('meta_description')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Keywords -->
                <div x-data="{ metaKeywords: '{{ old('meta_keywords') }}', maxMetaKeywords: 100, hasError: {{ $errors->has('meta_keywords') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ khóa SEO (Meta
                        Keywords)</label>
                    <input type="text" name="meta_keywords" x-model="metaKeywords"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nhập từ khóa SEO, ngăn cách bởi dấu phẩy"
                        x-on:input="metaKeywords = $event.target.value; hasError = false">
                    <p class="text-xs mt-1" x-show="!hasError"
                        x-text="metaKeywords.length > maxMetaKeywords ? `Google sẽ lấy 100 ký tự đầu tiên (Đã nhập ${metaKeywords.length})` : `Còn ${maxMetaKeywords - metaKeywords.length} ký tự để tối ưu SEO`"
                        :class="{
                            'text-red-500': metaKeywords.length > maxMetaKeywords,
                            'text-gray-500': metaKeywords.length <= maxMetaKeywords
                        }">
                    </p>
                    @error('meta_keywords')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nội dung bài viết -->
                <div x-data="{ hasError: {{ $errors->has('content') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung bài
                        viết</label>
                    <textarea name="content" id="ckeditor" rows="10"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-on:input="hasError = false">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <!-- SIDEBAR -->
            <aside class="space-y-6">
                <!-- Category -->
                <div x-data="{ categoryId: '{{ old('category_id') }}', hasError: {{ $errors->has('category_id') ? 'true' : 'false' }} }">
                    <label for="category_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Danh mục bài
                        viết</label>
                    <select name="category_id" id="category_id"
                        class="form-select w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-model="categoryId" x-on:change="hasError = false">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thumbnail -->
                <div x-data="{ hasThumbnailError: {{ $errors->has('thumbnail') || $errors->has('thumbnail_id') ? 'true' : 'false' }} }" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ảnh đại diện bài
                        viết</label>
                    <img id="thumbnail_preview"
                        src="{{ old('thumbnail_id') ? optional($mediaFiles->firstWhere('id', old('thumbnail_id')))->filepath : (file_exists(public_path('images/placeholder.png')) ? asset('images/placeholder.png') : asset('images/fallback.png')) }}"
                        class="w-40 h-24 object-cover border rounded"
                        x-show="hasSelectedThumbnail || {{ old('thumbnail_id') ? 'true' : 'false' }}">
                    <div class="flex gap-2 mt-2">
                        <button type="button" @click="$refs.uploadInput.click()"
                            class="px-4 py-2 rounded text-sm 
               bg-blue-600 dark:bg-blue-800 
               text-white dark:text-white 
               hover:bg-blue-700 dark:hover:bg-blue-900 
               focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500">
                            Tải ảnh mới
                        </button>

                        <input type="file" accept="image/*" x-ref="uploadInput" class="hidden"
                            @change="uploadFiles($event); hasThumbnailError = false">
                    </div>
                    <!-- Thêm input hidden cho thumbnail_id -->
                    <input type="hidden" id="thumbnail_id" name="thumbnail_id" value="{{ old('thumbnail_id') }}"
                        x-on:change="hasThumbnailError = false">
                    @error('thumbnail')
                        <p class="text-xs text-red-500 mt-1" x-show="hasThumbnailError">{{ $message }}</p>
                    @enderror
                    @error('thumbnail_id')
                        <p class="text-xs text-red-500 mt-1" x-show="hasThumbnailError">{{ $message }}</p>
                    @enderror
                    <!-- Nút chi tiết hình ảnh -->
                    <div x-show="hasSelectedThumbnail" class="mt-2">
                        <button type="button" @click="openMediaLibrary()"
                            class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">Chi tiết hình ảnh</button>
                    </div>
                </div>

                <!-- Publish date -->
                <div x-data="{ publishDate: '{{ old('publish_date') }}', hasError: {{ $errors->has('publish_date') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày đăng
                        bài</label>
                    <input type="datetime-local" name="publish_date" x-model="publishDate"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-on:input="hasError = false">
                    @error('publish_date')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div x-data="{ status: '{{ old('status', 1) }}', hasError: {{ $errors->has('status') ? 'true' : 'false' }} }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái bài
                        viết</label>
                    <select name="status"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-model="status" x-on:change="hasError = false">
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Công khai</option>
                        <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Bản nháp</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1" x-show="hasError">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="text-right">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md shadow-md transition">
                        <i class="fas fa-save mr-2"></i> Lưu bài viết
                    </button>
                </div>
            </aside>
        </form>

        <!-- MEDIA POPUP -->
        <div x-show="showMedia" x-cloak x-transition @keydown.window.escape="closeMediaLibrary"
            @click.self="closeMediaLibrary"
            class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-gray-900 w-full max-w-4xl h-[90vh] sm:h-auto rounded-xl shadow-2xl flex flex-col overflow-hidden">
                <!-- Tiêu đề và nút đóng -->
                <div class="flex justify-between items-center p-6">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Chi tiết ảnh</span>
                    <button @click="closeMediaLibrary"
                        class="bg-gray-200 dark:bg-gray-700 rounded-full w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 text-xl font-bold z-10 transition-colors"
                        aria-label="Đóng popup">
                        ×
                    </button>
                </div>

                <!-- Nội dung chính -->
                <div class="flex flex-col sm:flex-row w-full h-full overflow-auto">
                    <!-- Bên trái: Thông tin ảnh -->
                    <div class="flex-1 p-6 space-y-4 flex flex-col" x-show="selectedMedia">
                        <img :src="selectedMedia?.filepath ||
                            '{{ file_exists(public_path('images/placeholder.png')) ? asset('images/placeholder.png') : asset('images/fallback.png') }}'"
                            alt="Media preview" class="w-full h-auto rounded-lg shadow-md object-contain max-h-96">
                        <div class="space-y-2 flex-1">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">Tên file:</span>
                                <span x-text="selectedMedia?.filename || 'Chưa có file'"></span>
                            </p>
                            <p class="text-sm text-gray-500 break-words">
                                <span class="font-semibold">Đường dẫn:</span>
                                <a :href="selectedMedia?.filepath || '#'" class="text-blue-600 hover:underline"
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

                    <!-- Bên phải: Các trường nhập và nút -->
                    <div class="flex-1 p-6 space-y-4 bg-gray-50 dark:bg-gray-800 flex flex-col">
                        <div class="space-y-4 flex-1">
                            <div>
                                <label for="altText"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Văn bản
                                    thay thế (Alt Text):</label>
                                <input id="altText" type="text" x-model="altText"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label for="titleText"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu
                                    đề:</label>
                                <input id="titleText" type="text" x-model="titleText"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <button x-show="selectedMedia" @click="deleteMedia(selectedMedia?.id)"
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-md text-sm font-medium shadow-md transition-colors">
                                Xoá ảnh
                            </button>
                            <button x-show="selectedMedia"
                                @click="updateMediaMeta(selectedMedia?.id, altText, titleText)"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md text-sm font-medium shadow-md transition-colors">
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
                    hasSelectedThumbnail: {{ old('thumbnail_id') ? 'true' : 'false' }},
                    uploadSuccessMessage: '',
                    altText: '',
                    titleText: '',
                    metaTitle: '{{ old('meta_title') }}',
                    slug: '{{ old('slug') }}',
                    metaDescription: '{{ old('meta_description') }}',
                    metaKeywords: '{{ old('meta_keywords') }}',
                    maxMetaTitle: 60,
                    maxSlug: 70,
                    maxMetaDescription: 160,
                    maxMetaKeywords: 100,

                    // Khởi tạo selectedMedia từ thumbnail_id
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
                                // Nếu thumbnail_id không hợp lệ, reset các giá trị
                                this.selectedMedia = null;
                                this.altText = '';
                                this.titleText = '';
                                this.hasSelectedThumbnail = false;
                                document.getElementById('thumbnail_id').value = '';
                                document.getElementById('thumbnail_preview').src =
                                    "{{ file_exists(public_path('images/placeholder.png')) ? asset('images/placeholder.png') : asset('images/fallback.png') }}";
                            }
                        }
                    },

                    formatSize(bytes) {
                        if (bytes < 1024) return bytes + ' B';
                        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                        return (bytes / 1048576).toFixed(1) + ' MB';
                    },

                    // Mở popup và hiển thị thông tin ảnh
                    openMediaLibrary() {
                        if (this.selectedMedia) {
                            this.showMedia = true;
                            this.altText = this.selectedMedia.alt_text || '';
                            this.titleText = this.selectedMedia.title || '';
                        } else {
                            this.uploadSuccessMessage = "Vui lòng chọn hoặc tải lên ảnh trước!";
                            setTimeout(() => {
                                this.uploadSuccessMessage = '';
                            }, 3000);
                        }
                    },

                    closeMediaLibrary() {
                        this.showMedia = false;
                    },

                    // Upload files
                    uploadFiles(event) {
                        const file = event.target.files[0];
                        if (!file) {
                            alert("Vui lòng chọn một file ảnh!");
                            return;
                        }

                        const formData = new FormData();
                        formData.append('file', file);

                        fetch('/admin/news/media/upload', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: formData
                            })
                            .then(res => {
                                if (!res.ok) {
                                    throw new Error('Failed to upload image');
                                }
                                return res.json();
                            })
                            .then(data => {
                                if (data.success && data.files && data.files.length > 0) {
                                    const newMedia = data.files[0];
                                    this.mediaList.push(newMedia);
                                    this.selectedMedia = newMedia;
                                    this.altText = newMedia.alt_text || '';
                                    this.titleText = newMedia.title || '';
                                    this.hasSelectedThumbnail = true;
                                    document.getElementById('thumbnail_preview').src = newMedia.filepath;
                                    document.getElementById('thumbnail_id').value = newMedia.id;

                                    this.uploadSuccessMessage = "Ảnh đã tải lên thành công!";
                                    setTimeout(() => {
                                        this.uploadSuccessMessage = '';
                                    }, 3000);
                                } else {
                                    alert("Lỗi khi tải ảnh!");
                                }
                            })
                            .catch(error => {
                                console.error("Lỗi khi tải ảnh:", error);
                                alert("Lỗi hệ thống khi tải ảnh!");
                            });
                    },

                    // Cập nhật thông tin ảnh
                    updateMediaMeta(mediaId, altText, titleText) {
                        if (!mediaId) {
                            alert("Không có ảnh được chọn!");
                            return;
                        }
                        if (!altText) {
                            alert("Vui lòng nhập Alt Text!");
                            return;
                        }
                        fetch(`/admin/media/meta/${mediaId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    alt_text: altText,
                                    title: titleText
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    this.uploadSuccessMessage = "Cập nhật thông tin ảnh thành công!";
                                    setTimeout(() => {
                                        this.uploadSuccessMessage = '';
                                    }, 3000);
                                    // Cập nhật mediaList và selectedMedia
                                    const mediaIndex = this.mediaList.findIndex(item => item.id == mediaId);
                                    if (mediaIndex !== -1) {
                                        this.mediaList[mediaIndex] = data.media;
                                        this.selectedMedia = data.media;
                                        this.altText = data.media.alt_text || '';
                                        this.titleText = data.media.title || '';
                                        // Cập nhật thumbnail preview nếu filepath thay đổi
                                        document.getElementById('thumbnail_preview').src = data.media.filepath;
                                    }
                                } else {
                                    alert(data.message || "Lỗi khi cập nhật thông tin ảnh!");
                                }
                            })
                            .catch(error => {
                                console.error("Lỗi khi cập nhật thông tin ảnh:", error);
                                alert("Lỗi hệ thống khi cập nhật ảnh!");
                            });
                    },

                    // Xóa ảnh
                    deleteMedia(mediaId) {
                        if (!mediaId) {
                            alert("Không có ảnh được chọn để xóa!");
                            return;
                        }
                        if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                            fetch(`/admin/media/${mediaId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    }
                                })
                                .then(res => {
                                    if (!res.ok) {
                                        return res.json().then(errorData => {
                                            throw new Error(errorData.message ||
                                                `HTTP error! Status: ${res.status}`);
                                        });
                                    }
                                    return res.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        this.uploadSuccessMessage = "Ảnh đã được xóa thành công!";
                                        this.mediaList = this.mediaList.filter(item => item.id != mediaId);
                                        this.selectedMedia = null;
                                        this.hasSelectedThumbnail = false;
                                        document.getElementById('thumbnail_preview').src =
                                            "{{ file_exists(public_path('images/placeholder.png')) ? asset('images/placeholder.png') : asset('images/fallback.png') }}";
                                        document.getElementById('thumbnail_id').value = '';
                                        // Tự động đóng popup sau khi xóa thành công
                                        this.closeMediaLibrary();
                                        setTimeout(() => {
                                            this.uploadSuccessMessage = '';
                                        }, 3000);
                                    } else {
                                        alert(data.message || "Lỗi khi xóa ảnh!");
                                    }
                                })
                                .catch(error => {
                                    console.error("Lỗi khi xóa ảnh:", error.message);
                                    alert(`Lỗi hệ thống khi xóa ảnh: ${error.message}`);
                                });
                        }
                    }
                }
            }

            document.addEventListener('imageUploaded', function(event) {
                const filepath = event.detail.filepath;
                Alpine.store('mediaHandler')?.set('uploadedImageUrl', filepath);
            });
        </script>
        <script src="{{ asset('ckeditor1/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('ckeditor', {
                filebrowserUploadUrl: "{{ route('admin.news.create.ckeditor') }}?CKEditorFuncNum=1&_token={{ csrf_token() }}{{ isset($news) ? '&news_id=' . $news->id : '' }}",
                filebrowserUploadMethod: 'form',
                extraPlugins: 'image,uploadimage',
                height: 400,
                skin: 'moono-lisa',
                removeButtons: 'PasteFromWord',
                contentsCss: [
                    '{{ asset('ckeditor1/style.css') }}', // Đảm bảo trỏ đúng file style.css
                    '{{ asset('ckeditor1/contents.css') }}' // Nếu có file custom.css, đảm bảo thêm nó
                ],
            });
        </script>

    </div>
</x-app-layout>
