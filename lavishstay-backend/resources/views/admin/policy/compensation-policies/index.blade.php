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
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Quản Lý Chính Sách Bồi Thường
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Quản lý tất cả các chính sách bồi thường và cấu
                    hình của chúng</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.compensation-policies.create') }}">
                    <button class="btn bg-violet-600 text-white hover:bg-violet-700 transition-colors duration-200">
                        <svg class="fill-current shrink-0 xs:hidden w-4 h-4 mr-2" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only cursor-pointer">Thêm Chính Sách</span>
                    </button>
                </a>
            </div>
        </div>

        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Quản lý Chính sách Bồi Thường') }}
                </h2>
                <a href="{{ route('admin.compensation-policies.create') }}"
                    class="btn bg-violet-600 text-white hover:bg-violet-700">
                    Thêm chính sách mới
                </a>
            </div>
        </x-slot>

        <div class="py-6">
            <div class="">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('admin.compensation-policies') }}"
                            class="mb-6 flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Tìm kiếm theo tên hoặc mô tả..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm">
                            </div>
                            <div>
                                <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt
                                        động</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                        Không hoạt động</option>
                                </select>
                            </div>
                            <div>
                                <select name="condition_type"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                    <option value="">Tất cả loại sự cố</option>
                                    <option value="room_damage"
                                        {{ request('condition_type') === 'room_damage' ? 'selected' : '' }}>Hư hỏng
                                        phòng</option>
                                    <option value="service_failure"
                                        {{ request('condition_type') === 'service_failure' ? 'selected' : '' }}>Lỗi
                                        dịch vụ</option>
                                    <option value="overbooking"
                                        {{ request('condition_type') === 'overbooking' ? 'selected' : '' }}>Quá tải đặt
                                        phòng</option>
                                    <option value="other"
                                        {{ request('condition_type') === 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div>
                                <select name="room_type_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                    <option value="">Tất cả loại phòng</option>
                                    @foreach ($roomTypes as $roomType)
                                        <option value="{{ $roomType->room_type_id }}"
                                            {{ request('room_type_id') == $roomType->room_type_id ? 'selected' : '' }}>
                                            {{ $roomType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-violet-600 text-white rounded-md hover:bg-violet-700 text-sm cursor-pointer flex items-center gap-2">
                                🔍 Lọc
                            </button>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr
                                        class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <a
                                                href="{{ route('admin.compensation-policies', array_merge(request()->query(), ['sort_by' => 'compensation_policy_id', 'sort_order' => request('sort_by') === 'compensation_policy_id' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                                ID
                                                @if (request('sort_by') === 'compensation_policy_id')
                                                    <span>{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <a
                                                href="{{ route('admin.compensation-policies', array_merge(request()->query(), ['sort_by' => 'name', 'sort_order' => request('sort_by') === 'name' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Tên chính sách
                                                @if (request('sort_by') === 'name')
                                                    <span>{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Loại sự cố</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <a
                                                href="{{ route('admin.compensation-policies', array_merge(request()->query(), ['sort_by' => 'discount_value', 'sort_order' => request('sort_by') === 'discount_value' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Giảm giá
                                                @if (request('sort_by') === 'discount_value')
                                                    <span>{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <a
                                                href="{{ route('admin.compensation-policies', array_merge(request()->query(), ['sort_by' => 'max_compensation_amount', 'sort_order' => request('sort_by') === 'max_compensation_amount' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Mức bồi thường tối đa
                                                @if (request('sort_by') === 'max_compensation_amount')
                                                    <span>{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Loại phòng</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Trạng thái</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <a
                                                href="{{ route('admin.compensation-policies', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_order' => request('sort_by') === 'created_at' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Ngày tạo
                                                @if (request('sort_by') === 'created_at')
                                                    <span>{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($policies as $policy)
                                        <tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->compensation_policy_id }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs truncate">{{ Str::limit($policy->name, 50) }}
                                                </div>
                                                @if ($policy->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ Str::limit($policy->description, 50) }}</div>
                                                @endif
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->condition_type_label }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->formatted_discount_value }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->max_compensation_amount ? number_format($policy->max_compensation_amount, 0, ',', '.') . ' VND' : '-' }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->roomType ? $policy->roomType->name : 'Tất cả' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <button onclick="toggleStatus({{ $policy->compensation_policy_id }})"
                                                    class="status-toggle px-2 inline-flex text-xs leading-5 font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-violet-500 {{ $policy->is_active == 1 ? 'bg-green-200 text-green-800' : 'bg-red-600 text-red-800' }}"
                                                    id="status-toggle-{{ $policy->compensation_policy_id }}">
                                                    {{ $policy->is_active == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                                </button>
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <div class="relative inline-block text-left">
                                                    <button type="button"
                                                        class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-violet-500 transition-colors duration-200"
                                                        onclick="toggleDropdown({{ $policy->compensation_policy_id }})"
                                                        id="dropdown-button-{{ $policy->compensation_policy_id }}">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown Menu -->
                                                    <div id="dropdown-menu-{{ $policy->compensation_policy_id }}"
                                                        class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1" role="menu">
                                                            <a href="{{ route('admin.compensation-policies.show', $policy->compensation_policy_id) }}"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                Xem Chi Tiết
                                                            </a>
                                                            <a href="{{ route('admin.compensation-policies.edit', $policy->compensation_policy_id) }}"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                Sửa Chính Sách
                                                            </a>
                                                            <div class="border-t border-gray-200 dark:border-gray-700">
                                                            </div>
                                                            <button
                                                                onclick="deletePolicy({{ $policy->compensation_policy_id }}); closeDropdown({{ $policy->compensation_policy_id }})"
                                                                class="flex mt-2 items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150 cursor-pointer"
                                                                role="menuitem">
                                                                <svg class="w-4 h-4 mr-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Xóa Chính Sách
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Expandable Details Row -->
                                        <tr id="details-{{ $policy->compensation_policy_id }}"
                                            class="hidden bg-gray-50 dark:bg-gray-700/50">
                                            <td colspan="9" class="px-4 py-4">
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                    <!-- Left Column -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Thông tin chính sách</h4>
                                                            <div class="space-y-2">
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Tên:</span>
                                                                    <p
                                                                        class="text-sm text-green-600 dark:text-green-400">
                                                                        {{ $policy->name }}</p>
                                                                </div>
                                                                @if ($policy->description)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Mô
                                                                            tả:</span>
                                                                        <p
                                                                            class="text-sm text-green-600 dark:text-green-400">
                                                                            {{ $policy->description }}</p>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Loại
                                                                        sự cố:</span>
                                                                    <p
                                                                        class="text-sm text-green-600 dark:text-green-400">
                                                                        {{ $policy->condition_type_label }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Quy định bồi thường</h4>
                                                            <div class="space-y-2">
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Loại
                                                                        giảm giá:</span>
                                                                    <span
                                                                        class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs bg-blue-100 dark:bg-blue-400/30 text-blue-600 dark:text-blue-400">{{ $policy->discount_type_label }}</span>
                                                                </div>
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Giá
                                                                        trị giảm giá:</span>
                                                                    <span
                                                                        class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs bg-orange-100 dark:bg-orange-400/30 text-orange-600 dark:text-orange-400">{{ $policy->formatted_discount_value }}</span>
                                                                </div>
                                                                @if ($policy->max_compensation_amount)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Mức
                                                                            bồi thường tối đa:</span>
                                                                        <span
                                                                            class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs bg-red-100 dark:bg-red-400/30 text-red-600 dark:text-red-400">{{ number_format($policy->max_compensation_amount, 0, ',', '.') }}
                                                                            VND</span>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Loại
                                                                        phòng áp dụng:</span>
                                                                    <span
                                                                        class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs bg-purple-100 dark:bg-purple-400/30 text-purple-600 dark:text-purple-400">{{ $policy->roomType ? $policy->roomType->name : 'Tất cả' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Right Column -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Thông tin trạng thái</h4>
                                                            <div class="space-y-2">
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Tình
                                                                        trạng hiện tại:</span>
                                                                    <span
                                                                        class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs {{ $policy->is_active == 1 ? 'bg-green-100 dark:bg-green-400/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-400/30 text-red-600 dark:text-red-400' }}">
                                                                        {{ $policy->is_active == 1 ? 'Hoạt động' : 'Không hoạt động' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Dấu thời gian</h4>
                                                            <div
                                                                class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                                <div>Ngày tạo:
                                                                    {{ $policy->created_at->format('M d, Y H:i') }}
                                                                </div>
                                                                <div>Ngày sửa:
                                                                    {{ $policy->updated_at->format('M d, Y H:i') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9"
                                                class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                                Chưa có chính sách bồi thường nào được tạo.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $policies->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Policy details
        function toggleDetails(policyId) {
            const detailsRow = document.getElementById(`details-${policyId}`);
            const isHidden = detailsRow.classList.contains('hidden');

            // Close all other details first
            const allDetails = document.querySelectorAll('[id^="details-"]');
            allDetails.forEach(detail => {
                detail.classList.add('hidden');
            });

            // Toggle current details
            if (isHidden) {
                detailsRow.classList.remove('hidden');
                setTimeout(() => {
                    detailsRow.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }
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

        // Toggle Policy status
        function toggleStatus(policyId) {
            if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái chính sách này?')) {
                fetch(`/admin/compensation-policies/toggle-status/${policyId}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const button = document.querySelector(`#status-toggle-${policyId}`);
                            button.classList.toggle('bg-green-100', data.is_active == 1);
                            button.classList.toggle('bg-red-600', data.is_active == 0);
                            button.classList.toggle('text-green-800', data.is_active == 1);
                            button.classList.toggle('text-white', data.is_active == 0);
                            button.textContent = data.is_active == 1 ? 'Hoạt động' : 'Không hoạt động';
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi cập nhật trạng thái!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                    });
            }
        }

        // Delete Policy
        function deletePolicy(policyId) {
            if (confirm('Bạn có chắc chắn muốn xóa chính sách này? Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/compensation-policies/${policyId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
