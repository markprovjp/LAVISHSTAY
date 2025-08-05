<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chính sách rời lịch</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý các chính sách dời lịch cho đặt phòng</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add policy button -->
                <a href="{{ route('admin.reschedule-policies.create') }}"
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm chính sách</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="py-5">
            <form method="GET" action="{{ route('admin.reschedule-policies') }}" class="flex flex-wrap gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm kiếm theo tên hoặc mô tả..." class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class=" border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Không hoạt
                            động</option>
                    </select>
                </div>

                <!-- Room Type Filter -->
                <div>
                    <select name="room_type" class=" border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả loại phòng</option>
                        @foreach($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}" {{ request('room_type') == $roomType->id ? 'selected' : '' }}>
                                {{ $roomType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <select name="sort_by" class=" border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Ngày tạo
                        </option>
                        <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Tên</option>
                        <option value="reschedule_fee_vnd"
                            {{ request('sort_by') === 'reschedule_fee_vnd' ? 'selected' : '' }}>Phí dời lịch</option>
                        <option value="min_days_before_checkin"
                            {{ request('sort_by') === 'min_days_before_checkin' ? 'selected' : '' }}>Ngày tối thiểu trước check-in</option>
                    </select>
                </div>

                <div>
                    <select name="sort_order" class=" border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Giảm dần
                        </option>
                        <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Tăng dần
                        </option>
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
                @if (request()->hasAny(['search', 'status', 'room_type', 'sort_by']))
                    <a href="{{ route('admin.reschedule-policies') }}"
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

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="">
                <table class="table-auto w-full dark:text-gray-300">
                    <!-- Table header -->
                    <thead
                        class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Tên chính sách</th>
                            <th class="px-6 py-4 text-left">Loại phòng</th>
                            <th class="px-6 py-4 text-left">Phí dời lịch</th>
                            <th class="px-6 py-4 text-left">Phần trăm phí</th>
                            <th class="px-6 py-4 text-left">Ngày tối thiểu trước check-in</th>
                            <th class="px-6 py-4 text-left">Trạng thái</th>
                            <th class="px-6 py-4 text-left">Ngày tạo</th>
                            <th class="px-6 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($policies as $policy)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $policy->policy_id }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="max-w-xs">
                                        <div class="font-medium">{{ Str::limit($policy->name, 50) }}</div>
                                        @if ($policy->description)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($policy->description, 80) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $policy->roomType ? $policy->roomType->name : 'Tất cả loại phòng' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $policy->reschedule_fee_vnd ? number_format($policy->reschedule_fee_vnd, 0, ',', '.') . ' VND' : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $policy->reschedule_fee_percentage ? $policy->reschedule_fee_percentage . '%' : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $policy->min_days_before_checkin ?? '-' }} ngày
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="toggleStatus({{ $policy->policy_id }})"
                                        class="status-toggle px-2 inline-flex text-xs text-white leading-5 font-semibold rounded-full {{ $policy->is_active ? 'bg-green-200 text-green-800' : 'bg-red-600 text-red-800' }}">
                                        {{ $policy->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $policy->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                            class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                            onclick="toggleDropdown({{ $policy->policy_id }})"
                                            id="dropdown-button-{{ $policy->policy_id }}">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-menu-{{ $policy->policy_id }}"
                                            class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1 z-500" role="menu">

                                                <!-- Edit -->
                                                <a href="{{ route('admin.reschedule-policies.edit', $policy->policy_id) }}"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    Edit Policy
                                                </a>

                                                <!-- Divider -->
                                                <div class="border-t border-gray-100 dark:border-gray-700">
                                                </div>

                                                <!-- Delete -->
                                                <button
                                                    onclick="deletePolicy({{ $policy->policy_id }}); closeDropdown({{ $policy->policy_id }})"
                                                    class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                    role="menuitem">
                                                    <svg style="width: 20px; align-items: center" class=" mr-3"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Delete Policy
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Hidden row for policy details -->
                            <tr id="details-{{ $policy->policy_id }}" class="hidden bg-gray-50 dark:bg-gray-900/50">
                                <td colspan="9" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <!-- Policy Information -->
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Thông tin
                                                chính sách</h4>
                                            <div class="space-y-2">
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Tên:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $policy->name }}</div>
                                                </div>
                                                @if ($policy->description)
                                                    <div>
                                                        <span
                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Mô
                                                            tả:</span>
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            {{ $policy->description }}</div>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Loại phòng:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $policy->roomType ? $policy->roomType->name : 'Tất cả loại phòng' }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reschedule Information -->
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Thông tin dời lịch
                                            </h4>
                                            <div class="space-y-2">
                                                @if ($policy->reschedule_fee_vnd)
                                                    <div>
                                                        <span
                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Phí dời lịch:</span>
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            {{ number_format($policy->reschedule_fee_vnd, 0, ',', '.') }}
                                                            VND</div>
                                                    </div>
                                                @endif
                                                @if ($policy->reschedule_fee_percentage)
                                                    <div>
                                                        <span
                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Phần trăm phí:</span>
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            {{ $policy->reschedule_fee_percentage }}%</div>
                                                    </div>
                                                @endif
                                                @if ($policy->min_days_before_checkin)
                                                    <div>
                                                        <span
                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Ngày tối thiểu trước check-in:</span>
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            {{ $policy->min_days_before_checkin }} ngày</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Application Rules -->
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Quy tắc áp dụng</h4>
                                            <div class="space-y-2">
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Áp dụng cho ngày lễ:</span>
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        <span
                                                            class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs {{ $policy->applies_to_holiday ? 'bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-400/30 text-red-600 dark:text-red-400' }}">
                                                            {{ $policy->applies_to_holiday ? 'Có' : 'Không' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Áp dụng cho cuối tuần:</span>
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        <span
                                                            class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs {{ $policy->applies_to_weekend ? 'bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-400/30 text-red-600 dark:text-red-400' }}">
                                                            {{ $policy->applies_to_weekend ? 'Có' : 'Không' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status Information -->
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Thông tin
                                                trạng thái</h4>
                                            <div class="space-y-2">
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Trạng
                                                        thái hiện tại:</span>
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        <span
                                                            class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs {{ $policy->is_active ? 'bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-400/30 text-red-600 dark:text-red-400' }}">
                                                            {{ $policy->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Timestamps -->
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Thời gian
                                            </h4>
                                            <div class="space-y-2">
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Ngày
                                                        tạo:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $policy->created_at->format('d/m/Y H:i:s') }}</div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Cập
                                                        nhật lần cuối:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $policy->updated_at->format('d/m/Y H:i:s') }}</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Chưa có chính sách dời lịch nào được tạo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $policies->links() }}
            </div>
        </div>
    </div>

    <script>
        // Animation khi hiển thị
        document.getElementById('notification')?.classList.add('translate-y-0', 'opacity-100');
        document.getElementById('notification')?.classList.remove('-translate-y-full', 'opacity-0');

        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            closeNotification();
        }, 5000);

        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }
    </script>
    <script>
        // Toggle Policy details
        function showPolicyDetails(policyId) {
            const detailsRow = document.getElementById(`details-${policyId}`);
            detailsRow.classList.toggle('hidden');
        }
        // Toggle dropdown menu
        function toggleDropdown(policyId) {
            const dropdown = document.getElementById(`dropdown-menu-${policyId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${policyId}`) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown
        function closeDropdown(policyId) {
            const dropdown = document.getElementById(`dropdown-menu-${policyId}`);
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
        function toggleStatus(policyId) {
            if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái của chính sách này?')) {
                fetch(`/admin/reschedule-policies/${policyId}/toggle-status`, {
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

        // Delete policy function
        function deletePolicy(policyId) {
            if (confirm('Bạn có chắc chắn muốn xóa chính sách này? Hành động này không thể hoàn tác.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/reschedule-policies/${policyId}`;

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