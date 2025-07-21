<div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thống kê thời gian thực</h2>
    </header>
    <div class="px-5 py-3">
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="rooms-available">
                    {{ $dataFeed['rooms_available'] ?? 0 }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Phòng trống</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="rooms-occupied">
                    {{ $dataFeed['rooms_occupied'] ?? 0 }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Phòng đã thuê</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="today-revenue">
                    {{ number_format($dataFeed['today_revenue'] ?? 0) }}₫
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Doanh thu hôm nay</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="today-bookings">
                    {{ $dataFeed['today_bookings'] ?? 0 }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Đặt phòng hôm nay</div>
            </div>
        </div>
    </div>
</div>
