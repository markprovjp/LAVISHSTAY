<x-app-layout>
    <!-- Include Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="flex justify-between items-center">
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Thông tin người dùng</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Xem chi tiết thông tin của {{ $user->name }}</p>
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

        <!-- Tabs Menu -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-4 -mb-px" aria-label="Tabs">
                <button data-tab="personal-info"
                    class="tab-button py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-blue-600 hover:border-blue-600 dark:hover:text-blue-400 dark:hover:border-blue-400 focus:outline-none active-tab">
                    Thông tin cá nhân
                </button>
                <button data-tab="security"
                    class="tab-button py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-blue-600 hover:border-blue-600 dark:hover:text-blue-400 dark:hover:border-blue-400 focus:outline-none">
                    Bảo mật
                </button>
            </nav>
        </div>

        
        <!-- Tab Content -->
        <div class="mt-6">
            <!-- Personal Info Tab -->
            <div id="personal-info" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Personal Information -->
                    <div class="lg:col-span-8">
                        <div
                            class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                            <div class="flex items-end justify-between mb-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-7 h-7 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user fa-xs text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Thông tin cá nhân</h3>
                                </div>
                                <a href="{{ route('admin.users.edit', $user->id) }}">
                                    <button
                                        class="px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-md hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                                        <i class="fas fa-edit fa-xs mr-1.5"></i> Chỉnh sửa
                                    </button>
                                </a>
                            </div>

                            

                            <div class="lg:col-span-4">
                                <div class="bg-white dark:bg-gray-800">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                        <!-- Left: Avatar and Name -->
                                        <div class="flex items-start space-x-4">
                                            <!-- Avatar -->
                                            <div class="flex-shrink-0">
                                                @if ($user->profile_photo_url)
                                                    <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm"
                                                        src="{{ $user->profile_photo_url }}"
                                                        alt="{{ $user->name }}" />
                                                @else
                                                    <div
                                                        class="w-20 h-20 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                                                        <span
                                                            class="text-white font-bold text-lg">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Name and Role -->
                                            <div class="flex-1 min-w-0">
                                                <h2
                                                    class="text-lg font-bold text-gray-900 dark:text-gray-100 pl-3 pt-6">
                                                    {{ $user->name }}
                                                </h2>
                                                <span
                                                    class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 ml-3">
                                                    @if ($user->role === 'admin')
                                                        <i class="fas fa-shield-alt fa-xs mr-1"></i> Administrator
                                                    @elseif($user->role === 'manager')
                                                        <i class="fas fa-user-tie fa-xs mr-1"></i> Manager
                                                    @elseif($user->role === 'staff')
                                                        <i class="fas fa-users fa-xs mr-1"></i> Staff
                                                    @elseif($user->role === 'receptionist')
                                                        <i class="fas fa-headset fa-xs mr-1"></i> Receptionist
                                                    @elseif($user->role === 'guest')
                                                        <i class="fas fa-user fa-xs mr-1"></i> Guest
                                                    @else
                                                        <i class="fas fa-user fa-xs mr-1"></i> Customer
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Right: Email Verification and 2FA Status -->
                                        <div class="space-y-3 text-center md:text-right">
                                            <!-- Email Verification Status -->
                                            <div>
                                                @if ($user->email_verified_at)
                                                    <div
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        <i
                                                            class="fas fa-check-circle fa-xs mr-1 text-green-600 dark:text-green-400"></i>
                                                        <span class="text-green-600 dark:text-green-400">Email đã xác thực</span>
                                                    </div>
                                                @else
                                                    <div
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                        <i
                                                            class="fas fa-exclamation-circle fa-xs mr-1 text-amber-600 dark:text-amber-400"></i>
                                                        <span class="text-amber-600 dark:text-amber-400">Email chưa xác thực</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- 2FA Status -->
                                            <div>
                                                @if ($user->two_factor_confirmed_at)
                                                    <div
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                        <i
                                                            class="fas fa-shield-alt fa-xs mr-1 text-blue-600 dark:text-blue-400"></i>
                                                        <span class="text-blue-600 dark:text-blue-400">2FA đã bật</span>
                                                    </div>
                                                @else
                                                    <div
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        <i
                                                            class="fas fa-shield fa-xs mr-1 text-gray-600 dark:text-gray-400"></i>
                                                        <span class="text-gray-600 dark:text-gray-400">2FA đã tắt</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Quick Stats (Activity Days) -->
                                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700 mt-4">
                                                @php
                                                    $timezone = 'Asia/Ho_Chi_Minh';
                                                    $created = $user->created_at ? $user->created_at->setTimezone($timezone) : null;
                                                    $now = now()->setTimezone($timezone);
                                                    $days = 0;
                                                    $hours = 0;
                                                    $minutes = 0;

                                                    if ($created && $created <= $now) {
                                                        $diffInSeconds = $created->diffInSeconds($now);
                                                        $days = intdiv($diffInSeconds, 86400);
                                                        $hours = intdiv($diffInSeconds % 86400, 3600);
                                                        $minutes = intdiv($diffInSeconds % 3600, 60);
                                                    }
                                                @endphp
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Hoạt động từ
                                                    @if ($days >= 1)
                                                        {{ $days }} ngày trước
                                                    @elseif ($hours >= 1)
                                                        {{ $hours }} giờ trước
                                                    @else
                                                        {{ $minutes }} phút trước
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Họ và tên</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-user fa-xs text-gray-400 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-envelope fa-xs text-gray-400 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện thoại</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-phone fa-xs text-gray-400 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900 dark:text-gray-100">{{ $user->phone ?: 'Chưa cập nhật' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Vai trò</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-user-shield fa-xs text-gray-400 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900 dark:text-gray-100 capitalize">{{ $user->role }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày tham gia</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-calendar-alt fa-xs text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ optional($user->created_at)->format('d/m/Y H:i') ?? 'Không xác định' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cập nhật lần cuối</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-clock fa-xs text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ optional($user->updated_at)->format('d/m/Y H:i') ?? 'Không xác định' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($user->address)
                                <div class="mt-4">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Địa chỉ</label>
                                    <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <i class="fas fa-map-marker-alt fa-xs text-gray-400 mr-2 mt-0.5"></i>
                                        <span
                                            class="text-sm text-gray-900 dark:text-gray-100">{{ $user->address }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div id="security" class="tab-content hidden">
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
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

                    <div class="flex items-center mb-4">
                        <div
                            class="w-7 h-7 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-lock fa-xs text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Thông tin bảo mật</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Xác thực email</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if ($user->email_verified_at)
                                                Đã xác thực vào {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                            @else
                                                Email chưa được xác thực
                                            @endif
                                        </p>
                                    </div>
                                    @if ($user->email_verified_at)
                                        <div
                                            class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check fa-xs text-green-600 dark:text-green-400"></i>
                                        </div>
                                    @else
                                        <div
                                            class="w-6 h-6 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                                            <i
                                                class="fas fa-exclamation-circle fa-xs text-amber-600 dark:text-amber-400"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Xác thực 2 bước</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if ($user->two_factor_confirmed_at)
                                                Đã kích hoạt vào {{ $user->two_factor_confirmed_at->format('d/m/Y H:i') }}
                                            @else
                                                Chưa kích hoạt xác thực 2 bước
                                            @endif
                                        </p>
                                    </div>
                                    @if ($user->two_factor_confirmed_at)
                                        <div
                                            class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check fa-xs text-green-600 dark:text-green-400"></i>
                                        </div>
                                    @else
                                        <div
                                            class="w-6 h-6 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                                            <i
                                                class="fas fa-exclamation-circle fa-xs text-amber-600 dark:text-amber-400"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Password Change Form -->
                        <div class="space-y-4">
                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-md">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Đổi mật khẩu</h4>
                                <form action="{{ route('admin.users.change-password', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!-- Current Password -->
                                    <div class="mb-4">
                                        <label for="current_password"
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fas fa-lock mr-2 text-violet-600"></i>
                                            Mật khẩu hiện tại <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="password" id="current_password" name="current_password"
                                                required
                                                class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                                placeholder="Nhập mật khẩu hiện tại">
                                            <button type="button"
                                                onclick="togglePassword('current_password', 'current-password-eye')"
                                                class="absolute inset-y-0 top-0 mt-3 right-0 pr-3 flex items-center">
                                                <i id="current-password-eye"
                                                    class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- New Password -->
                                    <div class="mb-4">
                                        <label for="password"
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fas fa-lock mr-2 text-violet-600"></i>
                                            Mật khẩu mới <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="password" id="password" name="password" required
                                                class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                                placeholder="Nhập mật khẩu mới">
                                            <button type="button"
                                                onclick="togglePassword('password', 'password-eye')"
                                                class="absolute inset-y-0 top-0 mt-3 right-0 pr-3 flex items-center">
                                                <i id="password-eye"
                                                    class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer"></i>
                                            </button>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.
                                        </p>
                                    </div>

                                    <!-- Confirm New Password -->
                                    <div class="mb-4">
                                        <label for="password_confirmation"
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fas fa-lock mr-2 text-violet-600"></i>
                                            Xác nhận mật khẩu mới <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="password" id="password_confirmation"
                                                name="password_confirmation" required
                                                class="block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                                placeholder="Nhập lại mật khẩu mới">
                                            <button type="button"
                                                onclick="togglePassword('password_confirmation', 'confirm-password-eye')"
                                                class="absolute inset-y-0 top-0 mt-3 right-0 pr-3 flex items-center">
                                                <i id="confirm-password-eye"
                                                    class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="btn bg-violet-500 hover:bg-violet-600 text-white text-xs font-medium rounded-md px-4 py-2">
                                            <i class="fas fa-save mr-2"></i> Đổi mật khẩu
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active state from all tabs
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('text-blue-600', 'border-blue-600',
                        'dark:text-blue-400',
                        'dark:border-blue-400');
                    btn.classList.add('text-gray-500', 'border-transparent',
                        'dark:text-gray-400');
                    btn.classList.remove('active-tab');
                });

                // Add active state to clicked tab
                button.classList.add('text-blue-600', 'border-blue-600', 'dark:text-blue-400',
                    'dark:border-blue-400', 'active-tab');
                button.classList.remove('text-gray-500', 'border-transparent', 'dark:text-gray-400');

                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Show selected tab content
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Set default active tab
        document.querySelector('[data-tab="personal-info"]').click();

        // Toggle password visibility
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
        

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
                return false;
            }

            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                e.preventDefault();
                alert('Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt!');
                return false;
            }
        });
    </script>
</x-app-layout>