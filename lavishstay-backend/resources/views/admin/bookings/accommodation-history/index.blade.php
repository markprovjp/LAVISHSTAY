<x-app-layout>
    <!-- Add CSRF token meta tag at the top -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        .quick-filter-btn.active {
            background-color: rgb(139 92 246);
            color: white;
        }
        
        .room-option:hover {
            background-color: rgb(243 244 246);
        }
        
        .dark .room-option:hover {
            background-color: rgb(55 65 81);
        }
        
        .occupancy-bar {
            height: 4px;
            background-color: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .occupancy-fill {
            height: 100%;
            transition: width 0.3s ease;
        }

        .room-type-header {
            font-weight: bold;
            background-color: #f3f4f6;
            color: #374151;
        }

        .dark .room-type-header {
            background-color: #4b5563;
            color: #f9fafb;
        }
    </style>

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-6">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Lịch sử lưu trú</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Theo dõi lịch sử sử dụng phòng và thông tin khách hàng theo thời gian</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Export button -->
                <button onclick="exportHistory()"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L4 4h2v8h4V4h2L8 0z" />
                    </svg>
                    <span class="max-xs:sr-only">Xuất báo cáo</span>
                </button>

                <!-- Refresh button -->
                <button onclick="refreshHistory()"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 16a7.928 7.928 0 01-3.428-.77l.857-1.807A6.006 6.006 0 0014 8c0-3.309-2.691-6-6-6a6.006 6.006 0 00-5.422 8.572l-1.806.859A7.929 7.929 0 010 8c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z"/>
                    </svg>
                    <span class="max-xs:sr-only">Làm mới</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <!-- Total Bookings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tổng booking</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ number_format($statistics['total_bookings']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Rooms Used -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Phòng đã dùng</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ number_format($statistics['total_rooms']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Guests -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tổng khách</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ number_format($statistics['total_guests']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Doanh thu</p>
                        <p class="text-lg font-semibold text-red-600 dark:text-red-400">{{ number_format($statistics['total_revenue']) }}₫</p>
                    </div>
                </div>
            </div>

            <!-- Occupancy Rate (if specific room selected) -->
            @if($roomId && $statistics['occupancy_rate'] > 0)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tỷ lệ lấp đầy</p>
                        <p class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ $statistics['occupancy_rate'] }}%</p>
                        <div class="occupancy-bar mt-1">
                            <div class="occupancy-fill bg-yellow-500" style="width: {{ $statistics['occupancy_rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Advanced Filters -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6">
            <form method="GET" action="{{ route('admin.accommodation-history') }}" id="filter-form">
                <!-- Quick Date Filters -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lọc nhanh thời gian</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setQuickFilter('this_month')" 
                                class="quick-filter-btn px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 {{ $quickFilter === 'this_month' ? 'active' : '' }}">
                            Tháng này
                        </button>
                        <button type="button" onclick="setQuickFilter('last_month')" 
                                class="quick-filter-btn px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 {{ $quickFilter === 'last_month' ? 'active' : '' }}">
                            Tháng trước
                        </button>
                        <button type="button" onclick="setQuickFilter('custom')" 
                                class="quick-filter-btn px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 {{ $quickFilter === 'custom' || !$quickFilter ? 'active' : '' }}">
                            Tùy chọn
                        </button>
                    </div>
                    <input type="hidden" name="quick_filter" id="quick_filter" value="{{ $quickFilter }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Room Selection - Improved -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chọn phòng</label>
                        <select name="room_id" id="room_select" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <option value="">🏨 Tất cả phòng</option>
                            @foreach($roomsForFilter as $roomTypeName => $floors)
                                <optgroup label="📋 {{ $roomTypeName }}" class="room-type-header">
                                    @foreach($floors as $floorDisplay => $rooms)
                                        <optgroup label="🏢 {{ $floorDisplay }}">
                                            @foreach($rooms as $room)
                                                <option value="{{ $room->room_id }}" {{ $roomId == $room->room_id ? 'selected' : '' }}>
                                                    🚪 {{ $room->room_name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Tổng: {{ collect($roomsForFilter)->flatten(2)->count() }} phòng
                        </p>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Từ ngày</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Đến ngày</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tìm kiếm khách</label>
                        <div class="relative">
                            <input type="text" name="guest_name" value="{{ $guestName }}" placeholder="Tên khách hàng..."
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Search Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mã đặt phòng</label>
                        <input type="text" name="booking_code" value="{{ $bookingCode }}" placeholder="Nhập mã booking..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full btn bg-violet-500 hover:bg-violet-600 text-white">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Xem lịch sử
                        </button>
                    </div>

                    <div class="flex items-end">
                        @if(request()->hasAny(['room_id', 'start_date', 'end_date', 'guest_name', 'booking_code', 'quick_filter']))
                            <a href="{{ route('admin.accommodation-history') }}" 
                               class="w-full btn bg-gray-500 hover:bg-gray-600 text-white text-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Xóa bộ lọc
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Summary -->
        @if($accommodationHistory->count() > 0)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>Kết quả:</strong> Tìm thấy {{ number_format($accommodationHistory->total()) }} lượt lưu trú 
                        từ {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} 
                        đến {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                        @if($roomId)
                            @php
                                $selectedRoom = collect($roomsForFilter)->flatten(2)->firstWhere('room_id', $roomId);
                            @endphp
                            cho phòng <strong>{{ $selectedRoom->room_name ?? 'N/A' }}</strong>
                        @endif
                    </span>
                </div>
                <div class="text-xs text-blue-600 dark:text-blue-400">
                    {{ $statistics['period']['days'] }} ngày được kiểm tra
                </div>
            </div>
        </div>
        @endif

        <!-- History Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="overflow-x-auto">
                <table class="table-auto w-full dark:text-gray-300">
                    <!-- Table header -->
                    <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-4 py-3 text-left">Mã booking</th>
                            <th class="px-4 py-3 text-left">Thông tin khách</th>
                            <th class="px-4 py-3 text-left">Phòng & Loại</th>
                            <th class="px-4 py-3 text-left">Thời gian lưu trú</th>
                            <th class="px-4 py-3 text-center">Số khách</th>
                            <th class="px-4 py-3 text-right">Tổng tiền</th>
                            <th class="px-4 py-3 text-center">Trạng thái</th>
                            <th class="px-4 py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($accommodationHistory as $history)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <!-- Booking Code -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-blue-600 dark:text-blue-400">{{ $history->booking_code }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($history->booking_created_at)->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Guest Information -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $history->guest_name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                📞 {{ $history->guest_phone }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                ✉️ {{ $history->guest_email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Room Information -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">🚪 {{ $history->room_name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                🏢 Tầng {{ $history->floor_number ?? 'N/A' }} 
                                                @if($history->floor_name)
                                                    ({{ $history->floor_name }})
                                                @endif
                                            </div>
                                            <div class="text-xs text-blue-600 dark:text-blue-400">
                                                📋 {{ $history->room_type_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Stay Duration -->
                                <td class="px-4 py-3">
                                    <div class="bg-blue-50 dark:bg-blue-400/20 px-2 py-1 rounded text-xs font-medium text-blue-700 dark:text-blue-400 mb-1">
                                        📅 {{ \Carbon\Carbon::parse($history->check_in_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($history->check_out_date)->format('d/m') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 text-center font-medium">
                                        🌙 {{ \Carbon\Carbon::parse($history->check_in_date)->diffInDays(\Carbon\Carbon::parse($history->check_out_date)) }} đêm
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 text-center">
                                        {{ \Carbon\Carbon::parse($history->check_in_date)->format('Y') }}
                                    </div>
                                </td>

                                <!-- Guest Count -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <div class="bg-purple-100 dark:bg-purple-400/30 px-2 py-1 rounded-full">
                                            <span class="text-sm font-semibold text-purple-700 dark:text-purple-400">
                                                👥 {{ $history->guest_count ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Total Price -->
                                <td class="px-4 py-3 text-right">
                                    <div class="font-semibold text-red-600 dark:text-red-400">
                                        💰 {{ number_format($history->total_price_vnd) }}₫
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $statusConfig = [
                                            'confirmed' => ['class' => 'bg-blue-100 dark:bg-blue-400/30 text-blue-800 dark:text-blue-400', 'text' => '✅ Đã xác nhận'],
                                            'completed' => ['class' => 'bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400', 'text' => '🎉 Hoàn thành'],
                                        ];
                                        $status = $statusConfig[$history->booking_status] ?? ['class' => 'bg-gray-100 dark:bg-gray-400/30 text-gray-800 dark:text-gray-400', 'text' => $history->booking_status];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $status['class'] }}">
                                        {{ $status['text'] }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button onclick="viewBookingDetail({{ $history->booking_id }})" 
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200" 
                                                title="Xem chi tiết">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="printBooking({{ $history->booking_id }})" 
                                                class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" 
                                                title="In hóa đơn">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        <div class="text-lg text-gray-500 dark:text-gray-400 mb-2">🏨 Không có lịch sử lưu trú</div>
                                        <div class="text-sm text-gray-400 dark:text-gray-500">Không tìm thấy dữ liệu lưu trú nào trong khoảng thời gian đã chọn</div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                            Thử điều chỉnh bộ lọc hoặc chọn khoảng thời gian khác
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($accommodationHistory->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Hiển thị {{ number_format($accommodationHistory->firstItem()) }}-{{ number_format($accommodationHistory->lastItem()) }} 
                            của {{ number_format($accommodationHistory->total()) }} kết quả
                        </div>
                        <div class="flex space-x-1">
                            {{ $accommodationHistory->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Booking Detail Modal -->
    <div id="booking-detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Chi tiết lịch sử lưu trú</h3>
                    <button onclick="closeBookingDetail()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="booking-detail-content" class="p-6">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quick filter functionality
        function setQuickFilter(filter) {
            document.getElementById('quick_filter').value = filter;
            
            // Update active state
            document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Auto-set dates for quick filters
            const today = new Date();
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');
            
            if (filter === 'this_month') {
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                startDateInput.value = firstDay.toISOString().split('T')[0];
                endDateInput.value = lastDay.toISOString().split('T')[0];
            } else if (filter === 'last_month') {
                const firstDay = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                const lastDay = new Date(today.getFullYear(), today.getMonth(), 0);
                startDateInput.value = firstDay.toISOString().split('T')[0];
                endDateInput.value = lastDay.toISOString().split('T')[0];
            }
            
            // Auto-submit for quick filters
            if (filter !== 'custom') {
                document.getElementById('filter-form').submit();
            }
        }

        // Export functionality
        function exportHistory() {
            const form = document.getElementById('filter-form');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value.trim()) {
                    params.append(key, value);
                }
            }

            window.open(`{{ route('admin.accommodation-history') }}/export?${params.toString()}`, '_blank');
        }

        // Refresh functionality
        function refreshHistory() {
            location.reload();
        }

        // View booking detail
        function viewBookingDetail(bookingId) {
            showLoading('Đang tải thông tin...');

            fetch(`{{ url('admin/bookings') }}/${bookingId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    document.getElementById('booking-detail-content').innerHTML = generateBookingDetailHTML(data.booking);
                    document.getElementById('booking-detail-modal').classList.remove('hidden');
                } else {
                    showNotification('Không thể tải thông tin đặt phòng', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading booking detail:', error);
                showNotification('Có lỗi xảy ra khi tải thông tin', 'error');
            });
        }

        function closeBookingDetail() {
            document.getElementById('booking-detail-modal').classList.add('hidden');
        }

        function printBooking(bookingId) {
            window.open(`{{ url('admin/bookings') }}/${bookingId}/print`, '_blank');
        }

        // Utility functions
        function generateBookingDetailHTML(booking) {
            return `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Booking Information -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">📋 Thông tin đặt phòng</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Mã đặt phòng:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.booking_code}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Ngày đặt:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${formatDateTime(booking.created_at)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Check-in:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${formatDate(booking.check_in_date)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Check-out:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${formatDate(booking.check_out_date)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Số đêm:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${calculateNights(booking.check_in_date, booking.check_out_date)} đêm</span>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">👤 Thông tin khách hàng</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tên khách:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.guest_name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Email:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.guest_email}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Điện thoại:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.guest_phone}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Số khách:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">👥 ${booking.guest_count || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Room Information -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">🏨 Thông tin phòng</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tên phòng:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">🚪 ${booking.room_names || booking.room_name || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Loại phòng:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">📋 ${booking.room_type_names || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">💰 Thông tin thanh toán</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tổng tiền:</span>
                                <span class="font-semibold text-red-600 dark:text-red-400">${formatCurrency(booking.total_price_vnd)}₫</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Trạng thái:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.payment_status || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button onclick="closeBookingDetail()" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Đóng
                    </button>
                    <button onclick="printBooking(${booking.booking_id || booking.id})" 
                            class="px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white rounded-lg">
                        🖨️ In hóa đơn
                    </button>
                </div>
            `;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function calculateNights(checkIn, checkOut) {
            const start = new Date(checkIn);
            const end = new Date(checkOut);
            const diffTime = Math.abs(end - start);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount || 0);
        }

        function showLoading(message = 'Đang xử lý...') {
            let loadingOverlay = document.getElementById('loading-overlay');
            if (!loadingOverlay) {
                loadingOverlay = document.createElement('div');
                loadingOverlay.id = 'loading-overlay';
                loadingOverlay.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50';
                loadingOverlay.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-900 dark:text-gray-100" id="loading-message">${message}</span>
                    </div>
                `;
                document.body.appendChild(loadingOverlay);
            } else {
                document.getElementById('loading-message').textContent = message;
                loadingOverlay.classList.remove('hidden');
            }
        }

        function hideLoading() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
        }

        function showNotification(message, type = 'info') {
            const existingNotifications = document.querySelectorAll('.notification-toast');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

            const bgColor = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'warning': 'bg-yellow-500',
                'info': 'bg-blue-500'
                }[type] || 'bg-blue-500';

            notification.className += ` ${bgColor} text-white`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
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
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Auto-submit form when room selection changes
        document.getElementById('room_select').addEventListener('change', function() {
            if (this.value !== '') {
                document.getElementById('filter-form').submit();
            }
        });

        // ESC key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBookingDetail();
            }
        });

        // Click outside modal to close
        document.getElementById('booking-detail-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingDetail();
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set focus on search input if no filters are applied
            if (!document.querySelector('input[name="guest_name"]').value && 
                !document.querySelector('input[name="booking_code"]').value &&
                !document.querySelector('select[name="room_id"]').value) {
                document.querySelector('input[name="guest_name"]').focus();
            }

            // Auto-refresh every 5 minutes if no user interaction
            let lastActivity = Date.now();
            let refreshInterval;

            function resetRefreshTimer() {
                lastActivity = Date.now();
                clearTimeout(refreshInterval);
                refreshInterval = setTimeout(() => {
                    if (Date.now() - lastActivity >= 300000) { // 5 minutes
                        location.reload();
                    }
                }, 300000);
            }

            // Track user activity
            ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, resetRefreshTimer, true);
            });

            resetRefreshTimer();
        });

        // Handle form submission with loading state
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            showLoading('Đang tìm kiếm...');
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.querySelector('input[name="guest_name"]').focus();
            }
            
            // Ctrl/Cmd + E to export
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                exportHistory();
            }
            
            // Ctrl/Cmd + R to refresh (override default)
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                refreshHistory();
            }
        });

        // Tooltip functionality for truncated text
        function initTooltips() {
            const truncatedElements = document.querySelectorAll('.truncate');
            truncatedElements.forEach(element => {
                if (element.scrollWidth > element.clientWidth) {
                    element.title = element.textContent;
                }
            });
        }

        // Call tooltip initialization after page load
        window.addEventListener('load', initTooltips);

        // Print functionality
        function printTable() {
            const printWindow = window.open('', '_blank');
            const tableHTML = document.querySelector('.overflow-x-auto').innerHTML;
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Lịch sử lưu trú - ${new Date().toLocaleDateString('vi-VN')}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f5f5f5; font-weight: bold; }
                        .text-center { text-align: center; }
                        .text-right { text-align: right; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <h1>Lịch sử lưu trú</h1>
                    <p>Ngày xuất: ${new Date().toLocaleDateString('vi-VN')} ${new Date().toLocaleTimeString('vi-VN')}</p>
                    ${tableHTML}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }

        // Add print button functionality (if needed)
        function addPrintButton() {
            const actionsContainer = document.querySelector('.grid.grid-flow-col.sm\\:auto-cols-max');
            if (actionsContainer && !document.getElementById('print-table-btn')) {
                const printBtn = document.createElement('button');
                printBtn.id = 'print-table-btn';
                printBtn.onclick = printTable;
                printBtn.className = 'btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300';
                printBtn.innerHTML = `
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M4 0h8v3H4V0zm0 4h8v1H4V4zm0 2h8v1H4V6zm0 2h8v1H4V8zm0 2h8v1H4v-1zm0 2h8v1H4v-1z"/>
                    </svg>
                    <span class="max-xs:sr-only">In bảng</span>
                `;
                actionsContainer.appendChild(printBtn);
            }
        }

        // Initialize additional features
        setTimeout(() => {
            addPrintButton();
        }, 100);

        // Handle responsive table on mobile
        function handleResponsiveTable() {
            const table = document.querySelector('table');
            const container = document.querySelector('.overflow-x-auto');
            
            if (window.innerWidth < 768) {
                container.style.overflowX = 'auto';
                table.style.minWidth = '800px';
            } else {
                container.style.overflowX = 'visible';
                table.style.minWidth = 'auto';
            }
        }

        window.addEventListener('resize', handleResponsiveTable);
        handleResponsiveTable();

        // Smooth scroll to results after form submission
        if (window.location.search && document.querySelector('.bg-blue-50')) {
            setTimeout(() => {
                document.querySelector('.bg-blue-50').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }, 100);
        }

        // Advanced search functionality
        function toggleAdvancedSearch() {
            const advancedSection = document.getElementById('advanced-search');
            const toggleBtn = document.getElementById('toggle-advanced-btn');
            
            if (advancedSection.classList.contains('hidden')) {
                advancedSection.classList.remove('hidden');
                toggleBtn.textContent = 'Ẩn tìm kiếm nâng cao';
            } else {
                advancedSection.classList.add('hidden');
                toggleBtn.textContent = 'Hiện tìm kiếm nâng cao';
            }
        }

        // Real-time search suggestions (debounced)
        let searchTimeout;
        function handleSearchInput(input) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (input.value.length >= 2) {
                    // Could implement search suggestions here
                    console.log('Searching for:', input.value);
                }
            }, 300);
        }

        // Add search input listeners
        document.querySelector('input[name="guest_name"]').addEventListener('input', function() {
            handleSearchInput(this);
        });

        document.querySelector('input[name="booking_code"]').addEventListener('input', function() {
            handleSearchInput(this);
        });

        // Statistics refresh functionality
        function refreshStatistics() {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;
            const roomId = document.querySelector('select[name="room_id"]').value;

            if (startDate && endDate) {
                showLoading('Đang cập nhật thống kê...');
                
                fetch(`{{ route('admin.accommodation-history') }}/statistics`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        start_date: startDate,
                        end_date: endDate,
                        room_id: roomId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        updateStatisticsCards(data.statistics);
                        showNotification('Thống kê đã được cập nhật', 'success');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error refreshing statistics:', error);
                    showNotification('Có lỗi khi cập nhật thống kê', 'error');
                });
            }
        }

        // Update statistics cards
        function updateStatisticsCards(stats) {
            // Update each statistic card with new data
            const cards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-5 > div');
            
            if (cards[0]) {
                cards[0].querySelector('.text-lg.font-semibold').textContent = new Intl.NumberFormat('vi-VN').format(stats.total_bookings);
            }
            if (cards[1]) {
                cards[1].querySelector('.text-lg.font-semibold').textContent = new Intl.NumberFormat('vi-VN').format(stats.total_rooms);
            }
            if (cards[2]) {
                cards[2].querySelector('.text-lg.font-semibold').textContent = new Intl.NumberFormat('vi-VN').format(stats.total_guests);
            }
            if (cards[3]) {
                cards[3].querySelector('.text-lg.font-semibold').textContent = new Intl.NumberFormat('vi-VN').format(stats.total_revenue) + '₫';
            }
        }

        // Export with custom options
        function exportWithOptions() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tùy chọn xuất báo cáo</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Định dạng file</label>
                                <select id="export-format" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md">
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" id="include-summary" checked class="mr-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Bao gồm thống kê tổng hợp</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" id="include-details" checked class="mr-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Bao gồm chi tiết từng booking</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                        <button onclick="this.closest('.fixed').remove()" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            Hủy
                        </button>
                        <button onclick="performExport()" 
                                class="px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white rounded-lg">
                            Xuất báo cáo
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function performExport() {
            const format = document.getElementById('export-format').value;
            const includeSummary = document.getElementById('include-summary').checked;
            const includeDetails = document.getElementById('include-details').checked;
            
            // Close modal
            document.querySelector('.fixed.inset-0').remove();
            
            // Perform export with options
            const form = document.getElementById('filter-form');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value.trim()) {
                    params.append(key, value);
                }
            }
            
            params.append('format', format);
            params.append('include_summary', includeSummary);
            params.append('include_details', includeDetails);

            window.open(`{{ route('admin.accommodation-history') }}/export?${params.toString()}`, '_blank');
            showNotification(`Đang xuất báo cáo định dạng ${format.toUpperCase()}...`, 'info');
        }

        // Initialize page with current date if no dates are set
        if (!document.querySelector('input[name="start_date"]').value) {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            document.querySelector('input[name="start_date"]').value = firstDay.toISOString().split('T')[0];
            document.querySelector('input[name="end_date"]').value = lastDay.toISOString().split('T')[0];
        }

        // Show loading on page navigation
        window.addEventListener('beforeunload', function() {
            showLoading('Đang chuyển trang...');
        });

        // Performance monitoring
        if (window.performance) {
            window.addEventListener('load', function() {
                const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
                console.log(`Page loaded in ${loadTime}ms`);
                
                if (loadTime > 3000) {
                    showNotification('Trang tải chậm, vui lòng kiểm tra kết nối mạng', 'warning');
                }
            });
        }
    </script>
</x-app-layout>