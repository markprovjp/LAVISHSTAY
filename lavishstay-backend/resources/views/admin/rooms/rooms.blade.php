<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.rooms') }}" class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Tổng quan phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
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
                <button onclick="showComingSoon('Thêm phòng mới')" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="ml-2">Thêm phòng: {{ $roomType->name }}</span>
                </button>
            </div>
        </div>

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
                               class="form-input w-full">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm mt-4 font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái</label>
                        <select name="status" class="form-select w-full">
                            <option value="">Tất cả trạng thái</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Max Guests -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số khách tối đa</label>
                        <select name="max_guests" class="form-select w-full">
                            <option value="">Tất cả</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ request('max_guests') == $i ? 'selected' : '' }}>
                                    {{ $i }}+ khách
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- View -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hướng nhìn</label>
                        <select name="view" class="form-select w-full">
                            <option value="">Tất cả hướng</option>
                            @foreach($viewOptions as $view)
                                <option value="{{ $view }}" {{ request('view') == $view ? 'selected' : '' }}>
                                    {{ $view }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Check-in Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày nhận phòng</label>
                        <input type="date" name="check_in" value="{{ request('check_in') }}" 
                               class="form-input w-full">
                    </div>

                    <!-- Check-out Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày trả phòng</label>
                        <input type="date" name="check_out" value="{{ request('check_out') }}" 
                               class="form-input w-full">
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giá phòng (VND)</label>
                        <div class="flex space-x-2 gap-4">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                   placeholder="Từ {{ number_format($priceRange['min'], 0, ',', '.') }}" 
                                   class="form-input w-full">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                   placeholder="Đến {{ number_format($priceRange['max'], 0, ',', '.') }}" 
                                   class="form-input w-full">
                        </div>
                    </div>

                    <!-- Size Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diện tích
                    <!-- Size Range -->
                    <div>
                        {{-- <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diện tích (m²)</label> --}}
                        <div class="flex space-x-2 gap-4">
                            <input type="number" name="min_size" value="{{ request('min_size') }}" 
                                   placeholder="Từ {{ $sizeRange['min'] }}" 
                                   class="form-input w-full">
                            <input type="number" name="max_size" value="{{ request('max_size') }}" 
                                   placeholder="Đến {{ $sizeRange['max'] }}" 
                                   class="form-input w-full">
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn me-3 bg-violet-500 hover:bg-violet-600 text-white">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 16 16" width="16" height="16">
                            <path d="m14.707 13.293-1.414 1.414-2.4-2.4 1.414-1.414 2.4 2.4ZM6.8 12.6A6 6 0 1 1 12.6 6.8a6 6 0 0 1-5.8 5.8ZM2 6a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z"/>
                        </svg>
                        <span class="ml-2">Tìm kiếm</span>
                    </button>
                    
                    <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}" class="btn border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 16 16" width="16" height="16">
                            <path d="M12.72 3.293a1 1 0 010 1.414L9.414 8l3.306 3.293a1 1 0 01-1.414 1.414L8 9.414l-3.293 3.293a1 1 0 01-1.414-1.414L6.586 8 3.293 4.707a1 1 0 011.414-1.414L8 6.586l3.293-3.293a1 1 0 011.414 0z"/>
                        </svg>
                        <span class="ml-2">Xóa bộ lọc</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Sort and View Options -->
        <div class="flex px-6 flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="mb-4 sm:mb-0">
                <form method="GET" class="flex items-center space-x-3">
                    @foreach(request()->except(['sort_by', 'sort_order']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    
                    <label class="text-sm text-gray-600 dark:text-gray-400">Sắp xếp theo:</label>
                    <select name="sort_by" onchange="this.form.submit()" class="form-select text-sm">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên phòng</option>
                        <option value="room_number" {{ request('sort_by') == 'room_number' ? 'selected' : '' }}>Số phòng</option>
                        <option value="floor" {{ request('sort_by') == 'floor' ? 'selected' : '' }}>Tầng</option>
                        <option value="base_price_vnd" {{ request('sort_by') == 'base_price_vnd' ? 'selected' : '' }}>Giá phòng</option>
                        <option value="size" {{ request('sort_by') == 'size' ? 'selected' : '' }}>Diện tích</option>
                        <option value="max_guests" {{ request('sort_by') == 'max_guests' ? 'selected' : '' }}>Số khách</option>
                        <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Đánh giá</option>
                    </select>
                    
                    <select name="sort_order" onchange="this.form.submit()" class="form-select text-sm">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                    </select>
                </form>
            </div>
            
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Hiển thị {{ $rooms->firstItem() ?? 0 }} - {{ $rooms->lastItem() ?? 0 }} trong tổng số {{ $rooms->total() }} phòng
            </div>
        </div>

        <!-- Rooms Grid -->
        @if($rooms->count() > 0)
            <div class="grid p-6 grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($rooms as $room)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        <!-- Room Image -->
                        <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                            @if($room->image)
                                <img src="{{ $room->image }}" alt="{{ $room->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($room->status == 'available') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($room->status == 'occupied') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @elseif($room->status == 'maintenance') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @elseif($room->status == 'cleaning') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                    {{ $statusOptions[$room->status] ?? $room->status }}
                                </span>
                            </div>
                        </div>

                        <!-- Room Info -->
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                    {{ $room->name }}
                                </h3>
                                @if($room->rating)
                                    <div class="flex items-center ml-2">
                                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20" width="16" height="16">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-1">{{ $room->rating }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tầng {{ $room->floor }} - Phòng {{ $room->room_number }}
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                    </svg>
                                    {{ $room->max_guests }} khách • {{ $room->size }}m²
                                </div>

                                @if($room->view)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $room->view }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-2xl font-bold text-violet-600 dark:text-violet-400">
                                        {{ number_format($room->base_price_vnd, 0, ',', '.') }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">VND/đêm</span>
                                </div>
                                
                                <a href="{{ route('admin.rooms.show', $room->room_id) }}" 
                                   class="btn bg-violet-500 hover:bg-violet-600 text-white text-sm px-3 py-1.5">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $rooms->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="80" height="80">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    Không tìm thấy phòng {{ $roomType->name }} nào
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Thử thay đổi bộ lọc tìm kiếm hoặc xóa một số điều kiện lọc.
                </p>
                
                <a href="{{ route('admin.rooms.by-type', $roomType->room_type_id) }}" class="btn mb-8 bg-violet-500 hover:bg-violet-600 text-white">
                    Xóa bộ lọc
                </a>
            </div>
        @endif

    </div>

    <!-- Coming Soon Modal Script -->
    <script>
        function showComingSoon(feature) {
            alert(`Chức năng "${feature}" đang được phát triển và sẽ sớm ra mắt!`);
        }
    </script>

</x-app-layout>
