<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Translation Tables Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage translation tables status and deletion</p>
            </div>
            <!-- Actions -->
            <div class="mb-4 sm:mb-0 flex space-x-2">   
                <!-- Back Button -->
                <a href="{{ route('admin.multinational.translation') }}" class="btn bg-gray-900 me-3 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white cursor-pointer">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
                <!-- Add New Button -->
                <button id="openModal" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    Thêm mới
                </button>
            </div>
        </div>

        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Quản lý bảng dịch') }}
                </h2>
            </div>
        </x-slot>

        <!-- Modal -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">×</span>
                <h3 class="text-lg font-medium text-gray-100 mb-4">Thêm bảng mới</h3>
                <form id="addTableForm" class="space-y-4">
                    <div>
                        <label for="table_name" class="block text-sm font-medium">Tên bảng</label>
                        <select id="table_name" name="table_name" class="mt-1 block w-full rounded-md shadow-sm" required>
                            <option value="">Chọn bảng</option>
                        </select>
                    </div>
                    <div>
                        <label for="display_name" class="block text-sm font-medium">Tên hiển thị</label>
                        <input type="text" id="display_name" name="display_name" class="mt-1 block w-full rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label for="is_active" class="block text-sm font-medium">Trạng thái</label>
                        <select id="is_active" name="is_active" class="mt-1 block w-full rounded-md shadow-sm" required>
                            <option value="1">Hoạt động</option>
                            <option value="0">Không hoạt động</option>
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-white">
                        Thêm mới
                    </button>
                </form>
            </div>
        </div>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tên bảng
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tên hiển thị
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Trạng thái
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($tables as $table)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $table->table_name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs truncate">
                                                    {{ Str::limit($table->display_name, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button onclick="toggleStatus('{{ $table->table_name }}')"
                                                    class="status-toggle px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $table->is_active ? 'bg-green-600 text-green-800' : 'bg-red-600 text-red-800' }}">
                                                    {{ $table->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                </button>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                                <div class="relative inline-block text-left">
                                                    <button type="button"
                                                        class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                                        onclick="toggleDropdown('{{ $table->table_name }}')"
                                                        id="dropdown-button-{{ $table->table_name }}">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                            </path>
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown Menu -->
                                                    <div id="dropdown-menu-{{ $table->table_name }}"
                                                        class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1 z-500" role="menu">
                                                            <!-- Delete -->
                                                            <button
                                                                onclick="deleteTable('{{ $table->table_name }}'); closeDropdown('{{ $table->table_name }}')"
                                                                class="flex items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                                role="menuitem">
                                                                <svg style="width: 20px; align-items: center" class="mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                    </path>
                                                                </svg>
                                                                Xóa bảng
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                Chưa có bảng dịch nào được tạo.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $tables->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load tables for dropdown
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/admin/translation/get-tables', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && Array.isArray(data.tables)) {
                    const select = document.getElementById('table_name');
                    data.tables.forEach(table => {
                        const option = document.createElement('option');
                        option.value = table;
                        option.textContent = table;
                        select.appendChild(option);
                    });
                } else {
                    console.warn('Invalid data structure or no tables:', data);
                    alert('Dữ liệu không hợp lệ hoặc không có bảng nào. Vui lòng kiểm tra console.');
                }
            })
            .catch(error => {
                console.error('Error fetching tables:', error);
                alert('Không thể tải danh sách bảng. Vui lòng kiểm tra console để biết thêm chi tiết.');
            });
        });

        // Open and close modal
        document.getElementById('openModal').addEventListener('click', function() {
            document.getElementById('addModal').style.display = 'block';
        });

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        // Submit add table form
        document.getElementById('addTableForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {
                table_name: formData.get('table_name'),
                display_name: formData.get('display_name'),
                is_active: formData.get('is_active'),
            };

            fetch('{{ route("admin.translation.store-table") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Bảng đã được thêm thành công!');
                    closeModal();
                    location.reload();
                } else {
                    alert(data.error || 'Có lỗi xảy ra khi thêm bảng!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm bảng!');
            });
        });

        // Toggle dropdown menu
        function toggleDropdown(tableName) {
            const dropdown = document.getElementById(`dropdown-menu-${tableName}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${tableName}`) {
                    menu.classList.add('hidden');
                }
            });

            dropdown.classList.toggle('hidden');
        }

        // Close dropdown
        function closeDropdown(tableName) {
            const dropdown = document.getElementById(`dropdown-menu-${tableName}`);
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

        // Toggle table status
        function toggleStatus(tableName) {
            if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái bảng này?')) {
                fetch('{{ route("admin.translation.manage-tables.toggle-status", "") }}/' + tableName, {
                    method: 'POST',
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
                        alert(data.error || 'Có lỗi xảy ra khi cập nhật trạng thái!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                });
            }
        }

        // Delete table
        function deleteTable(tableName) {
            if (confirm('Bạn có chắc chắn muốn xóa bảng này? Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.translation.manage-tables.destroy", "") }}/' + tableName;

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

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal-content {
        background-color: #1f2937;
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        border: 1px solid #9333ea;
        box-shadow: 0 4px 6px rgba(147, 51, 234, 0.1);
        color: #f9fafb;
    }

    .close {
        float: right;
        font-size: 24px;
        cursor: pointer;
        color: #d1d5db;
    }

    .close:hover {
        color: #f9fafb;
    }

    .modal-content input,
    .modal-content select {
        background-color: #374151;
        color: #f9fafb;
        border: 1px solid #4b5563;
        border-radius: 4px;
    }

    .modal-content input:focus,
    .modal-content select:focus {
        border-color: #9333ea;
        outline: none;
        box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2);
    }

    .modal-content button {
        background-color: #9333ea;
        color: #ffffff;
        border: none;
    }

    .modal-content button:hover {
        background-color: #7e22ce;
    }

    .modal-content label {
        color: #d1d5db;
    }
</style>