<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard Tổng Quan</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Theo dõi hiệu suất kinh doanh khách sạn</p>
            </div>

            <!-- Dashboard Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Period Filter -->
                <select id="period-filter" class="form-select rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                    <option value="7">7 ngày qua</option>
                    <option value="30" selected>30 ngày qua</option>
                    <option value="90">90 ngày qua</option>
                </select>

                <!-- Refresh Button -->
                <button onclick="refreshDashboard()" class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8c1.8 0 3.4-.6 4.7-1.6L11 12.7c-.9.7-2 1.1-3 1.1-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5h-2l3 3 3-3h-2c0-4.4-3.6-8-8-8z"/>
                    </svg>
                    <span class="ml-2">Làm mới</span>
                </button>
            </div>
        </div>

        <!-- 1. TỔNG QUAN KINH DOANH - Business Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Phòng khả dụng -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phòng khả dụng</p>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-semibold text-green-600 dark:text-green-400" id="rooms-available">
                                {{ $businessSummary['rooms']['available'] }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                / {{ $businessSummary['rooms']['total'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phòng đang sử dụng -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phòng đang sử dụng</p>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400" id="rooms-occupied">
                                {{ $businessSummary['rooms']['occupied'] }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                ({{ $businessSummary['occupancy_rate'] }}%)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doanh thu hôm nay -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Doanh thu hôm nay</p>
                        <p class="text-2xl font-semibold text-red-600 dark:text-red-400" id="today-revenue">
                            {{ number_format($businessSummary['revenue']['today']) }}₫
                        </p>
                    </div>
                </div>
            </div>

            <!-- Đặt phòng hôm nay -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Đặt phòng hôm nay</p>
                        <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400" id="today-bookings">
                            {{ $businessSummary['bookings']['today'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. CHỈ SỐ KINH DOANH CHUYÊN SÂU -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Tỷ lệ lấp đầy -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tỷ lệ lấp đầy</h3>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $businessSummary['occupancy_rate'] }}%
                    </div>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $businessSummary['occupancy_rate'] }}%"></div>
                </div>
            </div>

            <!-- ADR - Giá phòng trung bình -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">ADR</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Giá phòng trung bình</p>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ number_format($businessSummary['adr']) }}₫
                    </div>
                </div>
            </div>

            <!-- RevPAR -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">RevPAR</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Doanh thu/phòng</p>
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ number_format($businessSummary['revpar']) }}₫
                    </div>
                </div>
            </div>

            <!-- Tỉ lệ hủy phòng -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Tỉ lệ hủy</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Tháng này</p>
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $businessSummary['cancellation_rate'] }}%
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. BIỂU ĐỒ THỐNG KÊ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <!-- Biểu đồ doanh thu -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Doanh thu theo ngày</h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Tháng này: {{ number_format($businessSummary['revenue']['this_month']) }}₫
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Biểu đồ đặt phòng -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Lượt đặt phòng</h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Tháng này: {{ $businessSummary['bookings']['this_month'] }}
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="bookingsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. THỐNG KÊ THEO LOẠI PHÒNG -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Thống kê theo loại phòng</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Doanh thu theo loại phòng -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Doanh thu theo loại phòng</h4>
                    <div class="h-64">
                        <canvas id="roomTypeRevenueChart"></canvas>
                    </div>
                </div>

                <!-- Tỷ lệ lấp đầy theo loại phòng -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Tỷ lệ lấp đầy theo loại</h4>
                    <div class="space-y-4">
                        @foreach($roomTypeStats['occupancy_by_type'] as $roomType)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300">{{ $roomType->name }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $roomType->occupancy_rate }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" 
                                     style="width: {{ $roomType->occupancy_rate }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. HOẠT ĐỘNG LỄ TÂN & CẢNH BÁO -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <!-- Hoạt động lễ tân -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Hoạt động lễ tân hôm nay</h3>
                <div class="space-y-4">
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-400/30 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Check-in hôm nay</span>
                        </div>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $frontDeskStats['checkins_today'] }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-400/30 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H3m13 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Check-out hôm nay</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $frontDeskStats['checkouts_today'] }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-400/30 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Chờ xác nhận</span>
                        </div>
                        <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $frontDeskStats['pending_bookings'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Cảnh báo -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Cảnh báo & Nhắc việc</h3>
                <div class="space-y-4">
                    
                    @if($alerts['rooms_need_cleaning'] > 0)
                    <div class="flex items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border-l-4 border-red-500">
                        <svg class="w-10 h-10 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ $alerts['rooms_need_cleaning'] }} phòng cần dọn dẹp</p>
                            <p class="text-xs text-red-600 dark:text-red-400">Cần xử lý ngay</p>
                        </div>
                    </div>
                    @endif

                    @if($alerts['overdue_payments'] > 0)
                    <div class="flex items-center p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg border-l-4 border-orange-500">
                        <svg class="w-10 h-10 text-orange-600 dark:text-orange-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-orange-800 dark:text-orange-200">{{ $alerts['overdue_payments'] }} thanh toán trễ hạn</p>
                            <p class="text-xs text-orange-600 dark:text-orange-400">Cần liên hệ khách hàng</p>
                        </div>
                    </div>
                    @endif

                    @if($alerts['arriving_tomorrow'] > 0)
                    <div class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-500">
                        <svg class="w-10 h-10 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ $alerts['arriving_tomorrow'] }} khách đến ngày mai</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400">Chuẩn bị phòng</p>
                        </div>
                    </div>
                    @endif

                                        @if($alerts['maintenance_rooms'] > 0)
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-900/20 rounded-lg border-l-4 border-gray-500">
                        <svg class="w-10 h-10 text-gray-600 dark:text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $alerts['maintenance_rooms'] }} phòng đang bảo trì</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Theo dõi tiến độ</p>
                        </div>
                    </div>
                    @endif

                    @if($alerts['rooms_need_cleaning'] == 0 && $alerts['overdue_payments'] == 0 && $alerts['maintenance_rooms'] == 0)
                    <div class="flex items-center justify-center p-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Không có cảnh báo nào</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 6. BẢNG DỮ LIỆU CHI TIẾT -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <!-- Đặt phòng mới nhất -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Đặt phòng mới nhất</h3>
                    <a href="{{ route('admin.bookings') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Xem tất cả
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($detailTables['recent_bookings'] as $booking)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $booking->booking_code }}
                                </span>
                                <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                    @if($booking->status == 'confirmed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @elseif($booking->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $booking->guest_name }} • {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($booking->total_price_vnd) }}₫
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>Chưa có đặt phòng nào</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Khách sắp đến hôm nay -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Khách sắp đến hôm nay</h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ count($detailTables['arriving_today']) }} khách
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($detailTables['arriving_today'] as $booking)
                    <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $booking->guest_name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $booking->booking_code }} • {{ $booking->guest_phone }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-green-600 dark:text-green-400 font-medium">
                                Check-in hôm nay
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>Không có khách check-in hôm nay</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 7. THỐNG KÊ KHÁCH HÀNG & TÀI CHÍNH -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Thống kê khách hàng -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Thống kê khách hàng</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Khách mới tháng này</span>
                        <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                            {{ $customerStats['new_customers'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Khách quen</span>
                        <span class="text-lg font-semibold text-green-600 dark:text-green-400">
                            {{ $customerStats['returning_customers'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Chỉ số tài chính -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Chỉ số tài chính tháng này</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Đã thu</p>
                        <p class="text-lg font-bold text-green-600 dark:text-green-400">
                            {{ number_format($financialMetrics['total_collected']) }}₫
                        </p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Chờ thanh toán</p>
                        <p class="text-lg font-bold text-yellow-600 dark:text-yellow-400">
                            {{ number_format($financialMetrics['pending_payments']) }}₫
                        </p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Tổng giá trị booking</p>
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($financialMetrics['total_bookings_value']) }}₫
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 8. TOP KHÁCH HÀNG -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Top khách hàng</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Số lần đặt
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tổng chi tiêu
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($customerStats['top_customers'] as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $customer->guest_name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $customer->guest_email }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $customer->booking_count }} lần
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                                {{ number_format($customer->total_spent) }}₫
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Chưa có dữ liệu khách hàng
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .metric-card {
            transition: transform 0.2s ease-in-out;
        }
        
                .metric-card:hover {
            transform: translateY(-2px);
        }
        
        .progress-bar {
            transition: width 0.3s ease-in-out;
        }
        
        .alert-item {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    @endpush


    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    
    <script>
        console.log(45445);
        
        console.log('chartData:', @json($chartData));
        console.log('roomTypeStats:', @json($roomTypeStats));
        // Global chart configuration
        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
        Chart.defaults.color = document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280';
        
        // Chart data from backend
        const chartData = @json($chartData);
        const roomTypeStats = @json($roomTypeStats);
        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            setupRealTimeUpdates();
            setupPeriodFilter();
        });
        
        function initializeCharts() {
            // 1. Revenue Chart
            initRevenueChart();
            
            // 2. Bookings Chart
            initBookingsChart();
            
            // 3. Room Type Revenue Chart
            initRoomTypeRevenueChart();
        }
        
        function initRevenueChart() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            const revenueData = chartData.revenue.map(item => ({
                x: item.date,
                y: item.revenue
            }));
            console.log('roomTypeStatsssssssss:', revenueData);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: revenueData,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'dd/MM'
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', {
                                        notation: 'compact',
                                        compactDisplay: 'short'
                                    }).format(value) + '₫';
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }
        
        function initBookingsChart() {
            const ctx = document.getElementById('bookingsChart').getContext('2d');
            
            const bookingData = chartData.bookings.map(item => ({
                x: item.date,
                y: item.bookings
            }));
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Số lượt đặt phòng',
                        data: bookingData,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'Đặt phòng: ' + context.parsed.y + ' lượt';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'dd/MM'
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
        
        function initRoomTypeRevenueChart() {
            const ctx = document.getElementById('roomTypeRevenueChart').getContext('2d');
            
            const roomTypeData = roomTypeStats.booking_by_type;
            const labels = roomTypeData.map(item => item.name);
            const revenues = roomTypeData.map(item => item.revenue);
            
            // Generate colors for each room type
            const colors = [
                'rgba(239, 68, 68, 0.8)',   // Red
                'rgba(59, 130, 246, 0.8)',  // Blue
                'rgba(16, 185, 129, 0.8)',  // Green
                'rgba(245, 158, 11, 0.8)',  // Yellow
                'rgba(139, 92, 246, 0.8)',  // Purple
                'rgba(236, 72, 153, 0.8)',  // Pink
            ];
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: revenues,
                        backgroundColor: colors.slice(0, labels.length),
                        borderColor: colors.slice(0, labels.length).map(color => color.replace('0.8', '1')),
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = new Intl.NumberFormat('vi-VN').format(context.parsed);
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${label}: ${value}₫ (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }
        
        // Real-time updates
        function setupRealTimeUpdates() {
            // Update every 30 seconds
            setInterval(updateRealTimeStats, 30000);
        }
        
        function updateRealTimeStats() {
            fetch('{{ route("admin.dashboard.realtime-stats") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update room stats
                        document.getElementById('rooms-available').textContent = data.data.rooms_available;
                        document.getElementById('rooms-occupied').textContent = data.data.rooms_occupied;
                        document.getElementById('today-revenue').textContent = 
                            new Intl.NumberFormat('vi-VN').format(data.data.today_revenue) + '₫';
                        document.getElementById('today-bookings').textContent = data.data.today_bookings;
                    }
                })
                .catch(error => {
                    console.error('Error updating real-time stats:', error);
                });
        }
        
        // Period filter
        function setupPeriodFilter() {
            const periodFilter = document.getElementById('period-filter');
            periodFilter.addEventListener('change', function() {
                updateChartsData(this.value);
            });
        }
        
        function updateChartsData(period) {
            showLoading();
            
            fetch(`{{ route("admin.dashboard.chart-data") }}?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update charts with new data
                        updateRevenueChart(data.data.revenue);
                        updateBookingsChart(data.data.bookings);
                    }
                })
                .catch(error => {
                    console.error('Error updating chart data:', error);
                    showNotification('Có lỗi xảy ra khi cập nhật dữ liệu', 'error');
                })
                .finally(() => {
                    hideLoading();
                });
        }
        
        function updateRevenueChart(newData) {
            // Implementation for updating revenue chart
            // This would require storing chart instances globally
        }
        
        function updateBookingsChart(newData) {
            // Implementation for updating bookings chart
        }
        
        // Dashboard refresh
        function refreshDashboard() {
            showLoading();
            location.reload();
        }
        
        // Utility functions
        function showLoading() {
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.id = 'loading-overlay';
            loadingOverlay.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
                    <div class="spinner"></div>
                    <span class="text-gray-900 dark:text-gray-100">Đang tải dữ liệu...</span>
                </div>
            `;
            document.body.appendChild(loadingOverlay);
        }
        
        function hideLoading() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
        }
        
        function showNotification(message, type = 'info') {
            // Create notification toast
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
            
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
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }
        
                // Dark mode chart updates
        function updateChartsForTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            Chart.defaults.color = isDark ? '#9CA3AF' : '#6B7280';
            
            // Update all chart instances if they exist
            Chart.instances.forEach(chart => {
                chart.options.plugins.legend.labels.color = isDark ? '#9CA3AF' : '#6B7280';
                chart.update();
            });
        }
        
        // Listen for theme changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    updateChartsForTheme();
                }
            });
        });
        
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // F5 or Ctrl+R to refresh
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                e.preventDefault();
                refreshDashboard();
            }
            
            // Ctrl+D to toggle dark mode (if implemented)
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                // Toggle dark mode implementation
            }
        });
        
        // Auto-refresh every 5 minutes
        setInterval(function() {
            updateRealTimeStats();
        }, 300000);
        
        // Page visibility API - pause updates when tab is not active
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Pause updates
                console.log('Dashboard paused - tab not active');
            } else {
                // Resume updates
                console.log('Dashboard resumed - tab active');
                updateRealTimeStats();
            }
        });
    </script>
 
</x-app-layout>
