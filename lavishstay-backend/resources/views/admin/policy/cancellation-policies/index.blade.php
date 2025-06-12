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
<x-app-layout>

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Cancellation Policies Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage all cancellation policies and their configurations</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add cancellation policy button -->
                <a href="{{ route('admin.cancellation-policies.create') }}">
                    <button
                        class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Add Policy</span>
                    </button>
                </a>
            </div>

        </div>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Quản lý Chính sách hủy') }}
                </h2>
                <a href="{{ route('admin.cancellation-policies.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Thêm chính sách mới
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800  shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tên chính sách
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hủy miễn phí
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Phạt (%)
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Phạt cố định
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Trạng thái
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Ngày tạo
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($policies as $policy)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->policy_id }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs truncate">
                                                    {{ Str::limit($policy->name, 50) }}
                                                </div>
                                                @if($policy->description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($policy->description, 50) }}</div>
                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs truncate">
                                                    {{ $policy->free_cancellation_days ? $policy->free_cancellation_days . ' ngày' : 'Không có' }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->penalty_percentage ? $policy->penalty_percentage . '%' : '-' }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->penalty_fixed_amount_vnd ? number_format($policy->penalty_fixed_amount_vnd, 0, ',', '.') . ' VND' : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button onclick="toggleStatus({{ $policy->policy_id }})"
                                                    class="status-toggle px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $policy->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $policy->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                </button>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $policy->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                                <div class="relative inline-block text-left">
                                                    <button type="button"
                                                        class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                                        onclick="toggleDropdown({{ $policy->policy_id }})"
                                                        id="dropdown-button-{{ $policy->policy_id }}">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                            </path>
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown Menu -->
                                                    <div id="dropdown-menu-{{ $policy->policy_id }}"
                                                        class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1 z-500" role="menu">
                                                            <!-- View Details -->
                                                            <button
                                                                onclick="toggleDetails({{ $policy->policy_id }}); closeDropdown({{ $policy->policy_id }})"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                View Details
                                                            </button>

                                                            <!-- Edit -->
                                                            <a href="{{ route('admin.cancellation-policies.edit', $policy->policy_id) }}"
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
                                                                <svg style="width: 20px; align-items: center" class=" mr-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
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
                                        <!-- Expandable Details Row -->
                                        <tr id="details-{{ $policy->policy_id }}"
                                            class="hidden bg-gray-50 dark:bg-gray-700/50">
                                            <td colspan="8" class="px-5 py-4">
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                    <!-- Left Column -->
                                                    <div class="space-y-4">
                                                        <!-- Policy Name -->
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Policy Information</h4>
                                                            <div class="space-y-2">
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Name:</span>
                                                                    <p
                                                                        class="text-sm  text-green-600 dark:text-green-400 ">
                                                                        {{ $policy->name }}
                                                                    </p>
                                                                </div>
                                                                @if ($policy->description)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Description:</span>
                                                                        <p
                                                                            class="text-sm  text-green-600 dark:text-green-400 ">
                                                                            {{ $policy->description }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <!-- Policy Rules -->
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Cancellation Rules</h4>
                                                            <div class="space-y-2">
                                                                @if ($policy->free_cancellation_days)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Free Cancellation:</span>
                                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                                            <span
                                                                                class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs bg-blue-100 dark:bg-blue-400/30 text-blue-600 dark:text-blue-400">
                                                                                {{ $policy->free_cancellation_days }} ngày trước check-in
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if ($policy->penalty_percentage)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Penalty Percentage:</span>
                                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                                             <span
                                                                                class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs bg-orange-100 dark:bg-orange-400/30 text-orange-600 dark:text-orange-400">
                                                                                {{ $policy->penalty_percentage }}% của tổng tiền
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if ($policy->penalty_fixed_amount_vnd)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Fixed Penalty:</span>
                                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                                             <span
                                                                                class="inline-flex items-center font-normal py-1 px-2 rounded-full text-xs bg-red-100 dark:bg-red-400/30 text-red-600 dark:text-red-400">
                                                                                {{ number_format($policy->penalty_fixed_amount_vnd, 0, ',', '.') }} VND
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Right Column -->
                                                    <div class="space-y-4">
                                                        <!-- Status -->
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Status Information</h4>
                                                            <div class="space-y-2">
                                                                <div>
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Current Status:</span>
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
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Timestamps</h4>
                                                            <div
                                                                class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                                <div>Created:
                                                                    {{ $policy->created_at->format('M d, Y H:i') }}
                                                                </div>
                                                                <div>Updated:
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
                                            <td colspan="8"
                                                class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                Chưa có chính sách hủy nào được tạo.
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
            </div>
        </div>
    </div>

    <script>
    // Toggle Policy details - chỉ giữ lại một hàm duy nhất
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
            // Smooth scroll to the details
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

    // Toggle Policy status
    function toggleStatus(policyId) {
        if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái chính sách này?')) {
            fetch(`/admin/cancellation-policies/toggle-status/${policyId}`, {
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
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/cancellation-policies/destroy/${policyId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

</x-app-layout>
