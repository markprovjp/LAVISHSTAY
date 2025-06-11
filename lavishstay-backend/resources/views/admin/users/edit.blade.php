{{-- {{-- <x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <a href="{{ route('admin.users') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Quản lý người dùng</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Chỉnh sửa người dùng</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Title -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chỉnh sửa người dùng</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Cập nhật thông tin người dùng {{ $user->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn bg-blue-500 text-white hover:bg-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Xem chi tiết
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn bg-gray-500 text-white hover:bg-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-r-lg dark:bg-green-900/20 dark:border-green-500 dark:text-green-300">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Main Form -->
            <div class="col-span-full xl:col-span-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Thông tin cơ bản</h2>
                        
                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Họ và tên <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                                           placeholder="Nhập họ và tên"
                                           required>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                           placeholder="email@example.com"
                                           required>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Số điện thoại
                                    </label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                                           placeholder="0123456789">
                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mật khẩu mới <span class="text-gray-500 text-xs">(Để trống nếu không thay đổi)</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password" 
                                               name="password"
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                                               placeholder="Nhập mật khẩu mới">
                                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Xác nhận mật khẩu mới
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation"
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                                               placeholder="Nhập lại mật khẩu mới">
                                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Địa chỉ
                                    </label>
                                    <textarea id="address" 
                                              name="address" 
                                              rows="3"
                                              class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200 @error('address') border-red-500 @enderror"
                                              placeholder="Nhập địa chỉ">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('admin.users') }}" class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    Hủy bỏ
                                </a>
                                <button type="submit" class="px-8 py-3 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 transition-all duration-200">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Cập nhật người dùng
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-span-full xl:col-span-4">
                <!-- Current Avatar -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Avatar hiện tại</h3>
                        <div class="flex items-center justify-center">
                            @if($user->profile_photo_url)
                                <img class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600" 
                                     src="{{ $user->profile_photo_url }}" 
                                     alt="{{ $user->name }}" />
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center border-4 border-gray-200 dark:border-gray-600">
                                    <span class="text-white font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </div>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mt-3">
                            {{ $user->name }}
                        </p>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Vai trò và quyền hạn</h3>
                        
                        <div class="space-y-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Chọn vai trò <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="role" value="admin" class="text-red-600 focus:ring-red-500" {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Administrator</span>
                                            <span class="ml-2 px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 rounded-full">Toàn quyền</span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Có toàn quyền truy cập và quản lý hệ thống</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="role" value="manager" class="text-blue-600 focus:ring-blue-500" {{ old('role', $user->role) === 'manager' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Manager</span>
                                            <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300 rounded-full">Quản lý</span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Quản lý nhân viên và các hoạt động kinh doanh</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="role" value="staff" class="text-green-600 focus:ring-green-500" {{ old('role', $user->role) === 'staff' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Staff</span>
                                            <span class="ml-2 px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300 rounded-full">Nhân viên</span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Nhân viên thực hiện các công việc hàng ngày</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="role" value="customer" class="text-gray-600 focus:ring-gray-500" {{ old('role', $user->role) === 'customer' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Customer</span>
                                            <span class="ml-2 px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-full">Khách hàng</span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Khách hàng sử dụng dịch vụ</p>
                                    </div>
                                </label>
                            </div>
                            
                            @error('role')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- User Info Card -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Thông tin người dùng</h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-400 space-y-1">
                                <p><span class="font-medium">ID:</span> #{{ $user->id }}</p>
                                <p><span class="font-medium">Tham gia:</span> {{ $user->created_at->format('d/m/Y') }}</p>
                                <p><span class="font-medium">Cập nhật:</span> {{ $user->updated_at->format('d/m/Y') }}</p>
                                @if($user->email_verified_at)
                                    <p class="text-green-600 dark:text-green-400">✓ Email đã xác thực</p>
                                @else
                                    <p class="text-amber-600 dark:text-amber-400">⚠ Email chưa xác thực</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-amber-800 dark:text-amber-300">Lưu ý khi chỉnh sửa</h3>
                            <div class="mt-2 text-sm text-amber-700 dark:text-amber-400">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Để trống mật khẩu nếu không thay đổi</li>
                                    <li>Email phải là duy nhất trong hệ thống</li>
                                    <li>Thay đổi vai trò sẽ ảnh hưởng đến quyền truy cập</li>
                                    <li>Thông tin có dấu (*) là bắt buộc</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            // Only check password confirmation if password is provided
            if (password && password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
                return false;   
            }
            
            const role = document.querySelector('input[name="role"]:checked');
            if (!role) {
                e.preventDefault();
                alert('Vui lòng chọn vai trò cho người dùng!');
                return false;
            }
        });
    </script>
</x-app-layout> --}}
<!-- resources/views/users/edit.blade.php -->
<x-app-layout>
{{-- <div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Chỉnh sửa thông tin người dùng</h2>
            <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-sm font-medium rounded-md shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar -->
            <div class="flex items-center mb-6">
                @if ($user->profile_photo_url)
                    <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600 shadow mr-4" src="{{ $user->profile_photo_url }}" alt="Avatar">
                @else
                    <div class="h-20 w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-white font-bold text-xl shadow-sm mr-4">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                <input type="file" name="profile_photo" class="text-sm text-gray-700 dark:text-gray-300">
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mật khẩu mới</label>
                <input type="password" name="password" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Địa chỉ</label>
                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vai trò</label>
                <select name="role" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
                    <option value="guest" @selected($user->role === 'guest')>Guest</option>
                    <option value="receptionist" @selected($user->role === 'receptionist')>Receptionist</option>
                    <option value="manager" @selected($user->role === 'manager')>Manager</option>
                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                </select>
            </div>

            <!-- 2FA Status (view only) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xác thực hai lớp (2FA)</label>
                <input type="text" readonly value="{{ $user->two_factor_confirmed_at ? 'Đã bật' : 'Chưa bật' }}" class="w-full rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-sm font-medium rounded-md shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Hủy
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow">
                    <i class="fas fa-save mr-2"></i>Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div> --}}
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Chỉnh sửa thông tin người dùng</h2>
            <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-sm font-medium rounded-md shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar -->
            <div class="flex items-center mb-6">
                @if ($user->profile_photo_url)
                    <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600 shadow mr-4" src="{{ $user->profile_photo_url }}" alt="Avatar">
                @else
                    <div class="h-20 w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-white font-bold text-xl shadow-sm mr-4">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                <input type="file" name="profile_photo" class="text-sm text-gray-700 dark:text-gray-300">
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Password -->
            <div class="mb-4 relative">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mật khẩu mới</label>
                <input type="password" id="password" name="password" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm pr-10">
                <span onclick="togglePassword('password')" class="absolute right-3 top-9 cursor-pointer text-gray-500 dark:text-gray-400">
                    <i class="fas fa-eye" id="icon-password"></i>
                </span>
            </div>

            <div class="mb-4 relative">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm pr-10">
                <span onclick="togglePassword('password_confirmation')" class="absolute right-3 top-9 cursor-pointer text-gray-500 dark:text-gray-400">
                    <i class="fas fa-eye" id="icon-password_confirmation"></i>
                </span>
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Địa chỉ</label>
                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vai trò</label>
                <select name="role" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
                    <option value="guest" @selected($user->role === 'guest')>Guest</option>
                    <option value="receptionist" @selected($user->role === 'receptionist')>Receptionist</option>
                    <option value="manager" @selected($user->role === 'manager')>Manager</option>
                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                </select>
            </div>

            <!-- 2FA Status (view only) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xác thực hai lớp (2FA)</label>
                <input type="text" readonly value="{{ $user->two_factor_confirmed_at ? 'Đã bật' : 'Chưa bật' }}" class="w-full rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-sm font-medium rounded-md shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Hủy
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow">
                    <i class="fas fa-save mr-2"></i>Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</x-app-layout> 