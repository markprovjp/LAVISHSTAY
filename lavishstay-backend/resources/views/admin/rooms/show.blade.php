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
                    Phòng {{ $room->room_number }} • Tầng {{ $room->floor }} • {{ $room->roomType->name }} 
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
                    

                <!-- Delete Button (optional) -->
                <button onclick="confirmDelete()" class="btn bg-red-500 hover:bg-red-600 text-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                        <path d="M5 7h6v6H5V7zm4-4v1h5v2h-1v7a1 1 0 01-1 1H4a1 1 0 01-1-1V6H2V4h5V3a1 1 0 011-1h2a1 1 0 011 1z"/>
                    </svg>
                    <span class="ml-2">Xóa phòng</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

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
                                        viewBox="0 0 20 20" width="24px" height="24px">>
                                        <path fill-rule="evenodd"
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if ($room->status == 'available') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($room->status == 'occupied') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @elseif($room->status == 'maintenance') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($room->status == 'cleaning') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                    @switch($room->status)
                                        @case('available')
                                            Trống
                                        @break

                                        @case('occupied')
                                            Đang sử dụng
                                        @break

                                        @case('maintenance')
                                            Đang bảo trì
                                        @break

                                        @case('cleaning')
                                            Đang dọn dẹp
                                        @break

                                        @default
                                            {{ $room->status }}
                                    @endswitch
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
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số
                                            phòng</label>
                                        <p
                                            class="text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded inline-block">
                                            {{ $room->room_number }}</p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tầng</label>
                                        <p class="text-gray-900 dark:text-gray-100">Tầng {{ $room->floor }}</p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại
                                            phòng</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $room->roomType->name }}</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diện
                                            tích</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $room->size }} m²</p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số
                                            khách tối đa</label>
                                        <p class="text-gray-900 dark:text-gray-100">{{ $room->max_guests }} khách</p>
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
                                                    viewBox="0 0 20 20" width="24px" height="24px">>
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
                                    {{ number_format($room->base_price_vnd, 0, ',', '.') }} VND
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">mỗi đêm</p>
                            </div>

                            @if ($room->lavish_plus_discount > 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giảm
                                        giá Lavish+</label>
                                    <p class="text-xl font-semibold text-green-600 dark:text-green-400">
                                        {{ $room->lavish_plus_discount }}%
                                    </p>
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

                {{-- <!-- Amenities -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                            Tiện ích phòng
                            <span class="text-gray-400 dark:text-gray-500 font-medium">({{ $room->roomType->amenities->count() }})</span>
                        </h2>
                    </div>
                    <div class="p-5">
                        @if ($room->roomType->amenities->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($room->roomType->amenities as $amenity)
                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg {{ $amenity->is_highlighted ? 'ring-2 ring-violet-500 bg-violet-50 dark:bg-violet-900/20' : '' }}">
                                        <div class="flex-shrink-0">
                                            @if ($amenity->icon)
                                                <div class="w-8 h-8 bg-violet-100 dark:bg-violet-900/30 rounded-full flex items-center justify-center">
                                                    <span class="text-violet-600 dark:text-violet-400 text-sm">{{ $amenity->icon }}</span>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" width="24px" height="24px">>
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $amenity->name }}
                                                @if ($amenity->is_highlighted)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-400">
                                                        Nổi bật
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">Chưa có tiện ích nào</p>
                            </div>
                        @endif
                    </div>
                </div> --}}

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
                                    @elseif($room->status == 'occupied') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @elseif($room->status == 'maintenance') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @elseif($room->status == 'cleaning') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                    @switch($room->status)
                                        @case('available')
                                            Trống
                                        @break

                                        @case('occupied')
                                            Đang sử dụng
                                        @break

                                        @case('maintenance')
                                            Đang bảo trì
                                        @break

                                        @case('cleaning')
                                            Đang dọn dẹp
                                        @break

                                        @default
                                            {{ $room->status }}
                                    @endswitch
                                </span>
                            </div>

                            @if ($room->rating)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Đánh giá</span>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-yellow-400 fill-current mr-1" viewBox="0 0 20 20"
                                            width="24px" height="24px">>
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $room->rating }}/5</span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Tổng đặt phòng</span>
                                <span class="text-lg font-semibold text-violet-600 dark:text-violet-400">0</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Doanh thu tháng</span>
                                <span class="text-lg font-semibold text-green-600 dark:text-green-400">0 VND</span>
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
                            <button onclick="showComingSoon('Chỉnh sửa thông tin')"
                                class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    width="24px" height="24px">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                    </path>
                                </svg>
                                Chỉnh sửa thông tin
                            </button>

                            <button onclick="showComingSoon('Cập nhật giá')"
                                class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    width="24px" height="24px">
                                    <path
                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                                    </path>
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Cập nhật giá phòng
                            </button>

                            <!-- Trong phần Quick Actions, thay thế button cũ -->
                            <button onclick="showRoomCalendar({{ $room->room_id }})"
                                class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20" width="24px" height="24px">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Xem lịch đặt phòng
                            </button>


                            
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

        </div>



        <!-- Room Calendar Modal -->
        @include('components.room-calendar-modal')

        <!-- Update script -->
        <script>
            function showComingSoon(feature) {
                if (feature === 'Xem lịch đặt phòng') {
                    showRoomCalendar({{ $room->room_id }});
                } else {
                    alert(`Chức năng "${feature}" đang được phát triển và sẽ sớm ra mắt!`);
                }
            }
            function confirmDelete() {
                if (confirm('Bạn có chắc chắn muốn xóa phòng {{ $room->name }}? Hành động này không thể hoàn tác!')) {
                    // Create form to delete
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.rooms.destroy", $room->room_id) }}';
                    
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
            }
        </script>

</x-app-layout>
