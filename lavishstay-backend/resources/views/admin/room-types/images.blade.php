<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.room-types') }}"
                                class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Quản lý loại phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.room-types.show', $roomType) }}"
                                    class="ml-1 text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                    {{ $roomType->name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">Quản lý ảnh</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý ảnh - {{ $roomType->name }}</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                
                <div class="grid cursor-pointer grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add room type button -->
              
                    <button onclick="openUploadModal()"
                        class="btn items-center cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current items-center shrink-0 w-4 h-4 me-2" viewBox="0 0 12 12" width="24" height="24">
                        <path d="M7 4V2a1 1 0 012 0v2h2a1 1 0 010 2H9v2a1 1 0 01-2 0V6H5a1 1 0 010-2h2z"/>
                    </svg>
                        <span class="max-xs:sr-only">Tải ảnh lên</span>
                    </button>
              
            </div>
                <button id="deleteSelectedBtn" onclick="deleteSelected()" disabled
                    class="btn cursor-pointer bg-red-600 text-white hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <svg class="fill-current shrink-0 w-4 h-4 me-2" viewBox="0 0 16 16" width="16" height="16">
                        <path d="M5 7h6a1 1 0 010 2H5a1 1 0 010-2zM4 2a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H4z"/>
                    </svg>
                    <span>Xóa đã chọn</span>
                </button>
            </div>
        </div>

        <!-- Room Type Info Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
            <div class="p-6">
                <div class="flex items-center gap-6">
                    <div class="flex-shrink-0 ">
                        @if($roomType->images->where('is_main', true)->first())
                            <img class="w-30 h-20 rounded-lg object-cover" 
                                 src="{{ $roomType->images->where('is_main', true)->first()->image_url }}" 
                                 alt="{{ $roomType->name }}">
                        @else
                            <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="ml-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $roomType->name }}</h3>
                                                <p class="text-gray-500 dark:text-gray-400">{{ $roomType->description ?? 'Chưa có mô tả' }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="text-sm me-3 text-gray-600 dark:text-gray-400">
                                Tổng số ảnh: <span class="font-medium text-blue-600" id="totalImages">{{ $images->count() }}</span>
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Ảnh chính: <span class="font-medium {{ $images->where('is_main', true)->count() > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $images->where('is_main', true)->count() > 0 ? '✓' : '✗' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tìm kiếm</label>
                        <input type="text" id="searchInput" placeholder="Tìm theo mô tả ảnh..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại ảnh</label>
                        <select id="typeFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Tất cả</option>
                            <option value="main">Ảnh chính</option>
                            <option value="other">Ảnh phụ</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sắp xếp</label>
                        <select id="sortFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="main_first">Ảnh chính trước</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end">
                        <button onclick="clearFilters()" class="btn border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Xóa bộ lọc
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Grid -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <input type="checkbox" id="selectAll" class="mr-3 rounded">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                        Thư viện ảnh <span id="imageCount">({{ $images->count() }})</span>
                    </h2>
                </div>
            </div>

            <div class="p-6">
                @if($images->count() > 0)
                    <div id="imagesGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                                            @foreach ($images as $image)
                        <div class="image-item relative group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-200"
                             data-image-id="{{ $image->image_id }}"
                             data-alt-text="{{ $image->alt_text }}"
                             data-is-main="{{ $image->is_main ? 'true' : 'false' }}"
                             data-created="{{ $image->created_at->toISOString() }}">
                            
                            <!-- Selection Checkbox -->
                            <div class="absolute top-3 left-3 z-20">
                                <input type="checkbox" value="{{ $image->image_id }}" 
                                       class="image-checkbox z-auto w-4 h-4 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            </div>

                            <!-- Main Image Badge -->
                            @if ($image->is_main)
                                <div class="absolute top-3 right-3 z-20">
                                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                        ⭐ Ảnh chính
                                    </span>
                                </div>
                             @endif

                            <!-- Image Container -->
                            <div class="relative aspect-square overflow-hidden rounded-t-xl cursor-pointer"
                                 onclick="viewImageFullSize('{{ $image->image_url }}', '{{ $image->alt_text }}')">
                                <img src="{{ $image->image_url }}" 
                                     alt="{{ $image->alt_text }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     onerror="handleImageError(this)">
                                
                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="bg-white bg-opacity-90 text-gray-800 px-4 py-2 rounded-lg">
                                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            Xem ảnh lớn
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Info & Actions -->
                            <div class="p-4">
                                <!-- Alt Text -->
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate" title="{{ $image->alt_text }}">
                                        {{ $image->alt_text }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                        {{ $image->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-2">
                                    <!-- Edit Button -->
                                    <button onclick="editImage({{ $image->image_id }})"
                                            class="flex-1 min-w-0 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Sửa
                                    </button>

                                    <!-- Set Main Button (only show if not main) -->
                                    @if (!$image->is_main)
                                        <button onclick="setMainImage({{ $image->image_id }})"
                                                class="flex-1 min-w-0 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                            Đặt chính
                                        </button>
                                    @endif

                                    <!-- Delete Button -->
                                    <button onclick="deleteImage({{ $image->image_id }})"
                                            class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach


                        <!-- Add New Image Card -->
                        <div class="relative group">
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg aspect-square flex items-center justify-center hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors cursor-pointer"
                                 onclick="openUploadModal()">
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Thêm ảnh mới</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chưa có ảnh nào</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Bắt đầu bằng cách tải lên ảnh đầu tiên cho loại phòng này</p>
                        <button onclick="openUploadModal()" class="btn bg-blue-600 text-white hover:bg-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tải ảnh lên
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            
                    <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tải ảnh lên</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Content -->
                <div class="p-6">
                    <!-- Upload Area -->
                    <div id="uploadArea" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-blue-500 transition-colors cursor-pointer mb-4">
                        <input type="file" id="imageInput" multiple accept="image/*" class="hidden">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">Kéo thả ảnh vào đây</p>
                        <p class="text-gray-500 dark:text-gray-500 mb-4">hoặc</p>
                        <button type="button" class="btn bg-blue-600 text-white hover:bg-blue-700">Chọn ảnh từ máy tính</button>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                            Hỗ trợ: PNG, JPG, GIF, WEBP. Tối đa 10 ảnh, mỗi ảnh không quá 5MB
                        </p>
                    </div>

                    <!-- Selected Files Preview -->
                    <div id="selectedFiles" class="hidden">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Ảnh đã chọn:</h4>
                        <div id="filesList" class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4"></div>
                    </div>

                    <!-- Upload Progress -->
                    <div id="uploadProgress" class="hidden mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Đang tải lên...</span>
                            <span id="progressText" class="text-sm text-gray-500 dark:text-gray-400">0%</span>
                        </div>
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeUploadModal()" class="btn border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Hủy
                        </button>
                        <button id="uploadBtn" onclick="uploadImages()" disabled class="btn bg-blue-600 text-white hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Tải lên
                        </button>
                    </div>
                </div>
				
				
				
				

            
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            
            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Chỉnh sửa ảnh</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <form id="editForm" class="p-6">
                <div class="mb-4">
                    <label for="editAltText" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Mô tả ảnh
                    </label>
                    <textarea id="editAltText" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
                              placeholder="Nhập mô tả cho ảnh..."></textarea>
                </div>

                <!-- Footer -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="btn border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Hủy
                    </button>
                    <button type="submit" class="btn bg-blue-600 text-white hover:bg-blue-700">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Full Size Image Viewer Modal -->
    <div id="imageViewerModal" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden flex items-center justify-center p-4">
        
        <!-- Close Button -->
        <button onclick="closeImageViewer()" class="absolute top-6 right-6 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Image Container -->
        <div class="max-w-7xl max-h-full w-full h-full flex items-center justify-center">
            <div class="relative max-w-full max-h-full flex flex-col items-center">
                
                <!-- Loading Spinner -->
                <div id="imageLoader" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg hidden">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                </div>

                <!-- Main Image -->
                <img id="viewerImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">
                
                <!-- Image Info -->
                <div class="mt-6 text-center bg-black bg-opacity-70 text-white px-6 py-3 rounded-lg backdrop-blur-sm">
                    <h3 id="viewerTitle" class="text-xl font-semibold mb-2"></h3>
                    <p class="text-gray-300">Nhấn ESC để đóng • Click để phóng to</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let selectedFiles = [];
        let currentEditingImageId = null;
        const roomTypeId = {{ $roomType->room_type_id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // DOM Elements
        const uploadModal = document.getElementById('uploadModal');
        const editModal = document.getElementById('editModal');
        const imageViewerModal = document.getElementById('imageViewerModal');
        const uploadArea = document.getElementById('uploadArea');
        const imageInput = document.getElementById('imageInput');
        const selectedFilesDiv = document.getElementById('selectedFiles');
        const filesListDiv = document.getElementById('filesList');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadProgress = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const selectAllCheckbox = document.getElementById('selectAll');

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
            initializeFilters();
            updateSelectedCount();
        });

        // Event Listeners
        function initializeEventListeners() {
            // Upload area click
            uploadArea.addEventListener('click', () => imageInput.click());
            
            // File input change
            imageInput.addEventListener('change', handleFileSelect);
            
            // Drag and drop
            uploadArea.addEventListener('dragover', handleDragOver);
            uploadArea.addEventListener('drop', handleDrop);
            uploadArea.addEventListener('dragleave', handleDragLeave);
            
            // Select all checkbox
            selectAllCheckbox.addEventListener('change', handleSelectAll);
            
            // Individual checkboxes
            document.querySelectorAll('.image-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Edit form submit
            document.getElementById('editForm').addEventListener('submit', handleEditSubmit);

            // Keyboard events
            document.addEventListener('keydown', handleKeyDown);

            // Click outside modals to close
            [uploadModal, editModal, imageViewerModal].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this);
                    }
                });
            });

            // Image viewer click to zoom
            document.getElementById('viewerImage').addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('scale-150');
                this.style.cursor = this.classList.contains('scale-150') ? 'zoom-out' : 'zoom-in';
            });
        }

        // Upload Modal Functions
        function openUploadModal() {
            uploadModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUploadModal() {
            uploadModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetUploadForm();
        }

        function resetUploadForm() {
            selectedFiles = [];
            imageInput.value = '';
            selectedFilesDiv.classList.add('hidden');
            uploadProgress.classList.add('hidden');
            filesListDiv.innerHTML = '';
            uploadBtn.disabled = true;
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
        }

        // File Handling
        function handleFileSelect(e) {
            const files = Array.from(e.target.files);
            processSelectedFiles(files);
        }

        function handleDragOver(e) {
            e.preventDefault();
            uploadArea.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        }

        function handleDrop(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            
            const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
            processSelectedFiles(files);
        }

        function processSelectedFiles(files) {
            // Validate files
            const validFiles = files.filter(file => {
                if (!file.type.startsWith('image/')) {
                    showNotification('Chỉ chấp nhận file ảnh', 'error');
                    return false;
                }
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    showNotification(`File ${file.name} quá lớn (tối đa 5MB)`, 'error');
                    return false;
                }
                return true;
            });

            if (validFiles.length === 0) return;

            // Limit to 10 files total
            selectedFiles = [...selectedFiles, ...validFiles].slice(0, 10);
            
            if (selectedFiles.length >= 10) {
                showNotification('Tối đa 10 ảnh mỗi lần tải lên', 'warning');
            }

            displaySelectedFiles();
            uploadBtn.disabled = selectedFiles.length === 0;
        }

        function displaySelectedFiles() {
            if (selectedFiles.length === 0) {
                selectedFilesDiv.classList.add('hidden');
                return;
            }

            selectedFilesDiv.classList.remove('hidden');
            filesListDiv.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileDiv = document.createElement('div');
                fileDiv.className = 'relative';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    fileDiv.innerHTML = `
                        <div class="relative">
                            <img src="${e.target.result}" alt="${file.name}" class="w-full h-24 object-cover rounded-lg">
                            
                                                      <button onclick="removeFile(${index})" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700 transition-colors">
                                ×
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white text-xs p-1 rounded-b-lg truncate">
                                ${file.name}
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
                
                filesListDiv.appendChild(fileDiv);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            displaySelectedFiles();
            uploadBtn.disabled = selectedFiles.length === 0;
        }

        // Upload Function
        async function uploadImages() {
            if (selectedFiles.length === 0) return;

            const formData = new FormData();
            selectedFiles.forEach(file => {
                formData.append('images[]', file);
            });

            uploadBtn.disabled = true;
            uploadProgress.classList.remove('hidden');

            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/images/upload`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                // Simulate progress for better UX
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 30;
                    if (progress > 90) progress = 90;
                    
                    progressBar.style.width = progress + '%';
                    progressText.textContent = Math.round(progress) + '%';
                }, 200);

                const result = await response.json();
                
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                progressText.textContent = '100%';

                if (result.success) {
                    showNotification(result.message, 'success');
                    setTimeout(() => {
                        location.reload(); // Reload to show new images
                    }, 1000);
                } else {
                    throw new Error(result.message || 'Upload failed');
                }

            } catch (error) {
                console.error('Upload error:', error);
                showNotification('Có lỗi xảy ra khi tải ảnh lên: ' + error.message, 'error');
                uploadBtn.disabled = false;
                uploadProgress.classList.add('hidden');
            }
        }

        // Edit Modal Functions
        function editImage(imageId) {
            const imageItem = document.querySelector(`[data-image-id="${imageId}"]`);
            const altText = imageItem.getAttribute('data-alt-text');
            
            currentEditingImageId = imageId;
            document.getElementById('editAltText').value = altText;
            
            editModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentEditingImageId = null;
        }

        async function handleEditSubmit(e) {
            e.preventDefault();
            
            if (!currentEditingImageId) return;

            const altText = document.getElementById('editAltText').value.trim();
            if (!altText) {
                showNotification('Mô tả ảnh không được để trống', 'error');
                return;
            }

            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/images/${currentEditingImageId}/update`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ alt_text: altText })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');
                    
                    // Update UI
                    const imageItem = document.querySelector(`[data-image-id="${currentEditingImageId}"]`);
                    imageItem.setAttribute('data-alt-text', altText);
                    imageItem.querySelector('.truncate').textContent = altText;
                    imageItem.querySelector('.truncate').title = altText;
                    
                    closeEditModal();
                } else {
                    throw new Error(result.message || 'Update failed');
                }

            } catch (error) {
                console.error('Edit error:', error);
                showNotification('Có lỗi xảy ra khi cập nhật: ' + error.message, 'error');
            }
        }

        // Set Main Image Function
        async function setMainImage(imageId) {
            if (!confirm('Bạn có chắc chắn muốn đặt ảnh này làm ảnh chính?')) return;

            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/images/${imageId}/set-main`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');
                    
                    // Update UI - remove all main badges first
                    document.querySelectorAll('.image-item').forEach(item => {
                        const badge = item.querySelector('.bg-blue-600');
                        if (badge) badge.remove();
                        
                        // Show set main button for all images
                        const setMainBtn = item.querySelector('button[onclick*="setMainImage"]');
                        if (setMainBtn) setMainBtn.style.display = 'block';
                        
                        item.setAttribute('data-is-main', 'false');
                    });
                    
                    // Add main badge to selected image
                    const selectedItem = document.querySelector(`[data-image-id="${imageId}"]`);
                    selectedItem.setAttribute('data-is-main', 'true');
                    
                    const badge = document.createElement('div');
                    badge.className = 'absolute top-2 right-2 z-10';
                    badge.innerHTML = '<span class="bg-blue-600 text-white px-2 py-1 rounded-full text-xs font-medium shadow-lg">⭐ Chính</span>';
                    selectedItem.querySelector('.relative.aspect-square').appendChild(badge);
                    
                    // Hide set main button for this image
                    const setMainBtn = selectedItem.querySelector('button[onclick*="setMainImage"]');
                    if (setMainBtn) setMainBtn.style.display = 'none';
                    
                } else {
                    throw new Error(result.message || 'Set main failed');
                }

            } catch (error) {
                console.error('Set main error:', error);
                showNotification('Có lỗi xảy ra: ' + error.message, 'error');
            }
        }

        // Delete Functions
        async function deleteImage(imageId) {
            if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) return;

            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');
                    
                    // Remove from UI
                    const imageItem = document.querySelector(`[data-image-id="${imageId}"]`);
                    imageItem.remove();
                    
                    updateImageCount();
                    updateTotalImagesCount();
                    
                } else {
                    throw new Error(result.message || 'Delete failed');
                }

            } catch (error) {
                console.error('Delete error:', error);
                showNotification('Có lỗi xảy ra: ' + error.message, 'error');
            }
        }

        function deleteSelected() {
            const selectedCheckboxes = document.querySelectorAll('.image-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                showNotification('Vui lòng chọn ít nhất một ảnh để xóa', 'warning');
                return;
            }

            if (!confirm(`Bạn có chắc chắn muốn xóa ${selectedCheckboxes.length} ảnh đã chọn?`)) return;

            const deletePromises = Array.from(selectedCheckboxes).map(checkbox => {
                const imageId = checkbox.value;
                return fetch(`/admin/room-types/${roomTypeId}/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
            });

            Promise.all(deletePromises)
                .then(responses => Promise.all(responses.map(r => r.json())))
                .then(results => {
                    const successCount = results.filter(r => r.success).length;
                    const failCount = results.length - successCount;
                    
                    if (successCount > 0) {
                        showNotification(`Đã xóa thành công ${successCount} ảnh`, 'success');
                        
                        // Remove from UI
                        selectedCheckboxes.forEach(checkbox => {
                            const imageItem = checkbox.closest('.image-item');
                            imageItem.remove();
                        });
                        
                        updateImageCount();
                        updateTotalImagesCount();
                        updateSelectedCount();
                    }
                    
                    if (failCount > 0) {
                        showNotification(`Có ${failCount} ảnh không thể xóa`, 'error');
                    }
                })
                .catch(error => {
                    console.error('Bulk delete error:', error);
                    showNotification('Có lỗi xảy ra khi xóa ảnh', 'error');
                });
        }

        // Selection Functions
        function handleSelectAll() {
            const checkboxes = document.querySelectorAll('.image-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const selectedCheckboxes = document.querySelectorAll('.image-checkbox:checked');
            const count = selectedCheckboxes.length;
            
            deleteSelectedBtn.disabled = count === 0;
            deleteSelectedBtn.querySelector('span').textContent = count > 0 ? `Xóa đã chọn (${count})` : 'Xóa đã chọn';
            
            // Update select all checkbox state
            const totalCheckboxes = document.querySelectorAll('.image-checkbox');
            if (totalCheckboxes.length > 0) {
                selectAllCheckbox.indeterminate = count > 0 && count < totalCheckboxes.length;
                selectAllCheckbox.checked = count === totalCheckboxes.length;
            }
        }

        // Image Viewer Functions
        function viewImageFullSize(imageUrl, altText) {
            const viewerImage = document.getElementById('viewerImage');
            const viewerTitle = document.getElementById('viewerTitle');
            const imageLoader = document.getElementById('imageLoader');
            
            // Show loader
            imageLoader.classList.remove('hidden');
            
            // Set image
            viewerImage.onload = function() {
                imageLoader.classList.add('hidden');
            };
            
            viewerImage.src = imageUrl;
            viewerImage.alt = altText;
            viewerTitle.textContent = altText;
            
            // Reset zoom
            viewerImage.classList.remove('scale-150');
            viewerImage.style.cursor = 'zoom-in';
            
            // Show modal
            imageViewerModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageViewer() {
            imageViewerModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Filter Functions
        function initializeFilters() {
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const sortFilter = document.getElementById('sortFilter');

            [searchInput, typeFilter, sortFilter].forEach(element => {
                element.addEventListener('input', applyFilters);
                element.addEventListener('change', applyFilters);
            });
        }

        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value;
            const sortFilter = document.getElementById('sortFilter').value;

            let imageItems = Array.from(document.querySelectorAll('.image-item'));

            // Filter by search term
            if (searchTerm) {
                imageItems.forEach(item => {
                    const altText = item.getAttribute('data-alt-text').toLowerCase();
                    item.style.display = altText.includes(searchTerm) ? 'block' : 'none';
                });
            } else {
                imageItems.forEach(item => item.style.display = 'block');
            }

            // Filter by type
            if (typeFilter) {
                imageItems.forEach(item => {
                    if (item.style.display === 'none') return;
                    
                    const isMain = item.getAttribute('data-is-main') === 'true';
                    const shouldShow = typeFilter === 'main' ? isMain : !isMain;
                    item.style.display = shouldShow ? 'block' : 'none';
                });
            }

            // Get visible items for sorting
            const visibleItems = imageItems.filter(item => item.style.display !== 'none');

            // Sort items
            visibleItems.sort((a, b) => {
                switch (sortFilter) {
                    case 'oldest':
                        return new Date(a.getAttribute('data-created')) - new Date(b.getAttribute('data-created'));
                    case 'main_first':
                        const aIsMain = a.getAttribute('data-is-main') === 'true';
                        const bIsMain = b.getAttribute('data-is-main') === 'true';
                        if (aIsMain && !bIsMain) return -1;
                        if (!aIsMain && bIsMain) return 1;
                        return new Date(b.getAttribute('data-created')) - new Date(a.getAttribute('data-created'));
                    case 'newest':
                    default:
                        return new Date(b.getAttribute('data-created')) - new Date(a.getAttribute('data-created'));
                }
            });

            // Reorder DOM elements
            const grid = document.getElementById('imagesGrid');
            const addNewCard = grid.querySelector('.relative.group:last-child'); // Keep "Add New" card
            
            visibleItems.forEach(item => {
                grid.appendChild(item);
            });
            
                       // Keep "Add New" card at the end
            if (addNewCard) {
                grid.appendChild(addNewCard);
            }

            updateImageCount();
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('typeFilter').value = '';
            document.getElementById('sortFilter').value = 'newest';
            applyFilters();
        }

        // Utility Functions
        function updateImageCount() {
            const visibleItems = document.querySelectorAll('.image-item[style*="display: block"], .image-item:not([style*="display: none"])');
            const totalItems = document.querySelectorAll('.image-item');
            const count = Array.from(visibleItems).filter(item => item.style.display !== 'none').length;
            const total = totalItems.length;
            
            document.getElementById('imageCount').textContent = `(${count}${count !== total ? `/${total}` : ''})`;
        }

        function updateTotalImagesCount() {
            const totalImages = document.querySelectorAll('.image-item').length;
            document.getElementById('totalImages').textContent = totalImages;
        }

        function closeModal(modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            if (modal === uploadModal) {
                resetUploadForm();
            } else if (modal === editModal) {
                currentEditingImageId = null;
            }
        }

        function handleKeyDown(e) {
            // ESC key to close modals
            if (e.key === 'Escape') {
                if (!uploadModal.classList.contains('hidden')) {
                    closeUploadModal();
                } else if (!editModal.classList.contains('hidden')) {
                    closeEditModal();
                } else if (!imageViewerModal.classList.contains('hidden')) {
                    closeImageViewer();
                }
            }
            
            // Delete key to delete selected images
            if (e.key === 'Delete' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                const selectedCheckboxes = document.querySelectorAll('.image-checkbox:checked');
                if (selectedCheckboxes.length > 0) {
                    deleteSelected();
                }
            }
        }

        // Notification System
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
            
            const colors = {
                success: 'bg-green-600 text-white',
                error: 'bg-red-600 text-white',
                warning: 'bg-yellow-600 text-white',
                info: 'bg-blue-600 text-white'
            };
            
            notification.className += ` ${colors[type] || colors.info}`;
            
            const icons = {
                success: '✓',
                error: '✗',
                warning: '⚠',
                info: 'ℹ'
            };
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="text-lg mr-3">${icons[type] || icons.info}</span>
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Image Error Handling
        function handleImageError(img) {
            img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik02MCA2MEgxNDBWMTQwSDYwVjYwWiIgZmlsbD0iI0Q1RDlERCIvPgo8cGF0aCBkPSJNODAgODBIMTIwVjEyMEg4MFY4MFoiIGZpbGw9IiNBN0E5QUMiLz4KPC9zdmc+';
            img.alt = 'Ảnh không tải được';
            img.classList.add('opacity-50');
        }

        // Add error handling to all images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(function(img) {
                img.addEventListener('error', function() {
                    handleImageError(this);
                });
            });
        });

        // Touch/Swipe support for mobile image viewer
        let touchStartX = 0;
        let touchEndX = 0;

        document.getElementById('viewerImage').addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.getElementById('viewerImage').addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                // Could implement next/previous image navigation here
                console.log('Swipe detected:', diff > 0 ? 'left' : 'right');
            }
        }

        // Lazy loading for images
        function initializeLazyLoading() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }

        // Performance optimization - debounce search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Apply debounce to search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                const debouncedSearch = debounce(applyFilters, 300);
                searchInput.removeEventListener('input', applyFilters);
                searchInput.addEventListener('input', debouncedSearch);
            }
        });

        // Preload next/previous images for better UX
        function preloadAdjacentImages(currentImageUrl) {
            const images = Array.from(document.querySelectorAll('.image-item img'));
            const currentIndex = images.findIndex(img => img.src === currentImageUrl);
            
            // Preload next and previous images
            [currentIndex - 1, currentIndex + 1].forEach(index => {
                if (index >= 0 && index < images.length) {
                    const img = new Image();
                    img.src = images[index].src;
                }
            });
        }

        // Context menu for images (right-click options)
        document.addEventListener('contextmenu', function(e) {
            const imageItem = e.target.closest('.image-item');
            if (imageItem) {
                e.preventDefault();
                showContextMenu(e, imageItem);
            }
        });

        function showContextMenu(e, imageItem) {
            // Remove existing context menu
            const existingMenu = document.querySelector('.context-menu');
            if (existingMenu) existingMenu.remove();

            const imageId = imageItem.getAttribute('data-image-id');
            const isMain = imageItem.getAttribute('data-is-main') === 'true';

            const contextMenu = document.createElement('div');
            contextMenu.className = 'context-menu fixed bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-2 z-50';
            contextMenu.style.left = e.pageX + 'px';
            contextMenu.style.top = e.pageY + 'px';

            const menuItems = [
                { text: 'Xem ảnh lớn', action: () => viewImageFullSize(imageItem.querySelector('img').src, imageItem.getAttribute('data-alt-text')) },
                { text: 'Chỉnh sửa', action: () => editImage(imageId) },
                ...(isMain ? [] : [{ text: 'Đặt làm ảnh chính', action: () => setMainImage(imageId) }]),
                { text: 'Sao chép URL', action: () => copyImageUrl(imageItem.querySelector('img').src) },
                { text: '---', action: null },
                { text: 'Xóa ảnh', action: () => deleteImage(imageId), class: 'text-red-600' }
            ];

            menuItems.forEach(item => {
                if (item.text === '---') {
                    const divider = document.createElement('div');
                    divider.className = 'border-t border-gray-200 dark:border-gray-700 my-1';
                    contextMenu.appendChild(divider);
                } else {
                    const menuItem = document.createElement('button');
                    menuItem.className = `block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ${item.class || 'text-gray-700 dark:text-gray-300'}`;
                    menuItem.textContent = item.text;
                    menuItem.onclick = () => {
                        if (item.action) item.action();
                        contextMenu.remove();
                    };
                    contextMenu.appendChild(menuItem);
                }
            });

            document.body.appendChild(contextMenu);

            // Remove context menu when clicking elsewhere
            setTimeout(() => {
                document.addEventListener('click', function removeContextMenu() {
                    contextMenu.remove();
                    document.removeEventListener('click', removeContextMenu);
                });
            }, 100);
        }

        function copyImageUrl(url) {
            navigator.clipboard.writeText(url).then(() => {
                showNotification('Đã sao chép URL ảnh', 'success');
            }).catch(() => {
                showNotification('Không thể sao chép URL', 'error');
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + A to select all
            if ((e.ctrlKey || e.metaKey) && e.key === 'a' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                selectAllCheckbox.checked = true;
                handleSelectAll();
            }
            
            // Ctrl/Cmd + U to open upload modal
            if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
                e.preventDefault();
                openUploadModal();
            }
        });

        // Auto-save scroll position
        window.addEventListener('beforeunload', function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });

        window.addEventListener('load', function() {
            const scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                sessionStorage.removeItem('scrollPosition');
            }
        });
    </script>

    <!-- Custom CSS for animations and responsive design -->
    <style>
        /* Fix z-index issues */
        .image-item {
            position: relative;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .image-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Ensure checkboxes and badges are visible */
        .image-checkbox {
            position: relative;
            z-index: 30 !important;
            background-color: white !important;
            border: 2px solid #d1d5db !important;
            border-radius: 4px !important;
            width: 16px !important;
            height: 16px !important;
            cursor: pointer !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        .image-checkbox:checked {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }

        /* Badge styling */
        .image-item .absolute.top-3.right-3 {
            z-index: 25 !important;
        }

        .image-item .absolute.top-3.left-3 {
            z-index: 30 !important;
        }
/* Main image badge */
        .bg-blue-600.text-white.px-3.py-1 {
            background-color: #3b82f6 !important;
            color: white !important;
            padding: 4px 12px !important;
            border-radius: 9999px !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            backdrop-filter: blur(4px) !important;
        }

        

        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .image-item:hover img {
            transform: scale(1.05);
        }

        

        /* Action buttons container */
        .image-item .p-4 {
            position: relative;
            z-index: 10;
        }

        /* Button styling */
        .image-item button {
            position: relative;
            z-index: 15;
            transition: all 0.2s ease;
        }

        .image-item button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Upload area animations */
        #uploadArea {
            transition: all 0.3s ease;
        }

        #uploadArea.dragover {
            transform: scale(1.02);
            border-color: #3b82f6;
            background-color: rgba(59, 130, 246, 0.1);
        }

        /* Progress bar animation */
        #progressBar {
            transition: width 0.3s ease;
        }

        /* Modal animations */
        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }

        .modal-exit {
            animation: modalExit 0.3s ease-in;
        }

        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes modalExit {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            to {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
        }

        /* Image viewer zoom */
        #viewerImage {
            transition: transform 0.3s ease;
            cursor: zoom-in;
        }

        #viewerImage.scale-150 {
            transform: scale(1.5);
            cursor: zoom-out;
        }

                /* Image viewer zoom */
        #viewerImage {
            transition: transform 0.3s ease;
            cursor: zoom-in;
        }

        #viewerImage.scale-150 {
            transform: scale(1.5);
            cursor: zoom-out;
        }

        /* Context menu */
        .context-menu {
            min-width: 180px;
            animation: contextMenuShow 0.2s ease-out;
        }

        @keyframes contextMenuShow {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Checkbox animations */
        .image-checkbox {
            transition: all 0.2s ease;
        }

        .image-checkbox:checked {
            transform: scale(1.1);
        }

        /* Loading states */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Dark mode loading */
        .dark .loading::after {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        /* Responsive grid adjustments */
        @media (max-width: 640px) {
            #imagesGrid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            #imagesGrid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .context-menu {
                min-width: 150px;
            }
        }

        /* Notification animations */
        .notification {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Button hover effects */
        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Image placeholder */
        .image-placeholder {
            background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%), 
                        linear-gradient(-45deg, #f0f0f0 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #f0f0f0 75%), 
                        linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }

        .dark .image-placeholder {
            background: linear-gradient(45deg, #374151 25%, transparent 25%), 
                        linear-gradient(-45deg, #374151 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #374151 75%), 
                        linear-gradient(-45deg, transparent 75%, #374151 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }

        /* Scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Focus styles for accessibility */
        .image-checkbox:focus,
        .btn:focus,
        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .image-item {
                border: 2px solid;
            }
            
            .btn {
                border: 2px solid;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Print styles */
        @media print {
            .btn,
            .context-menu,
            .notification,
            #uploadModal,
            #editModal,
            #imageViewerModal {
                display: none !important;
            }
            
            .image-item {
                break-inside: avoid;
            }
        }
    </style>
</x-app-layout>

    

