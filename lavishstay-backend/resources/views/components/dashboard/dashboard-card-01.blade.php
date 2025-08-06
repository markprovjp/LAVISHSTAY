@props(['dataFeed' => []])

<div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <div class="px-5 pt-5">
        <header class="flex justify-between items-start mb-2">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Doanh thu theo ngày</h2>
            <!-- Menu button -->
            <div class="relative inline-flex">
                <button class="rounded-full">
                    <span class="sr-only">Menu</span>
                    <svg class="w-8 h-8 fill-current text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400" viewBox="0 0 32 32">
                        <circle cx="16" cy="11" r="2" />
                        <circle cx="16" cy="16" r="2" />
                        <circle cx="16" cy="21" r="2" />
                    </svg>
                </button>
            </div>
        </header>
        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-1">Tổng doanh thu</div>
        <div class="flex items-start">
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mr-2" id="total-revenue-display">
                {{ number_format($dataFeed['total_revenue'] ?? 0) }}₫
            </div>
        </div>
    </div>
    <!-- Chart built with Chart.js 3 -->
    <div class="grow max-sm:max-h-[128px] xl:max-h-[128px]">
        <canvas id="revenueChart" width="389" height="128"></canvas>
    </div>
</div>
