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

                <!-- Refresh Button -->
                <button onclick="refreshAnalytics()" class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8c1.8 0 3.4-.6 4.7-1.6L11 12.7c-.9.7-2 1.1-3 1.1-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5h-2l3 3 3-3h-2c0-4.4-3.6-8-8-8z"/>
                    </svg>
                    <span class="ml-2">Làm mới</span>
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
                        @foreach($revenueAnalysis['by_policy'] ?? [] as $policy)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $policy->policy_name ?? 'N/A' }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ number_format($policy->revenue ?? 0) }}₫ ({{ $policy->completion_rate ?? 0 }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $policy->completion_rate ?? 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
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
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 ">
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
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $bookingAnalysis['avg_daily'] ?? 0 }} lượt</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-100 dark:bg-blue-400/30 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Theo mùa</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $bookingAnalysis['avg_seasonal'] ?? 0 }} lượt</span>
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
                        @foreach($occupancyAnalysis['by_room_type'] ?? [] as $roomType)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $roomType->name ?? 'N/A' }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ $roomType->occupancy_rate ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" style="width: {{ $roomType->occupancy_rate ?? 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
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
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $occupancyAnalysis['highest_day']->date ?? 'N/A' }} ({{ $occupancyAnalysis['highest_day']->rate ?? 0 }}%)</p>
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
                        <p class="text-lg font-bold text-red-600 dark:text-red-400">{{ $occupancyAnalysis['lowest_day']->date ?? 'N/A' }} ({{ $occupancyAnalysis['lowest_day']->rate ?? 0 }}%)</p>
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
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $customerBehavior['avg_stay_length'] ?? 0 }} đêm</p>
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
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Số người/phòng trung bình</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $customerBehavior['avg_guests_per_room'] ?? 0 }} người</p>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- 5. PHÂN TÍCH THỜI ĐIỂM ĐẶC BIỆT -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Phân Tích Thời Điểm Đặc Biệt</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Hiệu quả ngày lễ -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Hiệu Quả Ngày Lễ</h4>
                    <div class="space-y-4">
                        @foreach($specialPeriodAnalysis['holidays'] ?? [] as $holiday)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $holiday->period ?? 'N/A' }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ number_format($holiday->revenue ?? 0) }}₫</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" style="width: {{ ($holiday->revenue / ($specialPeriodAnalysis['holidays'][0]->revenue ?? 1)) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- So sánh lễ hội -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">So Sánh Doanh Thu Lễ Hội</h4>
                    <div class="h-80">
                        <canvas id="festivalRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. PHÂN TÍCH NGUYÊN NHÂN HUỶ PHÒNG -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Phân Tích Nguyên Nhân Huỷ Phòng</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tỷ lệ hủy theo kênh -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Tỷ Lệ Hủy Theo Kênh</h4>
                    <div class="h-80">
                        <canvas id="cancellationBySourceChart"></canvas>
                    </div>
                </div>
                <!-- Thời gian hủy trung bình -->
                <div>
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-4">Thời Gian Hủy Trung Bình</h4>
                    <div class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border-l-4 border-red-500">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-400/30 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $cancellationAnalysis['avg_cancellation_days'] ?? 0 }} ngày trước check-in</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lý Do Hủy</h5>
                        @foreach($cancellationAnalysis['reasons'] ?? [] as $reason)
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300">{{ $reason->reason ?? 'N/A' }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $reason->percentage ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full" style="width: {{ $reason->percentage ?? 0 }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
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

    <!-- Chart.js and Adapter -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

    <script>
        // Global chart configuration
        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
        Chart.defaults.color = document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280';

        // Chart data from backend
        const analyticsData = @json($analyticsData);

        // Store chart instances
        const chartInstances = {};

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            setupRealTimeUpdates();
            setupPeriodFilter();
            updateChartsForTheme();
        });

        function initializeCharts() {
            chartInstances.revenueByTime = initRevenueByTimeChart();
            chartInstances.revenueByRoomType = initRevenueByRoomTypeChart();
            chartInstances.revenueBySource = initRevenueBySourceChart();
            chartInstances.bookingStatus = initBookingStatusChart();
            chartInstances.bookingBySource = initBookingBySourceChart();
            chartInstances.bookingByCustomerType = initBookingByCustomerTypeChart();
            chartInstances.dailyOccupancy = initDailyOccupancyChart();
            chartInstances.pricePreference = initPricePreferenceChart();
            chartInstances.festivalRevenue = initFestivalRevenueChart();
            chartInstances.cancellationBySource = initCancellationBySourceChart();
            chartInstances.customerSource = initCustomerSourceChart();
        }

        function initRevenueByTimeChart() {
            const ctx = document.getElementById('revenueByTimeChart').getContext('2d');
            return new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [
                        {
                            label: 'Doanh thu ngày',
                            data: analyticsData.revenue.by_time.daily.map(item => ({ x: item.date, y: item.revenue })),
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
                        },
                        {
                            label: 'Doanh thu tuần',
                            data: analyticsData.revenue.by_time.weekly.map(item => ({ x: item.week, y: item.revenue })),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 12 } } },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${new Intl.NumberFormat('vi-VN').format(context.parsed.y)}₫`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: { unit: 'day', displayFormats: { day: 'dd/MM' } },
                            grid: { display: false },
                            ticks: { maxTicksLimit: 7 },
                            title: { display: true, text: 'Ngày' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { notation: 'compact', compactDisplay: 'short' }).format(value) + '₫';
                                }
                            },
                            title: { display: true, text: 'Doanh thu (₫)' }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' }
                }
            });
        }

        function initRevenueByRoomTypeChart() {
            const ctx = document.getElementById('revenueByRoomTypeChart').getContext('2d');
            const labels = analyticsData.revenue.by_room_type.map(item => item.name);
            const revenues = analyticsData.revenue.by_room_type.map(item => item.revenue);
            const colors = [
                'rgba(239, 68, 68, 0.8)', 'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(236, 72, 153, 0.8)'
            ];
            return new Chart(ctx, {
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
                        legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 12 } } },
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

        function initRevenueBySourceChart() {
            const ctx = document.getElementById('revenueBySourceChart').getContext('2d');
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: analyticsData.revenue.by_source.map(item => item.source),
                    datasets: [{
                        label: 'Doanh thu theo nguồn',
                        data: analyticsData.revenue.by_source.map(item => item.revenue),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${new Intl.NumberFormat('vi-VN').format(context.parsed.y)}₫`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, title: { display: true, text: 'Nguồn' } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { notation: 'compact', compactDisplay: 'short' }).format(value) + '₫';
                                }
                            },
                            title: { display: true, text: 'Doanh thu (₫)' }
                        }
                    }
                }
            });
        }

        function initBookingStatusChart() {
            const ctx = document.getElementById('bookingStatusChart').getContext('2d');
            const labels = analyticsData.booking.status.map(item => item.status);
            const colors = [
                'rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)',
                'rgba(59, 130, 246, 0.8)', 'rgba(245, 158, 11, 0.8)'
            ];
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: analyticsData.booking.status.map(item => item.count),
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
                        legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 12 } } },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} lượt (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        function initBookingBySourceChart() {
            const ctx = document.getElementById('bookingBySourceChart').getContext('2d');
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: analyticsData.booking.by_source.map(item => item.source),
                    datasets: [{
                        label: 'Booking theo nguồn',
                        data: analyticsData.booking.by_source.map(item => item.count),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed.y} lượt`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, title: { display: true, text: 'Nguồn' } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: { stepSize: 1 },
                            title: { display: true, text: 'Số lượt booking' }
                        }
                    }
                }
            });
        }

        function initBookingByCustomerTypeChart() {
            const ctx = document.getElementById('bookingByCustomerTypeChart').getContext('2d');
            const labels = analyticsData.booking.by_customer_type.map(item => item.customer_type);
            const colors = ['rgba(59, 130, 246, 0.8)', 'rgba(245, 158, 11, 0.8)', 'rgba(139, 92, 246, 0.8)'];
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: analyticsData.booking.by_customer_type.map(item => item.count),
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
                        legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 12 } } },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} lượt (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        function initDailyOccupancyChart() {
            const ctx = document.getElementById('dailyOccupancyChart').getContext('2d');
            return new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Công suất phòng',
                        data: analyticsData.occupancy.daily.map(item => ({ x: item.date, y: item.occupied_rooms })),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
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
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y} phòng`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: { unit: 'day', displayFormats: { day: 'dd/MM' } },
                            grid: { display: false },
                            ticks: { maxTicksLimit: 7 },
                            title: { display: true, text: 'Ngày' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: { stepSize: 1 },
                            title: { display: true, text: 'Số phòng sử dụng' }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' }
                }
            });
        }

        function initPricePreferenceChart() {
            const ctx = document.getElementById('pricePreferenceChart').getContext('2d');
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: analyticsData.price_preference.map(item => item.price_range),
                    datasets: [{
                        label: 'Mức giá ưa chuộng',
                        data: analyticsData.price_preference.map(item => item.count),
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        borderColor: 'rgb(245, 158, 11)',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(245, 158, 11)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed.y} lượt`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, title: { display: true, text: 'Mức giá' } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: { stepSize: 1 },
                            title: { display: true, text: 'Số lượt booking' }
                        }
                    }
                }
            });
        }

        function initFestivalRevenueChart() {
            const ctx = document.getElementById('festivalRevenueChart').getContext('2d');
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: analyticsData.festival_revenue.map(item => item.period),
                    datasets: [{
                        label: 'Doanh thu lễ hội',
                        data: analyticsData.festival_revenue.map(item => item.revenue),
                        backgroundColor: 'rgba(139, 92, 246, 0.8)',
                        borderColor: 'rgb(139, 92, 246)',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(139, 92, 246)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${new Intl.NumberFormat('vi-VN').format(context.parsed.y)}₫`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, title: { display: true, text: 'Kỳ lễ' } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { notation: 'compact', compactDisplay: 'short' }).format(value) + '₫';
                                }
                            },
                            title: { display: true, text: 'Doanh thu (₫)' }
                        }
                    }
                }
            });
        }

        function initCancellationBySourceChart() {
            const ctx = document.getElementById('cancellationBySourceChart').getContext('2d');
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: analyticsData.cancellation_by_source.map(item => item.source),
                    datasets: [{
                        label: 'Hủy theo nguồn',
                        data: analyticsData.cancellation_by_source.map(item => item.count),
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed.y} lượt`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, title: { display: true, text: 'Nguồn' } },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: { stepSize: 1 },
                            title: { display: true, text: 'Số lượt hủy' }
                        }
                    }
                }
            });
        }

        function initCustomerSourceChart() {
            const ctx = document.getElementById('customerSourceChart').getContext('2d');
            const labels = analyticsData.customer_source.map(item => item.customer_source);
            const colors = [
                'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)', 'rgba(239, 68, 68, 0.8)'
            ];
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: analyticsData.customer_source.map(item => item.count),
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
                        legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 12 } } },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} lượt (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        function setupRealTimeUpdates() {
            setInterval(updateRealTimeStats, 30000);
        }

        function updateRealTimeStats() {
            fetch('{{ route("admin.analytics.realtime-stats") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Object.assign(analyticsData, data.data);
                        Object.values(chartInstances).forEach(chart => chart.update());
                        showNotification('Dữ liệu đã được cập nhật', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error updating real-time stats:', error);
                    showNotification('Có lỗi xảy ra khi cập nhật dữ liệu', 'error');
                });
        }

        function setupPeriodFilter() {
            const periodFilter = document.getElementById('period-filter');
            periodFilter.addEventListener('change', function() {
                updateChartsData(this.value);
            });
        }

        function updateChartsData(period) {
            showLoading();
            fetch(`{{ route('admin.analytics.chart-data') }}?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Object.assign(analyticsData, data.data);
                        initializeCharts();
                        showNotification('Dữ liệu biểu đồ đã được cập nhật', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error updating charts:', error);
                    showNotification('Có lỗi xảy ra khi cập nhật dữ liệu', 'error');
                })
                .finally(() => hideLoading());
        }

        function refreshAnalytics() {
            showLoading();
            location.reload();
        }

        function showLoading() {
            let loadingOverlay = document.getElementById('loading-overlay');
            if (!loadingOverlay) {
                loadingOverlay = document.createElement('div');
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
        }

        function hideLoading() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
        }

        function showNotification(message, type = 'info') {
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

        function updateChartsForTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            Chart.defaults.color = isDark ? '#9CA3AF' : '#6B7280';
            Object.values(chartInstances).forEach(chart => {
                chart.options.plugins.legend.labels.color = isDark ? '#9CA3AF' : '#6B7280';
                chart.update();
            });
        }

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

        document.addEventListener('keydown', function(e) {
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                e.preventDefault();
                refreshAnalytics();
            }
        });

        setInterval(function() {
            updateRealTimeStats();
        }, 300000);

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                console.log('Analytics paused - tab not active');
            } else {
                console.log('Analytics resumed - tab active');
                updateRealTimeStats();
            }
        });
    </script>
</x-app-layout>