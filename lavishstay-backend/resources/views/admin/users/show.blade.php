<x-app-layout>
    <!-- Include Font Awesome CDN (ensure this is added in your layout or head) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-6xl mx-auto">

        <!-- Page Header -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-xs font-medium text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white transition-colors duration-200">
                            <i class="fas fa-home fa-xs mr-1.5 text-gray-600 dark:text-gray-300"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right fa-xs text-gray-400 mx-1.5"></i>
                            <a href="{{ route('admin.users') }}"
                                class="text-xs font-medium text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white transition-colors duration-200">Quản
                                lý người dùng</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right fa-xs text-gray-400 mx-1.5"></i>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Chi tiết người
                                dùng</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Title and Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Thông tin người dùng</h1>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Xem chi tiết thông tin của
                        {{ $user->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-edit fa-xs mr-1.5"></i>
                        Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.users') }}"
                        class="inline-flex items-center px-3 py-1.5 bg-gray-500 text-white text-xs font-medium rounded-md hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left fa-xs mr-1.5"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Profile Card -->

            {{-- <div class="lg:col-span-4">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <!-- Avatar and Basic Info -->
                    <div class="text-center">
                        @if ($user->profile_photo_url)
                            <img class="w-20 h-20 mx-auto rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm"
                                src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                        @else
                            <div
                                class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                                <span class="text-white font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                        @endif

                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-3">{{ $user->name }}</h2>

                        <!-- Role Badge -->
                        <div class="mt-3">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if ($user->role === 'admin') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                @elseif($user->role === 'manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                @elseif($user->role === 'staff') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                @if ($user->role === 'admin')
                                    <i class="fas fa-shield-alt fa-xs mr-1"></i>
                                    Administrator
                                @elseif($user->role === 'manager')
                                    <i class="fas fa-user-tie fa-xs mr-1"></i>
                                    Manager
                                @elseif($user->role === 'staff')
                                    <i class="fas fa-users fa-xs mr-1"></i>
                                    Staff
                                @else
                                    <i class="fas fa-user fa-xs mr-1"></i>
                                    Customer
                                @endif
                            </span>
                        </div>

                        <!-- Email Verification Status -->
                        <div class="mt-3">
                            @if ($user->email_verified_at)
                                <div
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    <i class="fas fa-check-circle fa-xs mr-1 text-green-600 dark:text-green-400"></i>
                                   <span class="text-green-600 dark:text-green-400">Email đã xác thực</span>
                                </div>
                            @else
                                <div
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                    <i class="fas fa-exclamation-circle fa-xs mr-1 text-amber-600 dark:text-amber-400"></i>
                                    <span class="text-amber-600 dark:text-amber-400">Email chưa xác thực</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                    {{ optional($user->created_at)->diffInDays(now()) ?? 0 }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Ngày hoạt động</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                    @if ($user->two_factor_confirmed_at)
                                        <span class="text-green-600">Bật</span>
                                    @else
                                        <span class="text-gray-500">Tắt</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">2FA</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="lg:col-span-4">
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <!-- Left: Avatar and Name -->
                        <div class="text-center md:text-left">
                            @if ($user->profile_photo_url)
                                <img class="w-20 h-20 mx-auto md:mx-0 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm"
                                    src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                            @else
                                <div
                                    class="w-24 h-24 mx-auto md:mx-0 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                                    <span
                                        class="text-white font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </div>
                            @endif

                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-3">{{ $user->name }}</h2>

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if ($user->role === 'admin') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                @elseif($user->role === 'manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                @elseif($user->role === 'staff') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                @if ($user->role === 'admin')
                                    <i class="fas fa-shield-alt fa-xs mr-1"></i> Administrator
                                @elseif($user->role === 'manager')
                                    <i class="fas fa-user-tie fa-xs mr-1"></i> Manager
                                @elseif($user->role === 'staff')
                                    <i class="fas fa-users fa-xs mr-1"></i> Staff
                                @else
                                    <i class="fas fa-user fa-xs mr-1"></i> Customer
                                @endif
                            </span>
                        </div>

                        <!-- Right: Role, Verification, Stats -->
                        <div class="space-y-3 text-center md:text-left">
                            <!-- Role Badge -->


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

                            <!-- Quick Stats -->
                            <div
                                class="grid grid-cols-2 gap-4 text-center md:text-left pt-2 border-t border-gray-200 dark:border-gray-700 mt-4">
                                <div>
                                    @php
                                        $timezone = 'Asia/Ho_Chi_Minh';
                                        $created = $user->created_at ? $user->created_at->setTimezone($timezone) : null;
                                        $now = now()->setTimezone($timezone);

                                        if ($created && $created <= $now) {
                                            $diffInSeconds = $created->diffInSeconds($now);
                                            $days = intdiv($diffInSeconds, 86400);
                                            $hours = intdiv($diffInSeconds % 86400, 3600);
                                            $minutes = intdiv($diffInSeconds % 3600, 60);
                                        } else {
                                            $days = 0;
                                            $hours = 0;
                                            $minutes = 0;
                                        }
                                    @endphp

                                    <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        @if ($days >= 1)
                                            {{ $days }} ngày
                                        @else
                                            @if ($hours >= 1)
                                                {{ $hours }} giờ
                                            @else
                                                {{ $minutes }} phút
                                            @endif
                                        @endif
                                    </div>


                                    {{-- <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ optional($user->created_at)->diffInDays(now()) ?? 0 }}
                                    </div> --}}
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Ngày hoạt động</div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        @if ($user->two_factor_confirmed_at)
                                            <span class="text-green-600">Bật</span>
                                        @else
                                            <span class="text-gray-500">Tắt</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">2FA</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Detailed Information -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Personal Information -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-7 h-7 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user fa-xs text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Thông tin cá nhân</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Họ và
                                    tên</label>
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <i class="fas fa-user fa-xs text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</span>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <i class="fas fa-envelope fa-xs text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện
                                    thoại</label>
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <i class="fas fa-phone fa-xs text-gray-400 mr-2"></i>
                                    <span
                                        class="text-sm text-gray-900 dark:text-gray-100">{{ $user->phone ?: 'Chưa cập nhật' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Vai
                                    trò</label>
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <i class="fas fa-user-shield fa-xs text-gray-400 mr-2"></i>
                                    <span
                                        class="text-sm text-gray-900 dark:text-gray-100 capitalize">{{ $user->role }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày tham
                                    gia</label>
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <i class="fas fa-calendar-alt fa-xs text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ optional($user->created_at)->format('d/m/Y H:i') ?? 'Không xác định' }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cập nhật
                                    lần cuối</label>
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
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Địa
                                chỉ</label>
                            <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <i class="fas fa-map-marker-alt fa-xs text-gray-400 mr-2 mt-0.5"></i>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $user->address }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Security Information -->
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
                        <div class="space-y-4">
                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Xác thực email
                                        </h4>
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
                                            {{-- <i class="fas fa-exclamation fa-xs text-amber-600 dark:text-amber-400"></i> --}}
                                            <i class="fas fa-exclamation-circle fa-xs mr-1"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Xác thực 2
                                            bước</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if ($user->two_factor_confirmed_at)
                                                Đã kích hoạt vào
                                                {{ $user->two_factor_confirmed_at->format('d/m/Y H:i') }}
                                            @else
                                                Chưa kích hoạt xác thực 2 bước
                                            @endif
                                        </p>
                                    </div>
                                    @if ($user->two_factor_confirmed_at)
                                        <div
                                            class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check fa-xs text-green-600 dark:text-green-400"></i>
                                            {{-- <i class="fas fa-exclamation-circle fa-xs mr-1"></i> --}}
                                        </div>
                                    @else
                                        <div
                                            class="w-6 h-6 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                                            {{-- <i class="fas fa-exclamation fa-xs text-amber-600 dark:text-amber-400"></i> --}}
                                            <i class="fas fa-exclamation-circle fa-xs mr-1"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
