<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="flex justify-between items-center">
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chỉnh sửa thông tin khách
                    hàng</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Cập nhật thông tin khách hàng
                    {{ $user->name }}</p>
            </div>
            <div class="flex items-center space-x-3 mb-4">
                <a href="{{ route('admin.users.customers.index') }}">
                    <button
                        class="btn cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Quay lại danh sách</span>
                    </button>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-r-lg dark:bg-green-900/20 dark:border-green-500 dark:text-green-300">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-3"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded-r-lg dark:bg-red-900/20 dark:border-red-500 dark:text-red-300">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl dark:border-gray-700">
            <form action="{{ route('admin.users.customers.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')

                <!-- Avatar Section -->
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            <i class="fas fa-camera mr-2 text-violet-600"></i>
                            Ảnh đại diện
                        </label>
                        <div class="flex items-center space-x-6 gap-6">
                            <!-- Avatar Preview -->
                            <div class="shrink-0">
                                <img id="avatar-preview"
                                    class="h-20 w-20 object-cover rounded-full border-4 border-gray-200 dark:border-gray-600 {{ $user->profile_photo_url ? '' : 'hidden' }}"
                                    src="{{ $user->profile_photo_url ?? '' }}" alt="Avatar preview">
                                <div id="avatar-placeholder"
                                    class="h-20 w-20 rounded-full border-4 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-700 {{ $user->profile_photo_url ? 'hidden' : '' }}">
                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                </div>
                            </div>
                            <!-- Upload Button -->
                            <div class="mt-3">
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                    class="hidden" onchange="previewImage(this)">
                                <label for="profile_photo"
                                    class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>
                                    Chọn ảnh
                                </label>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, GIF tối đa 2MB
                                </p>
                            </div>
                        </div>
                        @error('profile_photo')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mb-10">
                        <a href="{{ route('admin.users.customers.show', $user->id) }}"
                            class="px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-md hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white cursor-pointer">
                            <i class="fas fa-arrow-left fa-xs mr-1.5"></i> Quay lại
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Name, Email, Phone, Identity Code -->
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user mr-2 text-violet-600"></i>
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Nhập họ và tên">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope mr-2 text-violet-600"></i>
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $user->email) }}" 
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="example@email.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone mr-2 text-violet-600"></i>
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone"
                                value="{{ old('phone', $user->phone) }}" {{-- KHÔNG để  HTML --}}
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="0123456789">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                       
                    </div>

                    <!-- Right Column: Address -->
                    <div class="space-y-6">
                         <!-- Identity Code -->
                        <div>
                            <label for="identity_code"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-id-card mr-2 text-violet-600"></i>
                                Số CCCD / Hộ chiếu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="identity_code" name="identity_code"
                                value="{{ old('identity_code', $user->identity_code) }}" 
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Nhập số CCCD hoặc Hộ chiếu">
                            @error('identity_code')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-violet-600"></i>
                                Địa chỉ
                            </label>
                            <textarea id="address" name="address" rows="5"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Nhập địa chỉ đầy đủ...">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div></div>
                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white cursor-pointer">
                            <i class="fas fa-save mr-2"></i> Cập nhật khách hàng
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Preview avatar image
        function previewImage(input) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>