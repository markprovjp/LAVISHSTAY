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
                    <option value="365">1 năm qua</option>
                </select>

                <!-- Export Excel Button -->
                <button onclick="exportExcel()" class="btn bg-green-600 text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                    <svg class="fill-current shrink-0 w-4 h-4 mr-2" viewBox="0 0 16 16">
                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                    </svg>
                    <span class="max-xs:sr-only">Export Excel</span>
                </button>

                <!-- Refresh Button -->
                <button onclick="refreshDashboard()" class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8c1.8 0 3.4-.6 4.7-1.6L11 12.7c-.9.7-2 1.1-3 1.1-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5h-2l3 3 3-3h-2c0-4.4-3.6-8-8-8z"/>
                    </svg>
                    <span class="max-xs:sr-only cursor-pointer">Làm mới</span>
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
                        @forelse($roomTypeStats['occupancy_by_type'] as $roomType)
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
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>Chưa có dữ liệu loại phòng</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. REVIEWS & TIN TỨC GẦN ĐÂY -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Reviews gần đây -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reviews gần đây</h3>
                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Xem tất cả
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentReviews as $review)
                    <div class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($review->rating ?? 0))
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $review->rating ?? 0 }}/5</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">{{ Str::limit($review->comment ?? 'Không có bình luận', 100) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $review->guest_name ?? 'Khách hàng' }} • {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                        </p>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        <p>Chưa có review nào</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Tin tức hot -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tin tức nổi bật</h3>
                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Xem tất cả
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($hotNews as $news)
                    <div class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ Str::limit($news->title, 60) }}
                        </h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                            {{ Str::limit($news->summary ?? '', 80) }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $news->views ?? 0 }} lượt xem</span>
                            <span>{{ \Carbon\Carbon::parse($news->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                        </svg>
                        <p>Chưa có tin tức nào</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Comments gần đây -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Bình luận gần đây</h3>
                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Xem tất cả
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentComments as $comment)
                    <div class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                            {{ Str::limit($comment->content ?? '', 80) }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $comment->name ?? 'Khách' }}</span>
                            <span>{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            Trên: {{ Str::limit($comment->title ?? 'Bài viết', 40) }}
                        </p>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p>Chưa có bình luận nào</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 6. HOẠT ĐỘNG LỄ TÂN & CẢNH BÁO -->
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

                    @if($alerts['rooms_need_cleaning'] == 0 && $alerts['overdue_payments'] == 0 && $alerts['maintenance_rooms'] == 0 && $alerts['arriving_tomorrow'] == 0)
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

        <!-- 7. BẢNG DỮ LIỆU CHI TIẾT -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <!-- Đặt phòng mới nhất -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Đặt phòng mới nhất</h3>
                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Xem tất cả
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($detailTables['recent_bookings'] as $booking)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $booking->booking_code ?? 'N/A' }}
                                </span>
                                <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                    @if($booking->status == 'Confirmed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($booking->status == 'Pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @elseif($booking->status == 'Cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                    {{ $booking->status ?? 'Unknown' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $booking->guest_name ?? 'N/A' }} • {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($booking->total_price_vnd ?? 0) }}₫
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
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
                                {{ $booking->guest_name ?? 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $booking->booking_code ?? 'N/A' }} • {{ $booking->guest_phone ?? 'N/A' }}
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
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p>Không có khách check-in hôm nay</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 8. THỐNG KÊ KHÁCH HÀNG & TÀI CHÍNH -->
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
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Tổng giá trị</p>
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($financialMetrics['total_bookings_value']) }}₫
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 9. TOP KHÁCH HÀNG -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Top khách hàng VIP</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
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
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($customerStats['top_customers'] as $customer)
                        <tr>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
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
        .quick-filter-btn {
            @apply px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700;
        }
        .quick-filter-btn.active {
            @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:border-blue-500;
        }
        .btn {
            @apply inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global variables
        let revenueChart, bookingsChart, roomTypeRevenueChart;
        
        // Chart data from backend
        const chartData = @json($chartData);
        const roomTypeStats = @json($roomTypeStats);

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            startRealtimeUpdates();
            initializeFilterHandlers();
        });

        // Initialize all charts
        function initializeCharts() {
            initRevenueChart();
            initBookingsChart();
            initRoomTypeRevenueChart();
        }

        // Revenue Chart
        function initRevenueChart() {
            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;
            
            revenueChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: chartData.revenue.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: chartData.revenue.map(item => item.revenue),
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
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Bookings Chart
        function initBookingsChart() {
            const ctx = document.getElementById('bookingsChart');
            if (!ctx) return;
            
            bookingsChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: chartData.bookings.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Số lượt đặt',
                        data: chartData.bookings.map(item => item.bookings),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
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

        // Room Type Revenue Chart
        function initRoomTypeRevenueChart() {
            const ctx = document.getElementById('roomTypeRevenueChart');
            if (!ctx) return;
            
            const colors = [
                'rgba(239, 68, 68, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ];

            roomTypeRevenueChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: roomTypeStats.booking_by_type.map(item => item.name),
                    datasets: [{
                        data: roomTypeStats.booking_by_type.map(item => item.revenue),
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

        // Real-time updates
        function startRealtimeUpdates() {
            // Update every 30 seconds
            setInterval(updateRealtimeStats, 30000);
        }

        function updateRealtimeStats() {
            fetch('/admin/dashboard/realtime-stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update room stats
                        const roomsAvailable = document.getElementById('rooms-available');
                        const roomsOccupied = document.getElementById('rooms-occupied');
                        const todayRevenue = document.getElementById('today-revenue');
                        const todayBookings = document.getElementById('today-bookings');

                        if (roomsAvailable) roomsAvailable.textContent = data.data.rooms_available;
                        if (roomsOccupied) roomsOccupied.textContent = data.data.rooms_occupied;
                        if (todayRevenue) todayRevenue.textContent = new Intl.NumberFormat('vi-VN').format(data.data.today_revenue) + '₫';
                        if (todayBookings) todayBookings.textContent = data.data.today_bookings;
                    }
                })
                .catch(error => {
                    console.error('Error updating realtime stats:', error);
                });
        }

        // Filter handlers
        function initializeFilterHandlers() {
            const filterType = document.getElementById('filter_type');
            if (filterType) {
                filterType.addEventListener('change', toggleFilterOptions);
            }
        }

        function toggleAdvancedFilter() {
            const panel = document.getElementById('advanced-filter');
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }

        function toggleFilterOptions() {
            const filterType = document.getElementById('filter_type').value;
            const presetOptions = document.getElementById('preset-options');
            const customRangeOptions = document.getElementById('custom-range-options');
            const specificDateOptions = document.getElementById('specific-date-options');

            // Hide all options first
            presetOptions.classList.add('hidden');
            customRangeOptions.classList.add('hidden');
            specificDateOptions.classList.add('hidden');

            // Show relevant option
            switch(filterType) {
                case 'preset':
                    presetOptions.classList.remove('hidden');
                    break;
                case 'custom_range':
                    customRangeOptions.classList.remove('hidden');
                    break;
                case 'specific_date':
                    specificDateOptions.classList.remove('hidden');
                    break;
            }
        }

        function applyQuickFilter(period) {
            // Update active button
            document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            const targetBtn = document.querySelector(`[data-filter="${period}"]`);
            if (targetBtn) {
                targetBtn.classList.add('active');
            }

            // Update charts
            updateChartData(period);
        }

        function updateChartData(period) {
            fetch(`/admin/dashboard/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && revenueChart && bookingsChart) {
                        // Update revenue chart
                        revenueChart.data.labels = data.data.revenue.map(item => {
                            const date = new Date(item.date);
                            return date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' });
                        });
                        revenueChart.data.datasets[0].data = data.data.revenue.map(item => item.revenue);
                        revenueChart.update();

                        // Update bookings chart
                        bookingsChart.data.labels = data.data.bookings.map(item => {
                            const date = new Date(item.date);
                            return date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' });
                        });
                        bookingsChart.data.datasets[0].data = data.data.bookings.map(item => item.bookings);
                        bookingsChart.update();
                    }
                })
                .catch(error => {
                    console.error('Error updating chart data:', error);
                });
        }

        function resetFilter() {
            const filterForm = document.getElementById('filter-form');
            const filterType = document.getElementById('filter_type');
            if (filterForm) filterForm.reset();
            if (filterType) {
                filterType.value = 'preset';
                toggleFilterOptions();
            }
        }

        // Refresh dashboard
        function refreshDashboard() {
            // Show loading state
            const refreshBtn = document.querySelector('button[onclick="refreshDashboard()"]');
            if (refreshBtn) {
                const originalContent = refreshBtn.innerHTML;
                refreshBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                refreshBtn.disabled = true;

                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }

        // Export Excel
        function exportExcel() {
            const exportBtn = document.querySelector('button[onclick="exportExcel()"]');
            if (exportBtn) {
                const originalContent = exportBtn.innerHTML;
                
                // Show loading state
                exportBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Đang xuất...';
                exportBtn.disabled = true;

                // Create download link
                const link = document.createElement('a');
                link.href = `/admin/dashboard/export-excel`;
                link.download = `dashboard-report-${new Date().toISOString().split('T')[0]}.xlsx`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Reset button after delay
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

            [revenueChart, bookingsChart, roomTypeRevenueChart].forEach(chart => {
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