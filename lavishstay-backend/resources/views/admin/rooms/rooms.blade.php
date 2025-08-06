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
                                Tổng quan phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                    width="16" height="16">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">{{ $roomType->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                    Danh sách phòng {{ $roomType->name }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Tìm thấy {{ $rooms->total() }} phòng thuộc loại {{ $roomType->name }}
                </p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col cursor-pointer sm:auto-cols-max justify-start sm:justify-end gap-2">
                @if($rooms->total() >= $roomType->total_room)
                    <span class="text-gray-600 dark:text-gray-400 font-medium">
                        Đã đạt giới hạn tối đa phòng của loại, không thể thêm mới phòng nữa
                    </span>
                @else
                    <div class="flex gap-2">
                        <a href="{{ route('admin.rooms.create', $roomType->room_type_id) }}"
                        class="btn bg-violet-500 hover:bg-violet-600 text-white">
                            <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                                <path
                                    d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                            </svg>
                            <span class="ml-2">Thêm phòng: {{ $roomType->name }}</span>
                        </a>
                        <div class="relative">
                            <button type="button" id="toggleImport" class="btn bg-violet-500 hover:bg-violet-600 text-white flex items-center">
                                <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                                    <path d="M14 3v10a1 1 0 01-1 1H3a1 1 0 01-1-1V3a1 1 0 011-1h10a1 1 0 011 1zm-1 0H4v10h9V3zM6 7h4a1 1 0 010 2H6a1 1 0 010-2z" />
                                </svg>
                                <span class="ml-2">Nhập Excel</span>
                            </button>
                            <div id="importForm" class="hidden absolute mt-2 right-0 bg-white dark:bg-gray-800 shadow-lg rounded-md p-4 z-20 min-w-[250px]">
                                <form action="{{ route('admin.rooms.import-excel', ['room_type_id' => $roomType->room_type_id]) }}" method="POST" enctype="multipart/form-data" id="excelForm">
                                    @csrf
                                    <div class="flex items-center mb-3">
                                        <input type="file" name="excel_file" id="excelFile" accept=".xls,.xlsx" class="form-input block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                        <button type="button" id="clearFile" class="ml-2 text-red-500 hover:text-red-700" style="display: none;">X</button>
                                    </div>
                                    <span id="fileName" class="text-sm text-gray-600 dark:text-gray-400 block mb-2" style="display: none;"></span>
                                    <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white text-sm w-full">Xác nhận</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
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

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-8">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Bộ lọc tìm kiếm</h2>
            </div>
            <form method="GET" class="px-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm mt-4 font-medium text-gray-700 dark:text-gray-300 mb-1">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tên phòng, tầng, số phòng..."
                            class="form-input block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                            placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm mt-4 font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái</label>
                        <select name="status"
                            class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                            dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 w-full">
                            <option value="">Tất cả trạng thái</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Max Guests -->
                    <div>
                        <label class="block text-sm mt-4 font-medium text-gray-700 dark:text-gray-300 mb-1">Số khách tối đa</label>
                        <select name="max_guests"
                            class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                            dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 w-full">
                            <option value="">Tất cả</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ request('max_guests') == $i ? 'selected' : '' }}>
                                    {{ $i }}+ khách
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- View -->
                    <div>
                        <label class="block text-sm mt-4 font-medium text-gray-700 dark:text-gray-300 mb-1">Hướng nhìn</label>
                        <select name="view"
                            class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                            dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 w-full">
                            <option value="">Tất cả hướng</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Check-in Date -->
                    <div>
                        <label class="block text-sm mt-4 font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày nhận phòng</label>
                        <input type="date" name="check_in" value="{{ request('check_in') }}"
                            class="form-input block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                            placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <!-- Check-out Date -->
                    <div>
                        <label class="block text-sm mt-4 font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày trả phòng</label>
                        <input type="date" name="check_out" value="{{ request('check_out') }}"
                            class="form-input block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                            placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <!-- Submit and Reset -->
                    <div class="flex flex-wrap mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"></label>
                        <div class="mt-6 ">
                            <button type="submit" class="btn me-3 bg-violet-500 hover:bg-violet-600 text-white">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 16 16" width="16" height="16">
                                <path
                                    d="m14.707 13.293-1.414 1.414-2.4-2.4 1.414-1.414 2.4 2.4ZM6.8 12.6A6 6 0 1 1 12.6 6.8a6 6 0 0 1-5.8 5.8ZM2 6a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z" />
                            </svg>
                            <span class="ml-2">Tìm kiếm</span>
                        </button>

                        <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}"
                            class="btn border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 16 16" width="16" height="16">
                                <path
                                    d="M12.72 3.293a1 1 0 010 1.414L9.414 8l3.306 3.293a1 1 0 01-1.414 1.414L8 9.414l-3.293 3.293a1 1 0 01-1.414-1.414L6.586 8 3.293 4.707a1 1 0 011.414-1.414L8 6.586l3.293-3.293a1 1 0 011.414 0z" />
                            </svg>
                            <span class="ml-2">Xóa bộ lọc</span>
                        </a>
                        </div>
                    </div>
                </form>
            </div>


        <!-- Sort and View Options -->
        <div class="flex px-6 flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="mb-4 sm:mb-0">
                <form method="GET" class="flex items-center space-x-3">
                    @foreach (request()->except(['sort_by', 'sort_order']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <label class="text-sm text-gray-600 dark:text-gray-400">Sắp xếp theo:</label>
                    <select name="sort_by" onchange="this.form.submit()"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                            dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                            text-sm">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên phòng</option>
                        <option value="room_number" {{ request('sort_by') == 'room_number' ? 'selected' : '' }}>Số
                            phòng</option>
                        <option value="floor" {{ request('sort_by') == 'floor' ? 'selected' : '' }}>Tầng</option>
                        <option value="base_price_vnd" {{ request('sort_by') == 'base_price_vnd' ? 'selected' : '' }}>
                            Giá phòng</option>
                        <option value="size" {{ request('sort_by') == 'size' ? 'selected' : '' }}>Diện tích</option>
                        <option value="max_guests" {{ request('sort_by') == 'max_guests' ? 'selected' : '' }}>Số khách
                        </option>
                        <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Đánh giá
                        </option>
                    </select>

                    <select name="sort_order" onchange="this.form.submit()"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                            dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                            text-sm">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần
                        </option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần
                        </option>
                    </select>
                </form>
            </div>

            <div class="text-sm text-gray-600 dark:text-gray-400">
                Hiển thị {{ $rooms->firstItem() ?? 0 }} - {{ $rooms->lastItem() ?? 0 }} trong tổng số
                {{ $rooms->total() }} phòng
            </div>
        </div>

        <!-- Rooms Grid with Delete Option -->
        @if ($rooms->count() > 0)
            <form action="{{ route('admin.rooms.destroy.multiple', $roomType->room_type_id) }}" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="grid p-6 grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    <!-- Select All Checkbox -->
                    <div class="col-span-full mb-4 flex items-center">
                        <input type="checkbox" id="selectAll"
    class="form-checkbox h-5 w-5 text-violet-600 border-4 border-gray-700 dark:border-white bg-white dark:bg-gray-800 focus:ring-violet-500 transition shadow-sm" />
                        <label for="selectAll" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Chọn tất cả <span class="text-gray-500 dark:text-gray-400">(Xóa toàn bộ phòng của trang {{ $rooms->currentPage() }})</span>
                        </label>
                        <button type="button" id="deleteSelected" class="btn bg-red-500 hover:bg-red-600 text-white ml-4 px-4 py-2 rounded-lg hidden">
                            Xóa đã chọn
                        </button>
                    </div>

                    @foreach ($rooms as $room)
                        <div
                            class="bg-white dark:bg-gray-800 dark:border-red-900 shadow-lg rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-200 relative">
                            <!-- Delete Checkbox -->
                            <div class="absolute top-2 left-2 z-10">
                                <input type="checkbox" name="room_ids[]" value="{{ $room->room_id }}"
    class="form-checkbox h-5 w-5 border-4 border-gray-700 dark:border-white bg-white dark:bg-gray-800 text-violet-600 focus:ring-violet-500 transition shadow-sm room-checkbox" />
                            </div>

                            <!-- Room Image -->
                            <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                                @if ($room->image)
                                    <img src="{{ $room->image }}" alt="{{ $room->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="currentColor"
                                            viewBox="0 0 20 20" width="16" height="16">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Room Info -->
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                        {{ $room->name }} -
                                        <span
                                            class="inline-flex items-center rounded-full text-lg font-medium
                                            @if ($room->status == 'available') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($room->status == 'occupied') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                            @elseif($room->status == 'maintenance') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @elseif($room->status == 'cleaning') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                            {{ $statusOptions[$room->status] ?? $room->status }}
                                        </span>
                                    </h3>
                                    @if ($room->rating)
                                        <div class="flex items-center ml-2">
                                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"
                                                width="16" height="16">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span
                                                class="text-sm text-gray-600 dark:text-gray-400 ml-1">{{ $room->rating }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="16"
                                            height="16">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Tầng {{ $room->floor->floor_name }} - Phòng {{ $room->floor->floor_number }}
                                    </div>

                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="16"
                                            height="16">
                                            <path
                                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z">
                                            </path>
                                        </svg>
                                        1-{{ $room->roomType->max_guests ?? 0 }} khách •
                                        {{ $room->roomType->room_area ?? 'Chưa cập nhật' }}m²
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-violet-600 dark:text-violet-400">
                                            {{ number_format($room->roomType->base_price ?? 0, 0, ',', '.') }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">VND/đêm</span>
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.rooms.show', $room->room_id) }}"
                                           class="btn bg-violet-500 hover:bg-violet-600 text-white text-sm px-3 py-1.5">
                                            Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>

            <!-- Pagination -->
            <div class="mt-8 p-6">
                {{ $rooms->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" width="80" height="80">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    Không tìm thấy phòng {{ $roomType->name }} nào
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Thử thay đổi bộ lọc tìm kiếm hoặc xóa một số điều kiện lọc.
                </p>

                <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}"
                   class="btn mb-8 bg-violet-500 hover:bg-violet-600 text-white">
                    Xóa bộ lọc
                </a>
            </div>
        @endif
    </div>

    <!-- CSS và JavaScript cho nút Nhập Excel -->
    <style>
        #importForm {
            min-width: 200px;
        }
        #importForm.hidden {
            display: none;
        }
        #fileName {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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

    // Animation cho thông báo
    ['notification', 'notification-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('translate-y-0', 'opacity-100');
            el.classList.remove('-translate-y-full', 'opacity-0');
            setTimeout(() => {
                el.classList.add('opacity-0', 'scale-95');
                setTimeout(() => el.remove(), 300);
            }, 5000);
        }
    });

    // Đóng thông báo
    function closeNotification() {
        const el = document.getElementById('notification');
        if (el) {
            el.classList.add('opacity-0', 'scale-95');
            setTimeout(() => el.remove(), 300);
        }
    }

    function closeNotificationError() {
        const el = document.getElementById('notification-error');
        if (el) {
            el.classList.add('opacity-0', 'scale-95');
            setTimeout(() => el.remove(), 300);
        }
    }

    // Chọn tất cả checkbox
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.room-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteButtonVisibility();
        });
    } else {
        console.error('Không tìm thấy selectAll');
    }

    // Cập nhật trạng thái nút xóa
    document.querySelectorAll('.room-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteButtonVisibility);
    });

    function updateDeleteButtonVisibility() {
        const checkedCheckboxes = document.querySelectorAll('.room-checkbox:checked');
        const deleteButton = document.getElementById('deleteSelected');
        if (deleteButton) {
            deleteButton.classList.toggle('hidden', checkedCheckboxes.length === 0);
        }
    }

    // Xác nhận xóa nhiều phòng
    const deleteSelected = document.getElementById('deleteSelected');
    if (deleteSelected) {
        deleteSelected.addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn hành động mặc định
            swalWithBootstrapButtons.fire({
                title: "Bạn có chắc chắn?",
                text: "Hành động này sẽ xóa tất cả các phòng đã chọn!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Xóa!",
                cancelButtonText: "Hủy",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        });
    } else {
        console.error('Không tìm thấy deleteSelected');
    }

    // Toggle form nhập Excel
    const toggleImport = document.getElementById('toggleImport');
    const importForm = document.getElementById('importForm');
    if (toggleImport && importForm) {
        toggleImport.addEventListener('click', function(event) {
            event.stopPropagation(); // Ngăn sự kiện lan ra ngoài
            console.log('Toggle Import clicked'); // Debug
            importForm.classList.toggle('hidden');
        });
    } else {
        console.error('Không tìm thấy toggleImport hoặc importForm');
    }

    // Kiểm tra định dạng file Excel
    const excelFile = document.getElementById('excelFile');
    const fileNameSpan = document.getElementById('fileName');
    const clearFile = document.getElementById('clearFile');
    if (excelFile && fileNameSpan && clearFile) {
        excelFile.addEventListener('change', function() {
            const file = this.files[0];
            const allowedTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            if (file && !allowedTypes.includes(file.type)) {
                swalWithBootstrapButtons.fire({
                    title: 'Lỗi định dạng file',
                    text: 'Vui lòng chọn file Excel có định dạng .xls hoặc .xlsx!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                this.value = ''; // Reset input file
                fileNameSpan.textContent = '';
                fileNameSpan.style.display = 'none';
                clearFile.style.display = 'none';
                return;
            }

            // Cập nhật tên file nếu hợp lệ
            if (file) {
                fileNameSpan.textContent = file.name;
                fileNameSpan.style.display = 'block';
                clearFile.style.display = 'inline-block';
            } else {
                fileNameSpan.textContent = '';
                fileNameSpan.style.display = 'none';
                clearFile.style.display = 'none';
            }
        });

        clearFile.addEventListener('click', function() {
            excelFile.value = ''; // Reset input file
            fileNameSpan.textContent = '';
            fileNameSpan.style.display = 'none';
            this.style.display = 'none'; // Ẩn nút X
        });
    } else {
        console.error('Không tìm thấy excelFile, fileName hoặc clearFile');
    }

    // Đóng form khi click ra ngoài
    document.addEventListener('click', function(event) {
        if (toggleImport && importForm && !toggleImport.contains(event.target) && !importForm.contains(event.target)) {
            importForm.classList.add('hidden');
        }
    });

    // Coming Soon Modal
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

    
</x-app-layout>