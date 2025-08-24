<x-app-layout>
    <!-- Include Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex justify-between items-center">
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Thông tin nhân viên</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Xem chi tiết thông tin của {{ $user->name }}
                </p>
            </div>
            <div class="flex items-center space-x-3 mb-4">
                <a href="{{ route('admin.users.staffs.index') }}">
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
            <div id="notification"
                class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-green-700">Thành công!</h3>
                    <div class="text-sm text-green-600">{{ session('success') }}</div>
                </div>
                <button onclick="closeNotification()"
                    class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="notification"
                class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">{{ session('error') }}</div>
                </div>
                <button onclick="closeNotification()" class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Tabs Menu -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-4 -mb-px" aria-label="Tabs">
                <button data-tab="personal-info"
                    class="tab-button py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-violet-600 hover:border-violet-600 dark:hover:text-violet-400 dark:hover:border-violet-400 focus:outline-none active-tab cursor-pointer">
                    Thông tin cá nhân
                </button>
                <button data-tab="security"
                    class="tab-button py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-violet-600 hover:border-violet-600 dark:hover:text-violet-400 dark:hover:border-violet-400 focus:outline-none cursor-pointer">
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
                    <div class="lg:col-span-12 w-full max-w-none">
                        <div
                            class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-7 h-7 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user fa-xs text-violet-600 dark:text-violet-400"></i>
                                    </div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Thông tin cá
                                        nhân</h3>
                                </div>
                                <a href="{{ route('admin.users.staffs.edit', $user->id) }}">
                                    <button
                                        class="px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-md hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white cursor-pointer">
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
                                            <div class="flex-shrink-0 mb-4">
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
                                                @php $role = $user->roles->first()?->name; @endphp

                                                <span
                                                    class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 ml-3">
                                                    @if ($role === 'system_admin')
                                                        <i class="fas fa-shield-alt fa-xs mr-1"></i>
                                                        {{ \App\Models\Role::getRoleLabel('system_admin') }}
                                                    @elseif ($role === 'guest')
                                                        <i class="fas fa-user fa-xs mr-1"></i>
                                                        {{ \App\Models\Role::getRoleLabel('guest') }}
                                                    @elseif ($role === 'receptionist')
                                                        <i class="fas fa-headset fa-xs mr-1"></i>
                                                        {{ \App\Models\Role::getRoleLabel('receptionist') }}
                                                    @elseif ($role === 'hotel_manager')
                                                        <i class="fas fa-user-tie fa-xs mr-1"></i>
                                                        {{ \App\Models\Role::getRoleLabel('hotel_manager') }}
                                                    @elseif ($role === 'dept_manager')
                                                        <i class="fas fa-user-tie fa-xs mr-1"></i>
                                                        {{ \App\Models\Role::getRoleLabel('dept_manager') }}
                                                    @elseif ($role === 'housekeeping')
                                                        <i class="fas fa-broom fa-xs mr-1"></i>
                                                        {{ \App\Models\Role::getRoleLabel('housekeeping') }}
                                                    @elseif ($role === 'marketing')
                                                        <i class="fas fa-bullhorn fa-xs mr-1"></i>
                                                        {{ \App\Models\Role::getRoleLabel('marketing') }}
                                                    @elseif ($role === 'finance')
                                                        <i class="fas fa-calculator fa-xs mr-1"></i>
                                                        {{ \App\Models\Role::getRoleLabel('finance') }}
                                                    @else
                                                        <i class="fas fa-user fa-xs mr-1"></i> {{ ucfirst($role) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Họ
                                            và tên</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-user fa-xs text-gray-400 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900 dark:text-gray-100">{{ $user->name ?: 'Chưa cung cấp' }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-envelope fa-xs text-gray-400 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email ?: 'Chưa cung cấp' }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Số
                                            điện thoại</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-phone fa-xs text-gray-400 mr-2"></i>
                                            <span
                                                class="text-sm text-gray-900 dark:text-gray-100">{{ $user->phone ?: 'Chưa cung cấp' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Số CCCD / Hộ chiếu
                                        </label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-id-card fa-xs text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $user->identity_code ?? 'Chưa cung cấp' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày
                                            tham gia</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-calendar-alt fa-xs text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ optional($user->created_at)->format('d/m/Y H:i') ?? 'Không xác định' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cập
                                            nhật lần cuối</label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <i class="fas fa-clock fa-xs text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ optional($user->updated_at)->format('d/m/Y H:i') ?? 'Không xác định' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Địa
                                    chỉ</label>
                                <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <i class="fas fa-map-marker-alt fa-xs text-gray-400 mr-2 mt-2"></i>
                                    <span
                                        class="text-sm text-gray-900 dark:text-gray-100">{{ $user->address ?? 'Chưa cung cấp' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div id="security" class="tab-content hidden">
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-7 h-7 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-lock fa-xs text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Thông tin bảo mật</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password Change Form -->
                        <div class="space-y-4">
                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-md">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Đặt lại mật khẩu nhân viên
                                </h4>

                                @if (session('new_password'))
                                    <div class="text-green-600 dark:text-green-400 text-sm mb-3">
                                        <i class="fas fa-key mr-1"></i>
                                        Mật khẩu mới đã được gửi tới email:
                                        <span class="font-semibold">{{ $user->email }}</span>
                                    </div>
                                @endif

                                <form action="{{ route('admin.users.staffs.reset-password', $user->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn đặt lại mật khẩu không?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="btn bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md px-4 py-2 cursor-pointer">
                                        <i class="fas fa-redo mr-1"></i> Đặt lại mật khẩu và gửi về email
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation khi hiển thị thông báo
        document.querySelectorAll('#notification').forEach(notification => {
            notification.classList.add('translate-x-0', 'opacity-100');
            notification.classList.remove('translate-x-full', 'opacity-0');

            // Tự động ẩn sau 5 giây
            setTimeout(() => {
                closeNotification(notification);
            }, 5000);
        });

        function closeNotification(notification) {
            notification.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }

        // Tab switching functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active state from all tabs
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('text-violet-600', 'border-violet-600',
                        'dark:text-violet-400',
                        'dark:border-violet-400');
                    btn.classList.add('text-gray-500', 'border-transparent',
                        'dark:text-gray-400');
                    btn.classList.remove('active-tab');
                });

                // Add active state to clicked tab
                button.classList.add('text-violet-600', 'border-violet-600', 'dark:text-violet-400',
                    'dark:border-violet-400', 'active-tab');
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
    </script>
</x-app-layout>