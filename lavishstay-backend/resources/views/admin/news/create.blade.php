<x-app-layout>
    <div x-data="mediaHandler()" class="px-4 sm:px-6 lg:px-8 py-8 max-w-9xl mx-auto">

        <!-- Heading -->
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

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <!-- MAIN CONTENT -->
            <section class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề bài
                        viết</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug (đường
                        dẫn)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô tả ngắn (meta
                        description)</label>
                    <textarea name="meta_description" rows="3"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">{{ old('meta_description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ khóa SEO (ngăn
                        cách bởi dấu phẩy)</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung bài
                        viết</label>
                    <textarea name="content" id="ckeditor" rows="10"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">{{ old('content') }}</textarea>
                </div>
            </section>

            <!-- SIDEBAR -->
            <aside class="space-y-6">
                <!-- Category -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chuyên mục</label>
                    @foreach ($categories as $cat)
                        <label class="inline-flex items-center mt-1">
                            <input type="radio" name="category_id" value="{{ $cat->id }}"
                                class="form-radio h-5 w-5 text-blue-600"
                                {{ old('category_id') == $cat->id ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $cat->name }}</span>
                        </label><br>
                    @endforeach
                </div>

             <!-- Thumbnail -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow space-y-2">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ảnh đại diện bài viết</label>

    <!-- Hidden input lưu ID ảnh -->
    <input type="hidden" name="thumbnail_id" id="thumbnail_id" x-ref="thumbInput" value="{{ old('thumbnail_id') }}">

    <!-- Ảnh preview -->
    <img id="thumbnail_preview"
         src="{{ old('thumbnail_id') ? optional($mediaFiles->firstWhere('id', old('thumbnail_id')))->filepath : asset('images/placeholder.png') }}"
         class="w-40 h-24 object-cover border rounded">

    <!-- Nút chọn ảnh -->
    <div class="flex gap-2 mt-2">
        <button type="button"
                @click="openMediaLibrary()"
                class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">
            Chọn từ thư viện
        </button>

        <!-- Upload file mới -->
        <input type="file" multiple x-ref="uploadInput" class="hidden" @change="uploadFiles($event)">
        <button type="button"
                @click="$refs.uploadInput.click()"
                class="px-3 py-1 bg-gray-100 rounded text-sm">
            Tải ảnh mới
        </button>
    </div>
</div>


                <!-- Publish date -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày đăng bài</label>
                    <input type="datetime-local" name="publish_date" value="{{ old('publish_date') }}"
                        class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
                </div>

                <!-- Status -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái bài
                        viết</label>
                    <select name="status" class="w-full border dark:bg-gray-900 dark:text-white rounded-md py-2 px-3">
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Công khai</option>
                        <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Bản nháp</option>
                    </select>
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


 @php
    $mediaJson = $mediaFiles->map(function ($f) {
        return [
            'id' => $f->id,
            'filename' => $f->filename,
            'filepath' => $f->filepath,
            'alt_text' => $f->alt_text,
            'title' => $f->title,
            'caption' => $f->caption,
            'description' => $f->description,
        ];
    })->values();
@endphp

<!-- MEDIA POPUP -->
<div x-show="showMedia" x-transition
     @keydown.window.escape="closeMediaLibrary"
     @click.self="closeMediaLibrary"
     class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center">
  <div class="bg-white dark:bg-gray-900 w-full max-w-7xl h-[90vh] rounded-xl shadow-xl flex overflow-hidden relative"
       @click.stop>

    <!-- Nút X đóng -->
    <button @click="closeMediaLibrary"
            class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-lg font-bold z-10">
      &times;
    </button>

    <!-- Left: Media Grid -->
    <div class="flex-1 flex flex-col border-r border-gray-200 dark:border-gray-700 p-6 overflow-hidden">

      <!-- Toolbar -->
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <select class="border text-sm rounded px-2 py-1 dark:bg-gray-800 dark:text-white">
            <option>Images</option>
            <option>All files</option>
          </select>
          <select class="border text-sm rounded px-2 py-1 dark:bg-gray-800 dark:text-white">
            <option>All dates</option>
            <option>Last 7 days</option>
          </select>
        </div>
        <input type="text" placeholder="Search media..." class="text-sm border rounded px-3 py-1 w-56 dark:bg-gray-800 dark:text-white">
      </div>

      <!-- Media Grid -->
      <div class="overflow-y-auto flex-1">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
          <template x-for="file in mediaList" :key="file.id">
            <div class="relative group cursor-pointer overflow-hidden border rounded-lg transition hover:shadow-lg"
                 :class="{'ring-2 ring-indigo-500': selectedMedia?.id === file.id}"
                 @click="selectMedia(file)">
              <img :src="file.filepath" class="w-full h-28 object-cover rounded">
              <div class="absolute top-2 left-2">
                <template x-if="selectedMedia?.id === file.id">
                  <svg class="w-5 h-5 text-indigo-600 bg-white rounded-full p-0.5 shadow" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd" />
                  </svg>
                </template>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- Right: Sidebar -->
    <div class="w-80 bg-gray-50 dark:bg-gray-800 p-6 overflow-y-auto">
      <template x-if="selectedMedia">
        <div>
          <h3 class="text-base font-semibold mb-4 text-gray-900 dark:text-white">Chi tiết ảnh</h3>
          <img :src="selectedMedia.filepath" class="w-full h-auto mb-4 rounded shadow">
          <p class="text-sm text-gray-700 dark:text-gray-300 mb-1" x-text="selectedMedia.filename"></p>
          <p class="text-xs text-gray-500 mb-4" x-text="selectedMedia.created_at"></p>

          <div class="space-y-2">
            <div>
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Alt Text</label>
              <input type="text" x-model="selectedMedia.alt_text" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-900 dark:text-white">
            </div>
            <div>
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Title</label>
              <input type="text" x-model="selectedMedia.title" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-900 dark:text-white">
            </div>
            <div>
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Caption</label>
              <textarea x-model="selectedMedia.caption" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-900 dark:text-white"></textarea>
            </div>
            <div>
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Mô tả</label>
              <textarea x-model="selectedMedia.description" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-900 dark:text-white"></textarea>
            </div>
            <div>
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300">File URL</label>
              <div class="flex items-center gap-2">
                <input type="text" :value="selectedMedia.filepath" readonly class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-900 dark:text-white">
                <button @click="navigator.clipboard.writeText(selectedMedia.filepath)" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Copy</button>
              </div>
            </div>
          </div>

          <button @click="useSelected()" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded text-sm font-medium shadow">
            Dùng ảnh này
          </button>
        </div>
      </template>
    </div>
  </div>
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
        skin: 'moono-lisa',
        removeButtons: 'PasteFromWord' // nếu muốn loại bỏ một số nút
    });
</script>


</x-app-layout>

<script>
  function mediaHandler() {
    return {
      showMedia: false,
      mediaList: @json($mediaJson),
      selectedMedia: null,

      openMediaLibrary() {
        this.showMedia = true;
      },
      closeMediaLibrary() {
        this.showMedia = false;
        this.selectedMedia = null;
      },
      selectMedia(file) {
        this.selectedMedia = file;
      },
      useSelected() {
        if (!this.selectedMedia) return;
        document.getElementById('thumbnail_id').value = this.selectedMedia.id;
        document.getElementById('thumbnail_preview').src = this.selectedMedia.filepath;
        this.closeMediaLibrary();
      }
    }
  }
</script>