<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.rooms') }}"
                                class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Quản lý phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                    width="24px" height="24px">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.rooms.by-type', $room->roomType->room_type_id) }}"
                                    class="ml-1 text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                    {{ $room->roomType->name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                    width="24px" height="24px">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">{{ $room->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $room->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Phòng {{ $room->name }} • Tầng {{ $room->floor->floor_name }} (Số:
                    {{ $room->floor->floor_number }}) • {{ $room->roomType->name }}
                </p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.rooms.edit', $room->room_id) }}"
                    class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="18px" height="18px">
                        <path
                            d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z" />
                    </svg>
                    <span class="ml-2">Chỉnh sửa</span>
                </a>

                <!-- Delete Button -->
                <button id="deleteButton" class="btn bg-red-500 hover:bg-red-600 text-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                        <path
                            d="M5 7h6v6H5V7zm4-4v1h5v2h-1v7a1 1 0 01-1 1H4a1 1 0 01-1-1V6H2V4h5V3a1 1 0 011-1h2a1 1 0 011 1z" />
                    </svg>
                    <span class="ml-2">Xóa phòng</span>
                </button>
            </div>
        </div>

        <!-- Display Error Message -->
        @if (session('error'))
            <div id="notification-error" class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md relative">
                <div class="flex items-center justify-center w-8 h-8 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">{{ session('error') }}</div>
                </div>
                <button onclick="closeNotificationError()" class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <!-- Main Content -->
            <div class="xl:col-span-2 space-y-6">

                <div class="flex gap-4">
                    <!-- Room Image -->
                    <div class="bg-white flex-1 dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
                        <div class="relative h-64 md:h-80 bg-gray-200 dark:bg-gray-700">
                            @if ($room->image)
                                <img src="{{ $room->image }}" alt="{{ $room->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-400 dark:text-gray-500" fill="currentColor"
                                        viewBox="0 0 20 20" width="24px" height="24px">
                                        <path fill-rule="evenodd"
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                <span
                                    class="inline-flex items-center rounded-full text-lg font-medium
                                    @if ($room->status == 'available') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @else($room->status == 'out_of_service') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @endif">
                                    {{ $statusOptions[$room->status] ?? $room->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Room Details -->
                    <div class="bg-white flex-1 dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thông tin chi tiết</h2>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4 p-6">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên
                                            phòng</label>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $room->name }}</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tầng</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $room->floor->floor_name }} (Số:
                                            {{ $room->floor->floor_number }})</p>
                                    </div>
                                    @if ($room->bedType)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại
                                                giường</label>
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $room->bedType->type_name ?? 'Chưa xác định' }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng
                                            thái</label>
                                        <p class="text-gray-900 dark:text-gray-100">
                                            {{ $statusOptions[$room->status] ?? $room->status }}</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại
                                            phòng</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $room->roomType->name }}</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diện
                                            tích</label>
                                        <p class="text-gray-900 dark:text-gray-100">
                                            {{ $room->roomType->room_area ?? ($room->size ?? 'N/A') }} m²</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số
                                            khách tối đa</label>
                                        <p class="text-gray-900 dark:text-gray-100">
                                            {{ $room->roomType->max_guests ?? ($room->max_guests ?? 'N/A') }} khách</p>
                                    </div>
                                    @if ($room->view)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hướng
                                                nhìn</label>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $room->view }}</p>
                                        </div>
                                    @endif
                                    @if ($room->rating)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Đánh
                                                giá</label>
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-yellow-400 fill-current mr-1"
                                                    viewBox="0 0 20 20" width="24px" height="24px">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                <span
                                                    class="text-gray-900 dark:text-gray-100 font-medium">{{ $room->rating }}/5</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($room->description)
                                <div class="mt-6 p-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mô
                                        tả</label>
                                    <p class="text-gray-900 dark:text-gray-100 leading-relaxed">
                                        {{ $room->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thông tin giá</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giá cơ
                                    bản</label>
                                <p class="text-2xl font-bold text-violet-600 dark:text-violet-400">
                                    {{ number_format($room->roomType->base_price, 0, ',', '.') }} VND</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">mỗi đêm</p>
                            </div>
                            @if ($room->lavish_plus_discount > 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giảm
                                        giá Lavish+</label>
                                    <p class="text-xl font-semibold text-green-600 dark:text-green-400">
                                        {{ $room->lavish_plus_discount }}%</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Giá sau giảm:
                                        {{ number_format(($room->base_price_vnd * (100 - $room->lavish_plus_discount)) / 100, 0, ',', '.') }}
                                        VND
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thống kê nhanh</h2>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4 p-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Trạng thái</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($room->status == 'available') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($room->status == 'out_of_service') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                        {{ $statusOptions[$room->status] ?? $room->status }}
                                    </span>
                                </div>

                                @if ($room->rating)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Đánh giá</span>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-yellow-400 fill-current mr-1" viewBox="0 0 20 20"
                                                width="24px" height="24px">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $room->rating }}/5</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Tổng đặt phòng</span>
                                    <span
                                        class="text-lg font-semibold text-violet-600 dark:text-violet-400">{{ $room->bookingCount ?? 0 }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Doanh thu tháng</span>
                                    <span
                                        class="text-lg font-semibold text-green-600 dark:text-green-400">{{ number_format($room->monthlyRevenue ?? 0, 0, ',', '.') }}
                                        VND</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Hành động nhanh</h2>
                        </div>
                        <div class="p-5">
                            <div class="space-y-3">
                                <button onclick="showRoomCalendar({{ $room->room_id }})"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        width="24px" height="24px">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Xem lịch đặt phòng
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Room Type Info -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thông tin loại phòng</h2>
                        </div>
                        <div class="px-6 py-2">
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Loại phòng:</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $room->roomType->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Mã loại:</span>
                                    <p
                                        class="font-mono text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded inline-block">
                                        {{ $room->roomType->room_type_id }}</p>
                                </div>
                                @if ($room->roomType->description)
                                    <div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Mô tả loại phòng:</span>
                                        <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">
                                            {{ Str::limit($room->roomType->description, 100) }}</p>
                                    </div>
                                @endif
                                <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('admin.room-types.show', $room->roomType->room_type_id) }}"
                                        class="text-sm text-violet-600 dark:text-violet-400 hover:text-violet-800 dark:hover:text-violet-300">
                                        Xem chi tiết loại phòng →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Calendar Modal -->
            @include('components.room-calendar-modal')

            <!-- JavaScript -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Cấu hình SweetAlert2
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success mx-2",
                            cancelButton: "btn btn-danger mx-2"
                        },
                        buttonsStyling: false
                    });

                    // Animation cho thông báo lỗi
                    const errorNotification = document.getElementById('notification-error');
                    if (errorNotification) {
                        errorNotification.classList.add('translate-y-0', 'opacity-100');
                        errorNotification.classList.remove('-translate-y-full', 'opacity-0');
                        setTimeout(() => {
                            errorNotification.classList.add('opacity-0', 'scale-95');
                            setTimeout(() => errorNotification.remove(), 300);
                        }, 5000);
                    }

                    // Đóng thông báo lỗi thủ công
                    function closeNotificationError() {
                        const el = document.getElementById('notification-error');
                        if (el) {
                            el.classList.add('opacity-0', 'scale-95');
                            setTimeout(() => el.remove(), 300);
                        }
                    }

                    // Xử lý xóa phòng
                    const deleteButton = document.getElementById('deleteButton');
                    if (deleteButton) {
                        deleteButton.addEventListener('click', function(event) {
                            event.preventDefault(); // Ngăn hành động mặc định
                            swalWithBootstrapButtons.fire({
                                title: "Bạn có chắc chắn?",
                                text: "Bạn có chắc chắn muốn xóa phòng {{ $room->name }}? Hành động này không thể hoàn tác!",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Xóa!",
                                cancelButtonText: "Hủy",
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = '{{ route('admin.rooms.destroy', $room->room_id) }}';

                                    const csrfToken = document.createElement('input');
                                    csrfToken.type = 'hidden';
                                    csrfToken.name = '_token';
                                    csrfToken.value = '{{ csrf_token() }}';

                                    const methodField = document.createElement('input');
                                    methodField.type = 'hidden';
                                    methodField.name = '_method';
                                    methodField.value = 'DELETE';

                                    form.appendChild(csrfToken);
                                    form.appendChild(methodField);
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        });
                    } else {
                        console.error('Không tìm thấy nút deleteButton');
                    }

                    function showComingSoon(feature) {
                        swalWithBootstrapButtons.fire({
                            title: 'Chức năng đang phát triển',
                            text: `Chức năng "${feature}" đang được phát triển và sẽ sớm ra mắt!`,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            </script>
        </div>
</x-app-layout>