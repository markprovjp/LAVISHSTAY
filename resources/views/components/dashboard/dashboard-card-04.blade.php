@props(['dataFeed' => []])

<div class="flex flex-col col-span-full sm:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Doanh thu theo loại phòng</h2>
    </header>
    <div class="px-5 py-3">
        @if(isset($dataFeed['room_type_revenue']) && $dataFeed['room_type_revenue']->count() > 0)
            <div class="flex flex-wrap justify-between items-center">
                <div class="w-full lg:w-1/2">
                    <canvas id="roomTypeRevenueChart" width="200" height="200"></canvas>
                </div>
                <div class="w-full lg:w-1/2 mt-4 lg:mt-0">
                    <div class="space-y-3">
                        @foreach($dataFeed['room_type_revenue'] as $index => $roomType)
                            @php
                                $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500'];
                                $colorClass = $colors[$index % count($colors)];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 {{ $colorClass }} rounded-full mr-2"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $roomType->name }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ number_format($roomType->revenue) }}₫
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $roomType->bookings }} đặt phòng
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Chưa có dữ liệu doanh thu theo loại phòng</p>
            </div>
        @endif
    </div>
</div>
