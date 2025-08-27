<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý Coupon</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý các mã giảm giá cho đặt phòng</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add coupon button -->
                <a href="{{ route('admin.coupons.create') }}"
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm Coupon</span>
                </a>
                <a href="{{ route('admin.coupons.all-redemptions') }}"
                    class="btn btn bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Lịch sử sử dụng</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="py-5">
            <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex flex-wrap gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm kiếm theo mã hoặc mô tả..." class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <select name="type" class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả loại</option>
                        <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Phần trăm</option>
                        <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                    </select>
                </div>

                <!-- Expiry Filter -->
                <div>
                    <select name="expiry" class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả hạn sử dụng</option>
                        <option value="active" {{ request('expiry') === 'active' ? 'selected' : '' }}>Còn hạn</option>
                        <option value="expired" {{ request('expiry') === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <select name="sort_by" class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="code" {{ request('sort_by') === 'code' ? 'selected' : '' }}>Mã coupon</option>
                        <option value="value" {{ request('sort_by') === 'value' ? 'selected' : '' }}>Giá trị giảm</option>
                        <option value="end_at" {{ request('sort_by') === 'end_at' ? 'selected' : '' }}>Hạn sử dụng</option>
                        <option value="usage_limit" {{ request('sort_by') === 'usage_limit' ? 'selected' : '' }}>Giới hạn sử dụng</option>
                    </select>
                </div>

                <div>
                    <select name="sort_order" class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                        <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <button type="submit"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M9 2a1 1 0 0 0 0-2H7a1 1 0 0 0 0 2v1.586L1.707 8.879A1 1 0 0 0 1 9.586V15a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-2.414L9 9.414V2ZM3 13v-2.414l3-3V2H4v4.586l-3 3V13H3Z" />
                    </svg>
                    <span class="ml-2">Lọc</span>
                </button>

                <!-- Clear Filters -->
                @if (request()->hasAny(['search', 'status', 'type', 'expiry', 'sort_by']))
                    <a href="{{ route('admin.coupons.index') }}"
                        class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                        Xóa bộ lọc
                    </a>
                @endif
            </form>
        </div>

        @if (session('success'))
            <div id="notification" class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-green-700">Thành công!</h3>
                    <div class="text-sm text-green-600">{{ session('success') }}</div>
                </div>
                <button onclick="closeNotification()" class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="error-notification" class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">{{ session('error') }}</div>
                </div>
                <button onclick="closeErrorNotification()" class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="">
                <table class="table-auto w-full dark:text-gray-300">
                    <!-- Table header -->
                    <thead
                        class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Mã Coupon</th>
                            <th class="px-6 py-4 text-left">Mô tả</th>
                            <th class="px-6 py-4 text-left">Loại</th>
                            <th class="px-6 py-4 text-left">Giá trị</th>
                            <th class="px-6 py-4 text-left">Hạn sử dụng</th>
                            <th class="px-6 py-4 text-left">Lượt sử dụng</th>
                            <th class="px-6 py-4 text-left">Trạng thái</th>
                            <th class="px-6 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($coupons as $coupon)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $coupon->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="font-mono font-bold text-violet-600 dark:text-violet-400">{{ $coupon->code }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="max-w-xs">
                                        @if ($coupon->description)
                                            <div class="text-sm">{{ Str::limit($coupon->description, 50) }}</div>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">Không có mô tả</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $coupon->type === 'percent' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                                        {{ $coupon->type === 'percent' ? 'Phần trăm' : 'Cố định' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @if($coupon->type === 'percent')
                                        {{ $coupon->value }}%
                                    @else
                                        {{ number_format($coupon->value, 0, ',', '.') }} VND
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @if($coupon->end_at)
                                        <div class="{{ $coupon->end_at->isPast() ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                            {{ $coupon->end_at->format('d/m/Y H:i') }}
                                        </div>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Không giới hạn</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $coupon->redemptions_count ?? 0 }}
                                    @if($coupon->usage_limit)
                                        / {{ $coupon->usage_limit }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="toggleStatus({{ $coupon->id }})"
                                        class="status-toggle px-2 inline-flex text-xs text-white leading-5 font-semibold rounded-full {{ $coupon->active ? 'bg-green-200 text-green-800' : 'bg-red-600 text-red-800' }}">
                                        {{ $coupon->active ? 'Hoạt động' : 'Không hoạt động' }}
                                    </button>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                            class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                            onclick="toggleDropdown({{ $coupon->id }})"
                                            id="dropdown-button-{{ $coupon->id }}">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-menu-{{ $coupon->id }}"
                                            class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1 z-500" role="menu">
                                                
                                                <!-- View Redemptions -->
                                                <a href="{{ route('admin.coupons.redemptions', $coupon->id) }}"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    Lịch sử sử dụng
                                                </a>

                                                <!-- Edit -->
                                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    Chỉnh sửa
                                                </a>

                                                <!-- Divider -->
                                                <div class="border-t border-gray-100 dark:border-gray-700">
                                                </div>

                                                <!-- Delete -->
                                                <button
                                                    onclick="deleteCoupon({{ $coupon->id }}); closeDropdown({{ $coupon->id }})"
                                                    class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                    role="menuitem">
                                                    <svg style="width: 20px; align-items: center" class="mr-3"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Xóa Coupon
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Chưa có coupon nào được tạo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>

    <script>
        // Notification scripts
        if (document.getElementById('notification')) {
            document.getElementById('notification').classList.add('translate-y-0', 'opacity-100');
            document.getElementById('notification').classList.remove('-translate-y-full', 'opacity-0');

            setTimeout(() => {
                closeNotification();
            }, 5000);
        }

        if (document.getElementById('error-notification')) {
            document.getElementById('error-notification').classList.add('translate-y-0', 'opacity-100');
            document.getElementById('error-notification').classList.remove('-translate-y-full', 'opacity-0');

            setTimeout(() => {
                closeErrorNotification();
            }, 5000);
        }

        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        function closeErrorNotification() {
            const notification = document.getElementById('error-notification');
            if (notification) {
                notification.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Toggle dropdown menu
        function toggleDropdown(couponId) {
            const dropdown = document.getElementById(`dropdown-menu-${couponId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${couponId}`) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown
        function closeDropdown(couponId) {
            const dropdown = document.getElementById(`dropdown-menu-${couponId}`);
            dropdown.classList.add('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');

            let clickedInsideDropdown = false;

            // Check if clicked inside any dropdown or button
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

            // If clicked outside, close all dropdowns
            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Toggle status function
        function toggleStatus(couponId) {
            if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái của coupon này?')) {
                fetch(`/admin/coupons/${couponId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra khi thay đổi trạng thái');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi thay đổi trạng thái');
                    });
            }
        }

        // Delete coupon function
        function deleteCoupon(couponId) {
            if (confirm('Bạn có chắc chắn muốn xóa coupon này? Hành động này không thể hoàn tác.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/coupons/${couponId}`;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                form.appendChild(methodInput);
                form.appendChild(tokenInput);
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
    </style>
</x-app-layout>