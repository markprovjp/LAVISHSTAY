<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chỉnh Sửa Loại Phòng</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Cập nhật thông tin loại phòng</p>
            </div>
            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back Button -->
                <a href="{{ route('admin.room-types') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <i class="fa-solid fa-backward"></i><span class="ml-2">Quay lại danh sách</span>
                </a>
            </div>
        </div>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Chỉnh Sửa Loại Phòng') }}
                </h2>
                <a href="{{ route('admin.room-types') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Quay lại
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.room-types.update', $roomType->room_type_id) }}" method="POST" id="roomTypeForm">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Room Code -->
                                <div>
                                    <label for="room_code"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mã Loại Phòng <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="room_code" id="room_code" value="{{ old('room_code', $roomType->room_code) }}"
                                        class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Ví dụ: deluxe_001" required>
                                </div>

                                <!-- Total Room -->
                                <div>
                                    <label for="total_room"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tổng Số Phòng
                                    </label>
                                    <input type="number" name="total_room" id="total_room" value="{{ old('total_room', $roomType->total_room) }}"
                                        class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Ví dụ: 50" min="0">
                                </div>

                                <!-- Name -->
                                <div class="md:col-span-2">
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tên Loại Phòng <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $roomType->name) }}"
                                        class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Ví dụ: Phòng Deluxe Hướng Biển" required>
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mô Tả
                                    </label>
                                    <textarea name="description" id="description" rows="5"
                                        class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Mô tả loại phòng...">{{ old('description', $roomType->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="px-6 py-4 border-t mt-5 rounded-b-lg">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('admin.room-types') }}"
                                        class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        Hủy
                                    </a>
                                    <button type="submit"
                                        class="btn cursor-pointer bg-violet-500 hover:bg-violet-600 text-white">
                                        Cập Nhật Loại Phòng
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            // Form validation
            function validateForm() {
                let isValid = true;
                const requiredFields = ['room_code', 'name'];

                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                return isValid;
            }

            // Form submission
            document.getElementById('roomTypeForm').addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    showNotification('Vui lòng điền đầy đủ các trường bắt buộc', 'error');
                    return;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = `
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" width="24" height="24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Đang cập nhật...
                `;
                submitBtn.disabled = true;
            });

            // Notification system
            function showNotification(message, type = 'info') {
                const existingNotifications = document.querySelectorAll('.notification');
                existingNotifications.forEach(notification => notification.remove());

                const notification = document.createElement('div');
                notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
                
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

        <style>
            .form-input, .form-select, .form-checkbox {
                @apply transition-colors duration-200;
            }
            
            .form-input:focus, .form-select:focus {
                @apply ring-2 ring-violet-500 border-violet-500;
            }
            
            .btn {
                @apply transition-all duration-200 transform;
            }
            
            .btn:hover {
                @apply scale-105;
            }
            
            .btn:active {
                @apply scale-95;
            }

            .max-h-40::-webkit-scrollbar {
                width: 4px;
            }

            .max-h-40::-webkit-scrollbar-track {
                background: transparent;
            }

            .max-h-40::-webkit-scrollbar-thumb {
                background: rgba(156, 163, 175, 0.5);
                border-radius: 2px;
            }

            .max-h-40::-webkit-scrollbar-thumb:hover {
                background: rgba(156, 163, 175, 0.7);
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 768px) {
                .tab-button {
                    @apply text-xs px-2;
                }

                .grid-cols-2 {
                    @apply grid-cols-1;
                }

                .md\:grid-cols-2 {
                    @apply grid-cols-1;
                }
            }
        </style>
    </div>
</x-app-layout>