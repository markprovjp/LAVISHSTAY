<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.rooms') }}" class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Tổng quan phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}" class="ml-1 text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                    {{ $roomType->name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.rooms.show', $room->room_id) }}" class="ml-1 text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                    {{ $room->name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">Chỉnh sửa</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                    Chỉnh sửa phòng {{ $room->name }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Cập nhật thông tin phòng {{ $roomType->name }}
                </p>
            </div>
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.rooms.show', $room->room_id) }}" 
                   class="btn border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="w-4 h-4 fill-current shrink-0 mr-2" viewBox="0 0 16 16" width="16" height="16">
                        <path d="M6.6 13.4L5.2 12 8.4 8.8H1v-1.6h7.4L5.2 4 6.6 2.6 12.2 8.2 6.6 13.4z"/>
                    </svg>
                    Hủy bỏ
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.rooms.update', $room->room_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-8">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thông tin cơ bản</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hotel -->
                        @if($hotels->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Khách sạn <span class="text-red-500">*</span>
                            </label>
                            <select name="hotel_id" class="form-select w-full @error('hotel_id') border-red-500 @enderror">
                                <option value="">Chọn khách sạn</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->hotel_id }}" {{ (old('hotel_id', $room->hotel_id) == $hotel->hotel_id) ? 'selected' : '' }}>
                                        {{ $hotel->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <!-- Room Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tên phòng <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $room->name) }}" 
                                   class="form-input w-full @error('name') border-red-500 @enderror"
                                   placeholder="VD: {{ $roomType->name }} 101">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Floor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tầng <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="floor" value="{{ old('floor', $room->floor) }}" min="1" max="50"
                                   class="form-input w-full @error('floor') border-red-500 @enderror">
                            @error('floor')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Trạng thái <span class="text-red-500">*</span>
                            </label>
                            <select name="status" class="form-select w-full @error('status') border-red-500 @enderror">
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ (old('status', $room->status) == $value) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                                                <!-- Base Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Giá cơ bản (VND/đêm) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="base_price_vnd" value="{{ old('base_price_vnd', $room->base_price_vnd) }}" min="0" step="1000"
                                   class="form-input w-full @error('base_price_vnd') border-red-500 @enderror"
                                   placeholder="VD: 2500000">
                            @error('base_price_vnd')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Size -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Diện tích (m²) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="size" value="{{ old('size', $room->size) }}" min="1"
                                   class="form-input w-full @error('size') border-red-500 @enderror"
                                   placeholder="VD: 45">
                            @error('size')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Guests -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Số khách tối đa <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="max_guests" value="{{ old('max_guests', $room->max_guests) }}" min="1" max="20"
                                   class="form-input w-full @error('max_guests') border-red-500 @enderror">
                            @error('max_guests')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- View -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Hướng nhìn
                            </label>
                            <select name="view" class="form-select w-full @error('view') border-red-500 @enderror">
                                <option value="">Chọn hướng nhìn</option>
                                @foreach($viewOptions as $view)
                                    <option value="{{ $view }}" {{ (old('view', $room->view) == $view) ? 'selected' : '' }}>
                                        {{ $view }}
                                    </option>
                                @endforeach
                            </select>
                            @error('view')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Đánh giá (0-5 sao)
                            </label>
                            <input type="number" name="rating" value="{{ old('rating', $room->rating) }}" min="0" max="5" step="0.1"
                                   class="form-input w-full @error('rating') border-red-500 @enderror"
                                   placeholder="VD: 4.5">
                            @error('rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lavish Plus Discount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Giảm giá Lavish Plus (%)
                            </label>
                            <input type="number" name="lavish_plus_discount" value="{{ old('lavish_plus_discount', $room->lavish_plus_discount) }}" min="0" max="100" step="0.01"
                                   class="form-input w-full @error('lavish_plus_discount') border-red-500 @enderror"
                                   placeholder="VD: 10.5">
                            @error('lavish_plus_discount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Mô tả phòng
                        </label>
                        <textarea name="description" rows="4" 
                                  class="form-textarea w-full @error('description') border-red-500 @enderror"
                                  placeholder="Mô tả chi tiết về phòng, tiện nghi, đặc điểm nổi bật...">{{ old('description', $room->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Image Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-8">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Hình ảnh phòng</h2>
                </div>
                
                <div class="p-6">
                    <!-- Current Image -->
                    @if($room->image)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Hình ảnh hiện tại
                            </label>
                            <div class="relative inline-block">
                                <img width="200px" src="{{ $room->image }}" alt="{{ $room->name }}" 
                                     class="h-24 max-w-48 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                
                            </div>
                        </div>
                    @endif

                    <!-- New Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $room->image ? 'Thay đổi hình ảnh' : 'Thêm hình ảnh phòng' }}
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-violet-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex items-center py-3 text-sm text-gray-600 dark:text-gray-400">
                                    <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-violet-600 hover:text-violet-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-violet-500">
                                        <span class="btn bg-sky-700 me-3 dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">{{ $room->image ? 'Chọn ảnh mới' : 'Tải lên hình ảnh' }}</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                    </label>
                                    <p class="pl-1">hoặc kéo thả</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                                @if($room->image)
                                    <p class="text-xs text-gray-400">Để trống nếu không muốn thay đổi</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- New Image Preview -->
                        <div id="image-preview" class="mt-4 hidden">
                            <div class="relative inline-block">
                                <img id="image-display" src="" alt="Preview" class="h-32 w-48 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                    ×
                                </button>
                                <div class="absolute bottom-2 left-2 bg-green-500 text-white px-2 py-1 rounded text-xs">
                                    Ảnh mới
                                </div>
                            </div>
                        </div>
                        
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4">
                
                <button type="submit" class="btn cursor-pointer bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="w-4 h-4 fill-current shrink-0 mr-2" viewBox="0 0 16 16" width="16" height="16">
                        <path d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z"/>
                    </svg>
                    Cập nhật phòng
                </button>
                
            </div>
        </form>

    </div>

    <!-- JavaScript -->
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-display').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('image-display').src = '';
        }

        // Format price input
        document.querySelector('input[name="base_price_vnd"]').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value) {
                this.value = parseInt(value);
            }
        });

        // Drag and drop functionality
        const dropZone = document.querySelector('.border-dashed');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-violet-400', 'bg-violet-50', 'dark:bg-violet-900/10');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-violet-400', 'bg-violet-50', 'dark:bg-violet-900/10');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                document.getElementById('image').files = files;
                previewImage(document.getElementById('image'));
            }
        }

               // Confirm before submit if there are changes
        let originalData = {};
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        // Store original values
        inputs.forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                originalData[input.name] = input.checked;
            } else {
                originalData[input.name] = input.value;
            }
        });

        // Check for changes before leaving
        window.addEventListener('beforeunload', function(e) {
            let hasChanges = false;
            inputs.forEach(input => {
                let currentValue;
                if (input.type === 'checkbox' || input.type === 'radio') {
                    currentValue = input.checked;
                } else {
                    currentValue = input.value;
                }
                
                if (originalData[input.name] !== currentValue) {
                    hasChanges = true;
                }
            });

            if (hasChanges) {
                e.preventDefault();
                e.returnValue = 'Bạn có thay đổi chưa được lưu. Bạn có chắc muốn rời khỏi trang?';
            }
        });

        // Don't show warning when submitting form
        form.addEventListener('submit', function() {
            window.removeEventListener('beforeunload', arguments.callee);
        });

        // Auto-save draft (optional)
        let autoSaveTimer;
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    // Save to localStorage as draft
                    const formData = new FormData(form);
                    const draftData = {};
                    for (let [key, value] of formData.entries()) {
                        draftData[key] = value;
                    }
                    localStorage.setItem('room_edit_draft_{{ $room->room_id }}', JSON.stringify(draftData));
                }, 2000);
            });
        });

        // Load draft on page load
        const savedDraft = localStorage.getItem('room_edit_draft_{{ $room->room_id }}');
        if (savedDraft) {
            const draftData = JSON.parse(savedDraft);
            const loadDraft = confirm('Tìm thấy bản nháp đã lưu. Bạn có muốn khôi phục không?');
            
            if (loadDraft) {
                Object.keys(draftData).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input && input.type !== 'file') {
                        input.value = draftData[key];
                    }
                });
            }
        }

        // Clear draft after successful submit
        form.addEventListener('submit', function() {
            localStorage.removeItem('room_edit_draft_{{ $room->room_id }}');
        });
    </script>

</x-app-layout>


