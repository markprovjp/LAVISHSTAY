<style>
    .button-action {
        position: relative;
    }

    .menu-button-action {
        position: absolute;
        top: 100%;
        right: 0;
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
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Room Types Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage all room types and their configurations</p>
            </div>
            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add room type button -->
                <a href="{{ route('admin.room-types.create') }}">
                    <button
                        class="btn cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path
                                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="max-xs:sr-only">Add Room Type</span>
                    </button>
                </a>
            </div>
        </div>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Quản lý Loại Phòng') }}
                </h2>
                <a href="{{ route('admin.room-types.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Thêm Loại Phòng Mới
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
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
                                            Room Code
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Total Room
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($roomTypes as $roomType)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $roomType->room_type_id }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $roomType->room_code }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs truncate">
                                                    {{ Str::limit($roomType->name, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $roomType->total_room ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs truncate">
                                                    {{ Str::limit($roomType->description, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                                <div class="relative inline-block text-left">
                                                    <button type="button"
                                                        class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                                        onclick="toggleDropdown({{ $roomType->room_type_id }})"
                                                        id="dropdown-button-{{ $roomType->room_type_id }}">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                            </path>
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown Menu -->
                                                    <div id="dropdown-menu-{{ $roomType->room_type_id }}"
                                                        class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1 z-500" role="menu">
                                                            <!-- View Details -->
                                                            <button
                                                                onclick="toggleDetails({{ $roomType->room_type_id }}); closeDropdown({{ $roomType->room_type_id }})"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                View Details
                                                            </button>

                                                            <!-- Photos and Amenities -->
                                                            <a href="{{ route('admin.room-types.show', $roomType->room_type_id) }}"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                Ảnh và Tiện ích
                                                            </a>

                                                            <!-- Edit -->
                                                            <a href="{{ route('admin.room-types.edit', $roomType->room_type_id) }}"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                Edit Room Type
                                                            </a>

                                                            <!-- Divider -->
                                                            <div class="border-t border-gray-100 dark:border-gray-700">
                                                            </div>

                                                            <!-- Delete -->
                                                            <button
                                                                onclick="deleteRoomType({{ $roomType->room_type_id }}); closeDropdown({{ $roomType->room_type_id }})"
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
                                                                Delete Room Type
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Expandable Details Row -->
                                        <tr id="details-{{ $roomType->room_type_id }}"
                                            class="hidden bg-gray-50 dark:bg-gray-700/50">
                                            <td colspan="6" class="px-5 py-4">
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                    <!-- Left Column -->
                                                    <div class="space-y-4">
                                                        <!-- Descriptions -->
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                                Room Details</h4>
                                                            <div class="space-y-2">
                                                                @if ($roomType->name)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Name:</span>
                                                                        <p
                                                                            class="text-sm text-green-600 dark:text-green-400">
                                                                            {{ $roomType->name }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                                @if ($roomType->total_room)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Total
                                                                            Room:</span>
                                                                        <p
                                                                            class="text-sm text-green-600 dark:text-green-400">
                                                                            {{ $roomType->total_room }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                                @if ($roomType->room_area)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Diện
                                                                            Tích (m²):</span>
                                                                        <p
                                                                            class="text-sm text-green-600 dark:text-green-400">
                                                                            {{ $roomType->room_area }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                                @if ($roomType->view)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Hướng
                                                                            View:</span>
                                                                        <p
                                                                            class="text-sm text-green-600 dark:text-green-400">
                                                                            {{ $roomType->view }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                                @if ($roomType->rating)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Đánh
                                                                            Giá:</span>
                                                                        <p
                                                                            class="text-sm text-green-600 dark:text-green-400">
                                                                            {{ $roomType->rating }} / 5
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                                @if ($roomType->description)
                                                                    <div>
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Description:</span>
                                                                        <p
                                                                            class="text-sm text-green-600 dark:text-green-400">
                                                                            {{ $roomType->description }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                Chưa có loại phòng nào được tạo.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $roomTypes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Animation khi hiển thị
        document.getElementById('notification').classList.add('translate-y-0', 'opacity-100');
        document.getElementById('notification').classList.remove('-translate-y-full', 'opacity-0');

        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            closeNotification();
        }, 5000);

        function closeNotification() {
            const notification = document.getElementById('notification');
            notification.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    </script>
    <script>
        // Toggle Room Type details
        function toggleDetails(roomTypeId) {
            const detailsRow = document.getElementById(`details-${roomTypeId}`);
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
        function toggleDropdown(roomTypeId) {
            const dropdown = document.getElementById(`dropdown-menu-${roomTypeId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${roomTypeId}`) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown
        function closeDropdown(roomTypeId) {
            const dropdown = document.getElementById(`dropdown-menu-${roomTypeId}`);
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

        // Delete Room Type
        function deleteRoomType(roomTypeId) {
            if (confirm('Bạn có chắc chắn muốn xóa loại phòng này? Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/room-types/destroy/${roomTypeId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                form.appendChild(csrfToken);
                document.body.appendChild(form);

                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-Token': csrfToken.value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            showNotification(data.error || 'Lỗi khi xóa loại phòng', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Delete failed:', error);
                        showNotification('Lỗi khi xóa loại phòng', 'error');
                    });
            }
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className =
                `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            notification.className += ` ${bgColor} text-white`;

            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">
                        ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}
                    </span>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }
    </script>
</x-app-layout>
