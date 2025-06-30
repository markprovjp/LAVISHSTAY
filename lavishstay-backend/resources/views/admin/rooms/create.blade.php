<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.rooms') }}"
                                class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Tổng quan phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                    width="16" height="16">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}"
                                    class="ml-1 text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                    {{ $roomType->name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                    width="16" height="16">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">Thêm phòng mới</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                    Thêm phòng {{ $roomType->name }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Tạo phòng mới thuộc loại {{ $roomType->name }}
                </p>
            </div>
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}"
                    class="btn border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="w-4 h-4 fill-current shrink-0 mr-2" viewBox="0 0 16 16" width="16" height="16">
                        <path d="M6.6 13.4L5.2 12 8.4 8.8H1v-1.6h7.4L5.2 4 6.6 2.6 12.2 8.2 6.6 13.4z" />
                    </svg>
                    Hủy bỏ
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.rooms.store', $roomType->room_type_id) }}" method="POST"
            enctype="multipart/form-data" onsubmit="return confirmSubmit()">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <!-- Main Content -->
                <div class="xl:col-span-2 space-y-6">

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thông tin cơ bản</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Room Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tên phòng <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                                    placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                                    @error('name') border-red-500 @enderror"
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
                                    <select name="floor_id"
                                        class="border w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                        dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                        @error('floor_id') border-red-500 @enderror">
                                        @foreach (\App\Models\Floor::all() as $floor)
                                            <option value="{{ $floor->floor_id }}"
                                                {{ old('floor_id') == $floor->floor_id ? 'selected' : '' }}>
                                                {{ $floor->floor_name }} (Tầng {{ $floor->floor_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('floor_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bed Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Loại giường <span class="text-red-500">*</span>
                                    </label>
                                    <select name="bed_type_fixed"
                                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                                        dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                                    w-full @error('bed_type_fixed') border-red-500 @enderror">
                                        @foreach (\App\Models\BedType::active()->get() as $bedType)
                                            <option value="{{ $bedType->id }}"
                                                {{ old('bed_type_fixed') == $bedType->id ? 'selected' : '' }}>
                                                {{ $bedType->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bed_type_fixed')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Trạng thái <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status"
                                        class="border w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                                     dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('status') border-red-500 @enderror">
                                        @foreach (['available' => 'Trống', 'occupied' => 'Đang sử dụng', 'maintenance' => 'Đang bảo trì', 'cleaning' => 'Đang dọn dẹp'] as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('status', 'available') == $value ? 'selected' : '' }}>
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
                                    <input type="number" name="base_price_vnd" value="{{ old('base_price_vnd') }}"
                                        min="0" step="1000"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                                        placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                                        @error('base_price_vnd') border-red-500 @enderror"
                                        placeholder="VD: 2500000">
                                    @error('base_price_vnd')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mô tả phòng
                                    </label>
                                    <textarea name="description" rows="4" class="form-textarea w-full @error('description') border-red-500 @enderror"
                                        placeholder="Mô tả chi tiết về phòng, tiện nghi, đặc điểm nổi bật...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Hình ảnh phòng</h2>
                        </div>
                        <div class="p-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Hình ảnh phòng
                                </label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-violet-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex my-2 text-sm items-center text-gray-600 dark:text-gray-400">
                                            <label for="image"
                                                class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-violet-600 hover:text-violet-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-violet-500">
                                                <span
                                                    class="btn bg-sky-700 me-3 dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Tải
                                                    lên hình ảnh</span>
                                                <input id="image" name="image" type="file" class="sr-only"
                                                    accept="image/*" onchange="previewImage(this)">
                                            </label>
                                            <p class="pl-1">hoặc kéo thả</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div id="image-preview" class="mt-4 hidden">
                                    <div class="relative inline-block">
                                        <img id="image-display" src="" alt="Preview"
                                            class="h-32 w-48 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                        <button type="button" onclick="removeImage()"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                            ×
                                        </button>
                                    </div>
                                </div>

                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-6">
                    <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                        <svg class="w-4 h-4 fill-current shrink-0 mr-2" viewBox="0 0 16 16" width="16px"
                            height="16px">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        Thêm phòng
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

        function confirmSubmit() {
            return confirm('Bạn có chắc muốn thêm phòng mới?');
        }

        // Auto-generate room name based on room type and floor
        document.querySelector('select[name="floor_id"]').addEventListener('change', function() {
            const floorNumber = this.options[this.selectedIndex].text.match(/\d+/)[0]; // Lấy số tầng từ text
            const roomTypeName = '{{ $roomType->name }}';
            const nameInput = document.querySelector('input[name="name"]');

            if (floorNumber && !nameInput.value) {
                const roomNumber = floorNumber.padStart(2, '0') + '01';
                nameInput.value = `${roomTypeName} ${roomNumber}`;
            }
        });

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
    </script>
</x-app-layout>
