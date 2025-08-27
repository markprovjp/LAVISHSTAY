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
                                class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors duration-200">
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
                                <span class="ml-1 text-gray-500 dark:text-gray-400 font-medium">{{ $roomType->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold mb-2">
                    Danh sách phòng {{ $roomType->name }}
                </h1>
                <div class="flex items-center space-x-4 text-sm">
                    <p class="text-gray-600 dark:text-gray-400">
                        Tìm thấy <span class="font-semibold text-violet-600 dark:text-violet-400">{{ $rooms->total() }}</span> phòng thuộc loại {{ $roomType->name }}
                    </p>
                    <div class="h-4 w-px bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-gray-600 dark:text-gray-400">Có sẵn</span>
                        <div class="w-3 h-3 bg-red-500 rounded-full ml-4"></div>
                        <span class="text-gray-600 dark:text-gray-400">Bảo trì</span>
                    </div>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col cursor-pointer sm:auto-cols-max justify-start sm:justify-end gap-3">
                @if($rooms->total() >= $roomType->total_room)
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3">
                        <span class="text-amber-700 dark:text-amber-300 font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Đã đạt giới hạn tối đa phòng
                        </span>
                    </div>
                @else
                    <div class="flex gap-3">
                        <a href="{{ route('admin.rooms.create', $roomType->room_type_id) }}"
                        class="btn bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                                <path
                                    d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                            </svg>
                            <span class="ml-2 font-medium">Thêm phòng</span>
                        </a>
                        <div class="relative">
                            <button type="button" id="toggleImport" class="btn bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center">
                                <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                                    <path d="M7 0h2v8l2-2 1.4 1.4L8 12 3.6 7.4 5 6l2 2V0zm-7 14h16v2H0v-2z" />
                                </svg>
                                <span class="ml-2 font-medium">Nhập Excel</span>
                                <svg class="w-4 h-4 ml-1 transition-transform duration-200" id="importArrow" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="importForm" class="hidden absolute mt-2 right-0 bg-white dark:bg-gray-800 shadow-2xl rounded-xl p-6 z-20 min-w-[300px] border border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Nhập dữ liệu từ Excel</h3>
                                <form action="{{ route('admin.rooms.import-excel', ['room_type_id' => $roomType->room_type_id]) }}" method="POST" enctype="multipart/form-data" id="excelForm">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chọn file Excel</label>
                                        <div class="relative">
                                            <input type="file" name="excel_file" id="excelFile" accept=".xls,.xlsx" class="hidden">
                                            <div id="fileDropZone" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-violet-500 dark:hover:border-violet-400 transition-colors duration-200">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Kéo thả file hoặc <span class="text-violet-600 dark:text-violet-400 font-medium">click để chọn</span></p>
                                                <p class="text-xs text-gray-500 mt-1">Hỗ trợ: .xls, .xlsx</p>
                                            </div>
                                        </div>
                                        <div id="fileInfo" class="hidden mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span id="fileName" class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                                                </div>
                                                <button type="button" id="clearFile" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full btn bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Xác nhận nhập dữ liệu
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div id="notification" class="transform transition-all duration-300 ease-out mb-6 flex items-start p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-l-4 border-green-500 shadow-lg">
                <div class="flex items-center justify-center w-10 h-10 text-green-500 bg-green-100 dark:bg-green-800/50 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4 mr-8 flex-1">
                    <h3 class="font-semibold text-green-800 dark:text-green-200">Thành công!</h3>
                    <div class="text-sm text-green-700 dark:text-green-300 mt-1">{{ session('success') }}</div>
                </div>
                <button onclick="closeNotification()" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div id="notification-error" class="transform transition-all duration-300 ease-out mb-6 flex items-start p-4 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-l-4 border-red-500 shadow-lg relative">
                <div class="flex items-center justify-center w-10 h-10 text-red-500 bg-red-100 dark:bg-red-800/50 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4 mr-8 flex-1">
                    <h3 class="font-semibold text-red-800 dark:text-red-200">Lỗi!</h3>
                    <div class="text-sm text-red-700 dark:text-red-300 mt-1">{{ session('error') }}</div>
                </div>
                <button onclick="closeNotificationError()" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-8 overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/60 bg-gradient-to-r from-gray-50 to-white dark:from-gray-700 dark:to-gray-800">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-violet-600 dark:text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                    </svg>
                    Tìm kiếm - Bộ lọc
                </h2>
            </div>
            <form method="GET" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                            Tìm kiếm
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tên phòng, tầng, số phòng..."
                            class="form-input block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm 
                            placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                            focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all duration-200">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Lọc theo trạng thái phòng
                        </label>
                        <select name="status"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 
                            dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 w-full px-4 py-3 transition-all duration-200">
                            <option value="">Tất cả trạng thái</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="btn bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 16 16" width="16" height="16">
                            <path
                                d="m14.707 13.293-1.414 1.414-2.4-2.4 1.414-1.414 2.4 2.4ZM6.8 12.6A6 6 0 1 1 12.6 6.8a6 6 0 0 1-5.8 5.8ZM2 6a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z" />
                        </svg>
                        <span class="ml-2 font-medium">Tìm kiếm</span>
                    </button>

                    <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}"
                        class="btn bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 shadow-sm hover:shadow-md transition-all duration-200">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 16 16" width="16" height="16">
                            <path
                                d="M12.72 3.293a1 1 0 010 1.414L9.414 8l3.306 3.293a1 1 0 01-1.414 1.414L8 9.414l-3.293 3.293a1 1 0 01-1.414-1.414L6.586 8 3.293 4.707a1 1 0 011.414-1.414L8 6.586l3.293-3.293a1 1 0 011.414 0z" />
                        </svg>
                        <span class="ml-2">Xóa bộ lọc</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Rooms Grid with Delete Option -->
        @if ($rooms->count() > 0)
            <form action="{{ route('admin.rooms.destroy.multiple', $roomType->room_type_id) }}" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                
                <!-- Enhanced Select All Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="checkbox" id="selectAll"
                                    class="h-6 w-6 rounded-md border-2 border-gray-400 dark:border-gray-500 text-violet-600 focus:ring-violet-500 focus:ring-2 bg-white dark:bg-gray-700" />
                            </div>
                            <div class="flex flex-col">
                                <label for="selectAll" class="text-base font-medium text-gray-900 dark:text-gray-100 cursor-pointer">
                                    Chọn tất cả phòng
                                </label>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Trang {{ $rooms->currentPage() }} - Chọn tất cả {{ $rooms->count() }} phòng hiển thị
                                </span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3">
                            <div id="selectionCount" class="hidden bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300 px-4 py-2 rounded-lg border border-violet-200 dark:border-violet-800">
                                <span class="font-medium">Đã chọn: <span id="selectedCount">0</span> phòng</span>
                            </div>
                            <button type="button" id="deleteSelected" 
                                class="hidden btn bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Xóa đã chọn</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rooms Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach ($rooms as $room)
                        <div class="group bg-white dark:bg-gray-800 shadow-lg hover:shadow-2xl rounded-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 dark:border-gray-700 relative room-card">
                            <!-- Enhanced Selection Checkbox -->
                            <div class="absolute top-4 left-4 z-30">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="room_ids[]" value="{{ $room->room_id }}"
                                        class="room-checkbox h-6 w-6 rounded-md border-2 border-white dark:border-gray-300 text-violet-600 focus:ring-violet-500 focus:ring-2 bg-white dark:bg-gray-700 shadow-lg" />
                                </label>
                            </div>

                            <!-- Room Image with Overlay -->
                            <div class="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                @if ($room->image)
                                    <img src="{{ $room->image }}" alt="{{ $room->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 mx-auto mb-2 text-gray-400 dark:text-gray-500" fill="currentColor"
                                                viewBox="0 0 20 20" width="16" height="16">
                                                <path fill-rule="evenodd"
                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Chưa có hình ảnh</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium shadow-lg backdrop-blur-sm
                                        @if ($room->status == 'available') 
                                            bg-green-100/90 text-green-800 border border-green-200
                                            dark:bg-green-900/50 dark:text-green-300 dark:border-green-700
                                        @else
                                            bg-red-100/90 text-red-800 border border-red-200
                                            dark:bg-red-900/50 dark:text-red-300 dark:border-red-700
                                        @endif">
                                        <div class="w-2 h-2 rounded-full mr-2
                                            @if ($room->status == 'available') bg-green-500 @else bg-red-500 @endif">
                                        </div>
                                        {{ $statusOptions[$room->status] ?? $room->status }}
                                    </span>
                                </div>

                                <!-- Rating Badge (if exists) -->
                                @if ($room->rating)
                                    <div class="absolute bottom-4 right-4">
                                        <div class="flex items-center bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm px-2 py-1 rounded-lg shadow-lg border border-white/20">
                                            <svg class="w-4 h-4 text-yellow-400 fill-current mr-1" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $room->rating }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Room Info -->
                            <div class="p-5">
                                <div class="mb-3">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2 line-clamp-1">
                                        {{ $room->name }}
                                    </h3>
                                    
                                    <!-- Room Details -->
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2 text-violet-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="font-medium">Tầng {{ $room->floor->floor_name }}</span>
                                            <span class="mx-2">•</span>
                                            <span>Phòng {{ $room->floor->floor_number }}</span>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2 text-violet-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                            </svg>
                                            <span>1-{{ $room->roomType->max_guests ?? 0 }} khách</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $room->roomType->room_area ?? 'N/A' }}m²</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price and Actions -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex flex-col">
                                        <div class="flex items-baseline">
                                            <span class="text-2xl font-bold text-violet-600 dark:text-violet-400">
                                                {{ number_format($room->roomType->base_price ?? 0, 0, ',', '.') }}
                                            </span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">VND</span>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">mỗi đêm</span>
                                    </div>

                                    <a href="{{ route('admin.rooms.show', $room->room_id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>

            <!-- Enhanced Pagination -->
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Hiển thị <span class="font-medium">{{ $rooms->firstItem() ?? 0 }}</span> đến 
                        <span class="font-medium">{{ $rooms->lastItem() ?? 0 }}</span> 
                        trong tổng số <span class="font-medium">{{ $rooms->total() }}</span> kết quả
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Trang {{ $rooms->currentPage() }} / {{ $rooms->lastPage() }}
                    </div>
                </div>
                {{ $rooms->links() }}
            </div>
        @else
            <!-- Enhanced Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900/20 dark:to-purple-900/20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-violet-400 dark:text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        Không tìm thấy phòng {{ $roomType->name }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        Không có phòng nào khớp với tiêu chí tìm kiếm của bạn.<br>
                        Thử thay đổi bộ lọc hoặc xóa một số điều kiện lọc.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}"
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Xóa bộ lọc
                        </a>
                        
                        @if($rooms->total() < $roomType->total_room)
                            <a href="{{ route('admin.rooms.create', $roomType->room_type_id) }}"
                               class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Thêm phòng mới
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Custom Styles -->
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #8b5cf6;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #7c3aed;
        }
        
        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: #374151;
        }
        
        /* Line clamp utility */
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }

        /* Enhanced file drop zone */
        #fileDropZone.dragover {
            border-color: #8b5cf6;
            background-color: #f3f4f6;
        }
        
        .dark #fileDropZone.dragover {
            background-color: #374151;
        }

        /* Selection counter animation */
        #selectionCount {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Button hover effects */
        .btn {
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }

        /* Card selection glow effect */
        .room-card.room-selected {
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.4), 0 8px 20px rgba(139, 92, 246, 0.2);
            transform: translateY(-2px);
        }

        /* Notification animations */
        #notification, #notification-error {
            animation: notificationSlide 0.5s ease-out;
        }
        
        @keyframes notificationSlide {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Checkbox styling using standard approach */
        .room-checkbox:checked {
            background-color: #8b5cf6;
            border-color: #8b5cf6;
        }

        #selectAll:checked {
            background-color: #8b5cf6;
            border-color: #8b5cf6;
        }

        /* Indeterminate state for select all */
        #selectAll:indeterminate {
            background-color: #8b5cf6;
            border-color: #8b5cf6;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 16 16'%3e%3cpath stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 8h8'/%3e%3c/svg%3e");
        }
    </style>

    <!-- Enhanced JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced SweetAlert2 configuration
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white mx-2 px-6 py-3 rounded-lg font-medium shadow-lg transition-all duration-200",
                cancelButton: "btn bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 mx-2 px-6 py-3 rounded-lg font-medium shadow-sm transition-all duration-200"
            },
            buttonsStyling: false,
            background: 'white',
            backdrop: 'rgba(0,0,0,0.4)',
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        });

        // Enhanced notification animations
        ['notification', 'notification-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(() => {
                    el.style.transform = 'translateY(-20px)';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 5000);
            }
        });

        // Close notification functions
        window.closeNotification = function() {
            const el = document.getElementById('notification');
            if (el) {
                el.style.transform = 'translateY(-20px)';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }
        };

        window.closeNotificationError = function() {
            const el = document.getElementById('notification-error');
            if (el) {
                el.style.transform = 'translateY(-20px)';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }
        };

        // Enhanced checkbox selection logic
        const selectAll = document.getElementById('selectAll');
        const deleteButton = document.getElementById('deleteSelected');
        const selectionCount = document.getElementById('selectionCount');
        const selectedCountSpan = document.getElementById('selectedCount');

        function updateSelectionUI() {
            const roomCheckboxes = document.querySelectorAll('.room-checkbox');
            const checkedBoxes = document.querySelectorAll('.room-checkbox:checked');
            const count = checkedBoxes.length;
            const totalCheckboxes = roomCheckboxes.length;
            
            // Update counter
            if (selectedCountSpan) {
                selectedCountSpan.textContent = count;
            }
            
            // Show/hide elements with animation
            if (count > 0) {
                if (selectionCount) selectionCount.classList.remove('hidden');
                if (deleteButton) deleteButton.classList.remove('hidden');
            } else {
                if (selectionCount) selectionCount.classList.add('hidden');
                if (deleteButton) deleteButton.classList.add('hidden');
            }
            
            // Update select all checkbox state
            if (selectAll) {
                if (count === totalCheckboxes && totalCheckboxes > 0) {
                    selectAll.checked = true;
                    selectAll.indeterminate = false;
                } else if (count > 0) {
                    selectAll.checked = false;
                    selectAll.indeterminate = true;
                } else {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                }
            }
            
            // Add visual feedback to selected cards
            roomCheckboxes.forEach(checkbox => {
                const card = checkbox.closest('.room-card');
                if (card) {
                    if (checkbox.checked) {
                        card.classList.add('room-selected');
                    } else {
                        card.classList.remove('room-selected');
                    }
                }
            });
        }

        // Select all functionality
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const roomCheckboxes = document.querySelectorAll('.room-checkbox');
                roomCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectionUI();
            });
        }

        // Setup individual checkbox listeners
        function setupCheckboxListeners() {
            const roomCheckboxes = document.querySelectorAll('.room-checkbox');
            roomCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function() {
                    updateSelectionUI();
                });
            });
            // Initial UI update
            updateSelectionUI();
        }

        // Setup checkbox listeners
        setupCheckboxListeners();

        // Enhanced delete confirmation
        if (deleteButton) {
            deleteButton.addEventListener('click', function(event) {
                event.preventDefault();
                const count = document.querySelectorAll('.room-checkbox:checked').length;
                
                swalWithBootstrapButtons.fire({
                    title: `Xác nhận xóa ${count} phòng?`,
                    html: `
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan đến các phòng này sẽ bị xóa vĩnh viễn.</p>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: `Xóa ${count} phòng`,
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        swalWithBootstrapButtons.fire({
                            title: 'Đang xử lý...',
                            html: 'Vui lòng chờ trong giây lát',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        document.getElementById('deleteForm').submit();
                    }
                });
            });
        }

        // Enhanced Excel import functionality
        const toggleImport = document.getElementById('toggleImport');
        const importForm = document.getElementById('importForm');
        const importArrow = document.getElementById('importArrow');
        
        if (toggleImport && importForm) {
            toggleImport.addEventListener('click', function(event) {
                event.stopPropagation();
                const isHidden = importForm.classList.contains('hidden');
                
                importForm.classList.toggle('hidden');
                
                // Rotate arrow
                if (isHidden) {
                    importArrow.style.transform = 'rotate(180deg)';
                } else {
                    importArrow.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Enhanced file handling
        const excelFile = document.getElementById('excelFile');
        const fileDropZone = document.getElementById('fileDropZone');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const clearFile = document.getElementById('clearFile');

        if (fileDropZone && excelFile) {
            // Click to select file
            fileDropZone.addEventListener('click', () => excelFile.click());
            
            // Drag and drop functionality
            fileDropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            
            fileDropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
            
            fileDropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    excelFile.files = files;
                    handleFileSelection(files[0]);
                }
            });

            // File input change
            excelFile.addEventListener('change', function() {
                if (this.files.length > 0) {
                    handleFileSelection(this.files[0]);
                }
            });
        }

        function handleFileSelection(file) {
            const allowedTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            
            if (!allowedTypes.includes(file.type)) {
                swalWithBootstrapButtons.fire({
                    title: 'Định dạng file không hợp lệ',
                    text: 'Vui lòng chọn file Excel (.xls hoặc .xlsx)',
                    icon: 'error',
                    confirmButtonText: 'Đã hiểu'
                });
                excelFile.value = '';
                return;
            }
            
            // Show file info
            fileName.textContent = file.name;
            fileInfo.classList.remove('hidden');
            fileDropZone.innerHTML = `
                <svg class="w-8 h-8 mx-auto mb-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-green-600 dark:text-green-400 font-medium">File đã được chọn</p>
                <p class="text-xs text-gray-500 mt-1">Click để chọn file khác</p>
            `;
        }

        // Clear file functionality
        if (clearFile) {
            clearFile.addEventListener('click', function(e) {
                e.stopPropagation();
                excelFile.value = '';
                fileInfo.classList.add('hidden');
                fileDropZone.innerHTML = `
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Kéo thả file hoặc <span class="text-violet-600 dark:text-violet-400 font-medium">click để chọn</span></p>
                    <p class="text-xs text-gray-500 mt-1">Hỗ trợ: .xls, .xlsx</p>
                `;
            });
        }

        // Close import form when clicking outside
        document.addEventListener('click', function(event) {
            if (toggleImport && importForm && 
                !toggleImport.contains(event.target) && 
                !importForm.contains(event.target)) {
                importForm.classList.add('hidden');
                if (importArrow) importArrow.style.transform = 'rotate(0deg)';
            }
        });
    });
    </script>
    
</x-app-layout>