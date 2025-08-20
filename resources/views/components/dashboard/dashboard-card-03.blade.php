@props(['dataFeed' => []])

<div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <div class="px-5 pt-5">
        <header class="flex justify-between items-start mb-2">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Tỷ lệ lấp đầy</h2>
        </header>
        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-1">Hiện tại</div>
        <div class="flex items-start">
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mr-2" id="occupancy-rate-display">
                {{ $dataFeed['occupancy_rate'] ?? 0 }}%
            </div>
        </div>
    </div>
    <!-- Progress bar -->
    <div class="px-5 pb-5">
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full progress-bar" style="width: {{ $dataFeed['occupancy_rate'] ?? 0 }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
            <span>{{ $dataFeed['rooms_occupied'] ?? 0 }} phòng đã thuê</span>
            <span>{{ $dataFeed['rooms_available'] ?? 0 }} phòng trống</span>
        </div>
    </div>
</div>
