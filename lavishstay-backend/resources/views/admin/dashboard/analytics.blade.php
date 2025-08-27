<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Analytics Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Phân Tích Chi Tiết</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Phân tích hiệu suất kinh doanh và hành vi khách hàng</p>
            </div>

            <!-- Analytics Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Period Filter -->
                <select id="period-filter" class="form-select rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                    <option value="7">7 ngày qua</option>
                    <option value="30" selected>30 ngày qua</option>
                    <option value="90">90 ngày qua</option>
                    <option value="365">365 ngày qua</option>
                </select>

                <!-- Export Button -->
                <button onclick="exportAnalytics()" class="btn bg-green-600 text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                    <svg class="fill-current shrink-0 w-4 h-4 mr-2" viewBox="0 0 16 16">
                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                    </svg>
                    <span class="max-xs:sr-only">Export</span>
                </button>

                <!-- Refresh Button -->
                <button onclick="refreshAnalytics()" class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8c1.8 0 3.4-.6 4.7-1.6L11 12.7c-.9.7-2 1.1-3 1.1-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5h-2l3 3 3-3h-2c0-4.4-3.6-8-8-8z"/>
                    </svg>
                    <span class="max-xs:sr-only cursor-pointer">Làm mới</span>
                </button>
            </div>
        </div>

        <!-- 1. PHÂN TÍCH DOANH THU -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Doanh thu theo thời gian -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-red-500">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Doanh Thu Theo Thời Gian</h3>
                <div class="h-80">
                    <canvas id="revenueByTimeChart"></canvas>
                </div>
            </div>
            <!-- Doanh thu theo loại phòng -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Doanh Thu Theo Loại Phòng</h3>
                <div class="h-80">
                    <canvas id="revenueByRoomTypeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Doanh thu theo nguồn đặt -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-green-500">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Doanh Thu Theo Nguồn Đặt</h3>
                <div class="h-80">
                    <canvas id="revenueBySourceChart"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Mức Giá Ưa Chuộng</h3>
                <div class="h-80">
                    <canvas id="pricePreferenceChart"></canvas>
                </div>
            </div>
            <!-- Booking theo nguồn -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Booking Theo Nguồn</h3>
                <div class="h-80">
                    <canvas id="bookingBySourceChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- 1.1. CHI TIẾT DOANH THU -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Chi Tiết Doanh Thu</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Doanh thu theo chính sách -->
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Doanh Thu Theo Chính Sách</h4>
                    <div class="space-y-4">
                        @forelse($revenueAnalysis['by_policy'] ?? [] as $policy)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $policy->policy_name ?? 'N/A' }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ number_format($policy->revenue ?? 0) }}₫ ({{ $policy->completion_rate ?? 0 }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $policy->completion_rate ?? 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>
                <!-- Tỷ lệ phụ thu trẻ em -->
                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Tỷ Lệ Phụ Thu Trẻ Em</h4>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $revenueAnalysis['child_surcharge_ratio'] ?? 0 }}%</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tổng: {{ number_format($revenueAnalysis['child_surcharge_total'] ?? 0) }}₫</p>
                </div>
                <!-- Tổng doanh thu -->
                <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Tổng Doanh Thu</h4>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($revenueAnalysis['total_revenue'] ?? 0) }}₫</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tháng này</p>
                </div>
            </div>
        </div>

        <!-- 2. PHÂN TÍCH BOOKING -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Tỷ lệ booking -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Tỉ Lệ Booking</h3>
                <div class="h-80">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>
            <!-- Nguồn khách hàng -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Nguồn Khách Hàng</h4>
                <div class="h-80">
                    <canvas id="customerSourceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 2.1. CHI TIẾT BOOKING -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Chi Tiết Booking</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Booking trung bình -->
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Booking Trung Bình</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-100 dark:bg-blue-400/30 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Hàng ngày</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ round($bookingAnalysis['avg_daily'] ?? 0, 1) }} lượt</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-100 dark:bg-blue-400/30 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Theo mùa</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ round($bookingAnalysis['avg_seasonal'] ?? 0, 1) }} lượt</span>
                        </div>
                    </div>
                </div>
                <!-- Booking theo loại khách -->
                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Booking Theo Loại Khách</h4>
                    <div class="h-64">
                        <canvas id="bookingByCustomerTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. PHÂN TÍCH CÔNG SUẤT PHÒNG -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Phân Tích Công Suất Phòng</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Công suất theo loại phòng -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Công Suất Theo Loại Phòng</h4>
                    <div class="space-y-4">
                        @forelse($occupancyAnalysis['by_room_type'] ?? [] as $roomType)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $roomType->name ?? 'N/A' }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ $roomType->occupancy_rate ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" style="width: {{ $roomType->occupancy_rate ?? 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có dữ liệu</p>
                        @endforelse
                    </div>
                </div>
                <!-- Biểu đồ công suất hàng ngày -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Công Suất Hàng Ngày</h4>
                    <div class="h-80">
                        <canvas id="dailyOccupancyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-500">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Ngày công suất cao nhất</p>
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $occupancyAnalysis['highest_day']->date ?? 'N/A' }} ({{ $occupancyAnalysis['highest_day']->occupancy_rate ?? 0 }}%)</p>
                    </div>
                </div>
                <div class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border-l-4 border-red-500">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-400/30 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Ngày công suất thấp nhất</p>
                        <p class="text-lg font-bold text-red-600 dark:text-red-400">{{ $occupancyAnalysis['lowest_day']->date ?? 'N/A' }} ({{ $occupancyAnalysis['lowest_day']->occupancy_rate ?? 0 }}%)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. PHÂN TÍCH HÀNH VI KHÁCH HÀNG -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Số đêm lưu trú trung bình</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ round($customerBehavior['avg_stay_length'] ?? 0, 1) }} đêm</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Số khách trung bình/phòng</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ round($customerBehavior['avg_guests_per_room'] ?? 0, 1) }} người</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-400/30 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Mức giá phổ biến nhất</p>
                        <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                            @php
                                $mostPopularPrice = collect($customerBehavior['price_preference'] ?? [])->sortByDesc('bookings')->first();
                            @endphp
                            {{ $mostPopularPrice->price_range ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. PHÂN TÍCH THỜI ĐIỂM ĐẶC BIỆT -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Phân Tích Thời Điểm Đặc Biệt</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Doanh thu theo lễ hội -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Doanh Thu Theo Lễ Hội</h4>
                    <div class="h-80">
                        <canvas id="festivalRevenueChart"></canvas>
                    </div>
                </div>
                <!-- Chi tiết theo kỳ nghỉ lễ -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Chi Tiết Theo Kỳ Nghỉ Lễ</h4>
                    <div class="space-y-4">
                        @forelse($specialPeriodAnalysis['holidays'] ?? [] as $holiday)
                            <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border-l-4 border-yellow-500">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $holiday->period ?? 'N/A' }}</h5>
                                    <span class="text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 px-2 py-1 rounded-full">
                                        {{ $holiday->bookings ?? 0 }} booking
                                    </span>
                                </div>
                                <p class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ number_format($holiday->revenue ?? 0) }}₫</p>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p>Chưa có dữ liệu lễ hội</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. PHÂN TÍCH NGUYÊN NHÂN HỦY PHÒNG -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Phân Tích Nguyên Nhân Hủy Phòng</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Hủy phòng theo nguồn -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Hủy Phòng Theo Nguồn</h4>
                    <div class="h-80">
                        <canvas id="cancellationBySourceChart"></canvas>
                    </div>
                </div>
                <!-- Nguyên nhân hủy phòng -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Nguyên Nhân Hủy Phòng</h4>
                    <div class="space-y-4">
                        @forelse($cancellationAnalysis['reasons'] ?? [] as $reason)
                            <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <div class="flex-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $reason->reason ?? 'N/A' }}</span>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                        <div class="bg-red-600 h-2 rounded-full" style="width: {{ $reason->percentage ?? 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="ml-4 text-right">
                                    <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ $reason->count ?? 0 }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $reason->percentage ?? 0 }}%</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p>Không có dữ liệu hủy phòng</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Thời gian hủy trung bình</p>
                        <p class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ round($cancellationAnalysis['avg_cancellation_days'] ?? 0, 1) }} ngày trước check-in</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. TOP KHÁCH HÀNG VIP -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Top Khách Hàng VIP</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Số lần đặt
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tổng chi tiêu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Hạng
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($customerAnalysis['top_customers'] ?? [] as $index => $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($index < 3)
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                            @if($index == 0) bg-yellow-100 dark:bg-yellow-900/30
                                            @elseif($index == 1) bg-gray-100 dark:bg-gray-900/30
                                            @else bg-orange-100 dark:bg-orange-900/30 @endif">
                                            <span class="text-sm font-bold
                                                @if($index == 0) text-yellow-600 dark:text-yellow-400
                                                @elseif($index == 1) text-gray-600 dark:text-gray-400
                                                @else text-orange-600 dark:text-orange-400 @endif">
                                                {{ $index + 1 }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400 mr-3">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $customer->guest_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $customer->guest_email ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $customer->booking_count ?? 0 }} lần
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                                {{ number_format($customer->total_spent ?? 0) }}₫
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $totalSpent = $customer->total_spent ?? 0;
                                    if ($totalSpent >= 10000000) $rank = 'Diamond';
                                    elseif ($totalSpent >= 5000000) $rank = 'Platinum';
                                    elseif ($totalSpent >= 2000000) $rank = 'Gold';
                                    elseif ($totalSpent >= 1000000) $rank = 'Silver';
                                    else $rank = 'Bronze';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($rank == 'Diamond') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($rank == 'Platinum') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                    @elseif($rank == 'Gold') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @elseif($rank == 'Silver') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400 @endif">
                                    {{ $rank }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Chưa có dữ liệu khách hàng
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Custom CSS -->
    <style>
        .btn {
            @apply inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
        }
        .form-select {
            @apply block w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global variables
        let charts = {};
        
        // Chart data from backend
        const analyticsData = @json($analyticsData);
        const revenueAnalysis = @json($revenueAnalysis);
        const bookingAnalysis = @json($bookingAnalysis);
        const occupancyAnalysis = @json($occupancyAnalysis);
        const customerBehavior = @json($customerBehavior);
        const specialPeriodAnalysis = @json($specialPeriodAnalysis);
        const cancellationAnalysis = @json($cancellationAnalysis);

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeAllCharts();
            initializeEventHandlers();
        });

        // Initialize all charts
        function initializeAllCharts() {
            initRevenueByTimeChart();
            initRevenueByRoomTypeChart();
            initRevenueBySourceChart();
            initPricePreferenceChart();
            initBookingBySourceChart();
            initBookingStatusChart();
            initCustomerSourceChart();
            initBookingByCustomerTypeChart();
            initDailyOccupancyChart();
            initFestivalRevenueChart();
            initCancellationBySourceChart();
        }

        // Revenue by time chart
        function initRevenueByTimeChart() {
            const ctx = document.getElementById('revenueByTimeChart');
            if (!ctx) return;
            
            charts.revenueByTime = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: analyticsData.revenue.by_time.daily.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: analyticsData.revenue.by_time.daily.map(item => item.revenue),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Revenue by room type chart
        function initRevenueByRoomTypeChart() {
            const ctx = document.getElementById('revenueByRoomTypeChart');
            if (!ctx) return;
            
            const colors = [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ];

            charts.revenueByRoomType = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: analyticsData.revenue.by_room_type.map(item => item.name),
                    datasets: [{
                        data: analyticsData.revenue.by_room_type.map(item => item.revenue),
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
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
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = new Intl.NumberFormat('vi-VN').format(context.parsed);
                                    return context.label + ': ' + value + '₫';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Revenue by source chart
        function initRevenueBySourceChart() {
            const ctx = document.getElementById('revenueBySourceChart');
            if (!ctx) return;
            
            charts.revenueBySource = new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: analyticsData.revenue.by_source.map(item => item.source),
                    datasets: [{
                        data: analyticsData.revenue.by_source.map(item => item.revenue),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = new Intl.NumberFormat('vi-VN').format(context.parsed);
                                    return context.label + ': ' + value + '₫';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Price preference chart
        function initPricePreferenceChart() {
            const ctx = document.getElementById('pricePreferenceChart');
            if (!ctx) return;
            
            charts.pricePreference = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: analyticsData.price_preference.map(item => item.price_range),
                    datasets: [{
                        label: 'Số lượng booking',
                        data: analyticsData.price_preference.map(item => item.count),
                        backgroundColor: 'rgba(139, 92, 246, 0.8)',
                        borderColor: 'rgb(139, 92, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Booking by source chart
        function initBookingBySourceChart() {
            const ctx = document.getElementById('bookingBySourceChart');
            if (!ctx) return;
            
            charts.bookingBySource = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: analyticsData.booking.by_source.map(item => item.source),
                    datasets: [{
                        data: analyticsData.booking.by_source.map(item => item.count),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Booking status chart
        function initBookingStatusChart() {
            const ctx = document.getElementById('bookingStatusChart');
            if (!ctx) return;
            
            charts.bookingStatus = new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: analyticsData.booking.status.map(item => item.status),
                    datasets: [{
                        data: analyticsData.booking.status.map(item => item.count),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(59, 130, 246, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Customer source chart
        function initCustomerSourceChart() {
            const ctx = document.getElementById('customerSourceChart');
            if (!ctx) return;
            
            charts.customerSource = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: analyticsData.customer_source.map(item => item.customer_source),
                    datasets: [{
                        label: 'Số lượng khách',
                        data: analyticsData.customer_source.map(item => item.count),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Booking by customer type chart
        function initBookingByCustomerTypeChart() {
            const ctx = document.getElementById('bookingByCustomerTypeChart');
            if (!ctx) return;
            
            charts.bookingByCustomerType = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: analyticsData.booking.by_customer_type.map(item => item.customer_type),
                    datasets: [{
                        data: analyticsData.booking.by_customer_type.map(item => item.count),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Daily occupancy chart
        function initDailyOccupancyChart() {
            const ctx = document.getElementById('dailyOccupancyChart');
            if (!ctx) return;
            
            charts.dailyOccupancy = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: analyticsData.occupancy.daily.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Tỷ lệ lấp đầy (%)',
                        data: analyticsData.occupancy.daily.map(item => item.occupancy_rate),
                        borderColor: 'rgb(139, 92, 246)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Festival revenue chart
        function initFestivalRevenueChart() {
            const ctx = document.getElementById('festivalRevenueChart');
            if (!ctx) return;
            
            charts.festivalRevenue = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: analyticsData.festival_revenue.map(item => item.period),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: analyticsData.festival_revenue.map(item => item.revenue),
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(59, 130, 246, 0.8)'
                        ],
                        borderColor: [
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)',
                            'rgb(59, 130, 246)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Cancellation by source chart
        function initCancellationBySourceChart() {
            const ctx = document.getElementById('cancellationBySourceChart');
            if (!ctx) return;
            
            charts.cancellationBySource = new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: analyticsData.cancellation_by_source.map(item => item.source),
                    datasets: [{
                        data: analyticsData.cancellation_by_source.map(item => item.count),
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Event handlers
        function initializeEventHandlers() {
            const periodFilter = document.getElementById('period-filter');
            if (periodFilter) {
                periodFilter.addEventListener('change', function() {
                    updateAnalyticsData(this.value);
                });
            }
        }

        // Update analytics data
        function updateAnalyticsData(period) {
            fetch(`/admin/analytics/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update charts with new data
                        updateAllCharts(data.data);
                    }
                })
                .catch(error => {
                    console.error('Error updating analytics data:', error);
                });
        }

        // Update all charts
        function updateAllCharts(newData) {
            // Update revenue by time chart
            if (charts.revenueByTime && newData.revenue && newData.revenue.by_time) {
                charts.revenueByTime.data.labels = newData.revenue.by_time.daily.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' });
                });
                charts.revenueByTime.data.datasets[0].data = newData.revenue.by_time.daily.map(item => item.revenue);
                charts.revenueByTime.update();
            }

            // Update other charts similarly...
            Object.keys(charts).forEach(chartKey => {
                if (charts[chartKey]) {
                    charts[chartKey].update();
                }
            });
        }

        // Refresh analytics
        function refreshAnalytics() {
            const refreshBtn = document.querySelector('button[onclick="refreshAnalytics()"]');
            if (refreshBtn) {
                const originalContent = refreshBtn.innerHTML;
                refreshBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                refreshBtn.disabled = true;

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }

        // Export analytics
        function exportAnalytics() {
            const exportBtn = document.querySelector('button[onclick="exportAnalytics()"]');
            if (exportBtn) {
                const originalContent = exportBtn.innerHTML;
                
                exportBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Đang xuất...';
                exportBtn.disabled = true;

                // Create download link
                const link = document.createElement('a');
                link.href = `/admin/analytics/export-excel`;
                link.download = `analytics-report-${new Date().toISOString().split('T')[0]}.xlsx`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                setTimeout(() => {
                    exportBtn.innerHTML = originalContent;
                    exportBtn.disabled = false;
                }, 2000);
            }
        }

        // Dark mode chart updates
        function updateChartsForTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e5e7eb' : '#374151';
            const gridColor = isDark ? '#374151' : '#e5e7eb';

            Object.keys(charts).forEach(chartKey => {
                const chart = charts[chartKey];
                if (chart && chart.options.scales) {
                    if (chart.options.scales.x) {
                        chart.options.scales.x.ticks.color = textColor;
                        chart.options.scales.x.grid.color = gridColor;
                    }
                    if (chart.options.scales.y) {
                        chart.options.scales.y.ticks.color = textColor;
                        chart.options.scales.y.grid.color = gridColor;
                    }
                    chart.update();
                }
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
    </script>
</x-app-layout>