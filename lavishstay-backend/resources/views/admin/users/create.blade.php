<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="flex justify-between items-center">
            <div class="mb-8">

                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Thêm người dùng mới</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tạo tài khoản mới cho hệ thống quản lý khách sạn
                </p>
            </div>
            <div class="flex items-center space-x-3 mb-4">

                <a href="{{ route('admin.users') }}">
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
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl  dark:border-gray-700">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <!-- Avatar Section -->
                <!-- Avatar Section - Chỉ cần thay đổi phần này -->
                <div class="mb-8 flex justify-between items-cente">
                    <div class="r">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            <i class="fas fa-camera mr-2 text-violet-600"></i>
                            Ảnh đại diện
                        </label>
                        <div class="flex items-center space-x-6 gap-6">
                            <!-- Avatar Preview -->
                            <div class="shrink-0">
                                <img id="avatar-preview"
                                    class="h-20 w-20 object-cover rounded-full border-4 border-gray-200 dark:border-gray-600 hidden"
                                    alt="Avatar preview">
                                <div id="avatar-placeholder"
                                    class="h-20 w-20 rounded-full border-4 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-700">
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
                    <div
                        class="mt-4 p-4  bg-blue-50 dark:bg-blue-900/20 border border-gray-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-0.5"></i>
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                <h4 class="font-medium mb-1">Yêu cầu mật khẩu:</h4>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Tối thiểu 8 ký tự</li>
                                    <li>Ít nhất 1 chữ hoa và 1 chữ thường</li>
                                    <li>Ít nhất 1 số</li>
                                    <li>Ít nhất 1 ký tự đặc biệt</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ">
                    <!-- Họ và tên -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user mr-2 text-violet-600"></i>
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
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
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-2 text-violet-600"></i>
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                            placeholder="example@email.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Số điện thoại -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-violet-600"></i>
                            Số điện thoại
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                            placeholder="0123456789">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Vai trò -->

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa-solid fa-user-tag mr-2 text-violet-600"></i>
                            Vai trò <span class="text-red-500">*</span>
                        </label>
                        <!-- Thay thế phần select role trong form -->
                        <select id="role_id" name="role_id" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <option value="">Chọn vai trò</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ \App\Models\Role::getRoleLabel($role->name) }}
                                </option>
                            @endforeach
                        </select>


                    </div>

                    <!-- Địa chỉ -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-violet-600"></i>
                            Địa chỉ
                        </label>
                        <textarea id="address" name="address" rows="5"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                            placeholder="Nhập địa chỉ đầy đủ...">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Mật khẩu -->
                    <div class=" gap-6 mt-6">
                        <div class="w-full">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-violet-600"></i>
                                Mật khẩu <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="password" name="password" required
                                    class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                    placeholder="Nhập mật khẩu">
                                <button type="button" onclick="togglePassword('password', 'password-eye')"
                                    class="absolute inset-y-0 top-0 mt-3 right-0 pr-3 flex items-center">
                                    <i id="password-eye"
                                        class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="w-full mt-5">
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-violet-600"></i>
                                Xác nhận mật khẩu <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="password_confirmation" name="password_confirmation"
                                    required
                                    class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                    placeholder="Nhập lại mật khẩu">
                                <button type="button"
                                    onclick="togglePassword('password_confirmation', 'confirm-password-eye')"
                                    class="absolute inset-y-0 top-0 mt-3 right-0 pr-3 flex items-center">
                                    <i id="confirm-password-eye"
                                        class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Ghi chú mật khẩu -->

                    <div></div>
                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 mt-8 pt-6 ">

                        <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                            <i class="fas fa-plus mr-2"></i> Tạo người dùng
                        </button>

                    </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Xem trước ảnh avatar
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

        // Hiện/ẩn mật khẩu
        function togglePassword(inputId, eyeId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(eyeId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Validate form trước khi submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
                return false;
            }

            // Kiểm tra độ mạnh mật khẩu
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                e.preventDefault();
                alert('Mật khẩu không đáp ứng yêu cầu bảo mật!');
                return false;
            }
        });

        // Thay thế phần role descriptions trong script
        document.getElementById('role').addEventListener('change', function() {
            const roleDescriptions = {
                'guest': 'Khách có quyền hạn cơ bản nhất trong hệ thống',
                'receptionist': 'Lễ tân có thể xử lý booking và quản lý khách hàng',
                'manager': 'Quản lý có thể giám sát hoạt động và báo cáo',
                'admin': 'Quản trị viên có toàn quyền truy cập hệ thống'
            };

            // Bạn có thể thêm element để hiển thị mô tả này nếu cần
            console.log(roleDescriptions[this.value]);
        });
    </script>
</x-app-layout>
