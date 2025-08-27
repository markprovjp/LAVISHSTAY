<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý Nhân viên</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý tất cả nhân viên trong hệ thống</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.users.staffs.create') }}">
                    <button
                        class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white cursor-pointer">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6 .4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Thêm Nhân viên</span>
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

        <form method="GET" action="{{ route('admin.users.staffs.index') }}"
            class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                <!-- Họ tên -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <i class="fas fa-user mr-1 text-violet-600"></i> Họ tên
                    </label>
                    <input type="text" name="name" id="name" value="{{ request('name') }}"
                        placeholder="Nhập họ tên"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Email hoặc SĐT -->
                <div>
                    <label for="keyword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <i class="fas fa-at mr-1 text-violet-600"></i> Email/SĐT
                    </label>
                    <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}"
                        placeholder="Nhập email hoặc SĐT"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- CCCD/Hộ chiếu -->
                <div>
                    <label for="identity_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <i class="fas fa-id-card mr-1 text-violet-600"></i> CCCD/Hộ chiếu
                    </label>
                    <input type="text" name="identity_code" id="identity_code" value="{{ request('identity_code') }}"
                        placeholder="Nhập CCCD/Hộ chiếu"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 cursor-pointer">
                        <i class="fas fa-user-tag mr-1 text-violet-600"></i> Vai trò
                    </label>
                    <!-- Dropdown chọn vai trò -->
                    <select name="role" id="role"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 cursor-pointer">
                        <!-- Lựa chọn mặc định -->
                        <option value="">-- Tất cả --</option>
                        <!-- Lặp qua danh sách vai trò để tạo các option -->
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ \App\Models\Role::getRoleLabel($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons group -->
                <div class="flex flex-col sm:flex-row sm:space-x-2">
                    <button type="submit"
                        class="inline-flex justify-center items-center px-4 py-2 mb-2 mr-3 sm:mb-0 bg-violet-600 hover:bg-violet-700 border border-transparent rounded-md font-medium text-white text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 w-full sm:w-auto cursor-pointer">
                        <i class="fas fa-search mr-2"></i> Tìm kiếm
                    </button>

                    <a href="{{ route('admin.users.staffs.index') }}"
                        class="inline-flex justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-200 text-sm shadow-sm w-full sm:w-auto cursor-pointer">
                        <i class="fas fa-redo-alt mr-2"></i> Đặt lại
                    </a>
                </div>
            </div>
        </form>

        <!-- Main Content -->
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if ($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Ảnh đại diện</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tên</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Số điện thoại</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Số CCCD / Hộ chiếu</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Vai trò</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Ngày tham gia</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr
                                        class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <!-- Avatar -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($user->profile_photo_url)
                                                <img class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                                    src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
                                                    <span
                                                        class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Name -->
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <div class="font-medium">{{ $user->name }}</div>
                                            @if ($user->address)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ Str::limit($user->address, 30) }}</div>
                                            @endif
                                        </td>

                                        <!-- Email -->
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {!! $user->email ?: '<span class="text-violet-500 ">Chưa cung cấp</span>' !!}
                                        </td>

                                        <!-- Phone -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {!! $user->phone ?: '<span class="text-violet-500 ">Chưa cung cấp</span>' !!}
                                        </td>

                                        <!-- Identity Code -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {!! $user->identity_code ?: '<span class="text-violet-500 ">Chưa cung cấp</span>' !!}
                                        </td>

                                        <!-- Role -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach ($user->roles as $role)
                                                @php
                                                    $roleColors = [
                                                        'system_admin' => 'bg-red-100 text-red-800', // Quản trị hệ thống
                                                        'guest' => 'bg-blue-100 text-blue-800', // Khách hàng
                                                        'receptionist' => 'bg-yellow-100 text-yellow-800', // Lễ tân
                                                        'hotel_manager' => 'bg-purple-100 text-purple-800', // Quản lý khách sạn
                                                        'marketing' => 'bg-pink-100 text-pink-800', // Marketing & SEO
                                                        'finance' => 'bg-teal-100 text-teal-800', // Kế toán & Tài chính
                                                    ];
                                                    $color = $roleColors[$role->name] ?? 'bg-gray-100 text-gray-800';
                                                @endphp

                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }} mr-1">
                                                    {{ \App\Models\Role::getRoleLabel($role->name) }}
                                                </span>
                                            @endforeach

                                        </td>

                                        <!-- Join Date -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ optional($user->created_at)->format('d/m/Y H:i') ?? 'Không xác định' }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                            <div class="relative inline-block text-left">
                                                <button type="button"
                                                    class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200 cursor-pointer"
                                                    onclick="toggleDropdown({{ $user->id }})"
                                                    id="dropdown-button-{{ $user->id }}">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div id="dropdown-menu-{{ $user->id }}"
                                                    class="hidden menu-button-action bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                    <div class="py-1" role="menu">
                                                        <a href="{{ route('admin.users.staffs.show', $user->id) }}"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150 cursor-pointer"
                                                            role="menuitem">
                                                            Xem Chi tiết
                                                        </a>
                                                        <div class="border-t border-gray-100 dark:border-gray-700">
                                                        </div>
                                                        <button
                                                            onclick="deleteUser({{ $user->id }}); closeDropdown({{ $user->id }})"
                                                            class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                            role="menuitem">
                                                            <svg style="width: 20px; align-items: center"
                                                                class="mr-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                            Xóa Nhân viên
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Expandable Details Row -->
                                    <tr id="details-{{ $user->id }}"
                                        class="hidden bg-gray-50 dark:bg-gray-700/50">
                                        <td colspan="8" class="px-5 py-4">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                                <!-- Left Column -->
                                                <div class="space-y-4">
                                                    <!-- User Info -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Thông tin Nhân viên</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">Họ
                                                                    tên:</span>
                                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                                    {{ $user->name }}</p>
                                                            </div>
                                                            @if ($user->address)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Địa
                                                                        chỉ:</span>
                                                                    <p
                                                                        class="text-sm text-green-600 dark:text-green-400">
                                                                        {{ $user->address }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Contact Info -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Thông tin Liên hệ</h4>
                                                        <div class="space-y-2">
                                                            @if ($user->email)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        <span
                                                                            class="inline-flex items-center font-normal py-1 rounded-full text-xs bg-violet-100 dark:bg-violet-400/30 text-violet-600 dark:text-violet-400">
                                                                            {{ $user->email }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($user->phone)
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Số
                                                                        điện thoại:</span>
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        <span
                                                                            class="inline-flex items-center font-normal py-1 rounded-full text-xs bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400">
                                                                            {{ $user->phone }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right Column -->
                                                <div class="space-y-4">
                                                    <!-- Role -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Vai trò</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span
                                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">Vai
                                                                    trò:</span>
                                                                <p
                                                                    class="text-sm text-violet-600 dark:text-violet-400">
                                                                    {{ ucfirst($user->role) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Timestamps -->
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Thời gian</h4>
                                                        <div
                                                            class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                            <div>Tạo:
                                                                {{ optional($user->created_at)->format('d/m/Y H:i') ?? 'Không xác định' }}
                                                            </div>
                                                            <div>Cập nhật:
                                                                {{ optional($user->updated_at)->format('d/m/Y H:i') ?? 'Không xác định' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div
                            class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                            <span class="text-4xl text-gray-400">👥</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Không tìm thấy nhân viên
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Bắt đầu bằng cách tạo nhân viên đầu tiên trong
                            hệ thống.</p>
                        <a href="{{ route('admin.users.staffs.create') }}"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-violet-600 hover:bg-violet-700 transition-colors duration-200 cursor-pointer">
                            Thêm Nhân viên Mới
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Animation khi hiển thị thông báo
        document.querySelectorAll('#notification').forEach(notification => {
            notification.classList.add('translate-y-0', 'opacity-100');
            notification.classList.remove('-translate-y-full', 'opacity-0');

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

        function toggleDropdown(userId) {
            const dropdown = document.getElementById(`dropdown-menu-${userId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${userId}`) {
                    menu.classList.add('hidden');
                }
            });

            dropdown.classList.toggle('hidden');
        }

        function closeDropdown(userId) {
            const dropdown = document.getElementById(`dropdown-menu-${userId}`);
            dropdown.classList.add('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');

            let clickedInsideDropdown = false;

            dropdowns.forEach(dropdown => {
                if (dropdown.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            buttons.forEach(button => {
                if (button.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        function deleteUser(userId) {
            if (confirm('Bạn có chắc chắn muốn xóa nhân viên này? Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/staffs/destroy/${userId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        .button-action {
            position: relative;
        }

        .menu-button-action {
            position: absolute;
            top: 0%;
            right: 130%;
            z-index: 50;
            width: 200px;
        }

        @media (max-width: 640px) {
            .menu-button-action {
                right: 0;
                top: 100%;
                width: 160px;
            }

            .grid-cols-5 {
                grid-template-columns: 1fr;
            }

            .table-auto {
                font-size: 0.875rem;
            }

            .table-auto th,
            .table-auto td {
                padding: 0.5rem;
            }
        }

        /* Hiệu ứng hover cho hàng trong bảng */
        tr:hover {
            background-color: #f3f4f6 !important;
            /* Màu nền sáng cho light mode */
        }

        .dark tr:hover {
            background-color: #4b5563 !important;
            /* Màu nền tối cho dark mode */
        }
    </style>
</x-app-layout>
