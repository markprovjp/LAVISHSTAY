<!-- Room Calendar Modal -->
<div x-data="roomCalendar()" x-init="init()" x-show="isOpen" x-cloak style="display: none;"
    class="fixed inset-0 bg-black/50 z-50 overflow-y-auto">

    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-6xl w-full max-h-[95vh] overflow-hidden"
            @click.away="closeModal()">

            <!-- Header -->
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-blue-600">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-semibold text-white">
                            Lịch đặt phòng - <span x-text="roomInfo.name || 'Loading...'"></span>
                        </h3>
                        <p class="text-blue-100" x-text="roomInfo.type || ''"></p>
                    </div>
                    <button @click="closeModal()" class="text-blue-100 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="!roomInfo.name && isOpen" class="p-12 text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-500 mx-auto mb-6"></div>
                <p class="text-lg text-gray-500 dark:text-gray-400">Đang tải dữ liệu lịch...</p>
            </div>

            <!-- Content -->
            <div x-show="roomInfo.name" class="overflow-y-auto max-h-[calc(95vh-200px)]">

                <!-- Calendar Navigation -->
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <button @click="previousMonth()"
                        class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Tháng trước
                    </button>

                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Lịch đặt phòng
                    </h4>

                    <button @click="nextMonth()"
                        class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        Tháng sau
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Calendar Months -->
                <div class="p-6 flex">
                    <div class="flex w-full justify-between gap-4">
                        <template x-for="month in visibleMonths" :key="month.key">
                            <div
                                class="bg-white p-6 flex-1 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">

                                <!-- Month Header -->
                                <div
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <h5 class="text-lg font-bold text-gray-900 dark:text-gray-100 text-center"
                                        x-text="month.name"></h5>
                                </div>

                                <!-- Days of week header -->
                                <div class="flex justify-between mt-3 w-full text-center gap-1 mb-3">
                                    <template
                                        x-for="day in ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7','CN']">
                                        <div class="h-10 flex-1 items-center justify-center">
                                            <span class="text-lg font-semibold text-gray-600 dark:text-gray-400"
                                                x-text="day"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Calendar days grid - 6 rows x 7 columns = 42 cells -->
                                <div class="w-full">
                                    <!-- Days grid with explicit week rows -->
                                    <template x-for="week in Math.ceil(month.days.length / 7)" :key="week">
                                        <div class="flex w-full mb-3 gap-4">
                                            <template x-for="i in 7" :key="i">
                                                <template x-if="month.days[(week-1)*7 + i - 1]">
                                                    <div class="w-1/7 p-1" style="width: 14.28%;">
                                                        <button @click="showDayDetails(month.days[(week-1)*7 + i - 1])"
                                                            :title="month.days[(week - 1) * 7 + i - 1].tooltip"
                                                            :disabled="!month.days[(week - 1) * 7 + i - 1].isCurrentMonth || !month
                                                                .days[(week - 1) * 7 + i - 1].data"
                                                            :class="{
                                                                // Base styles
                                                                'w-full h-16 text-sm rounded-lg border transition-all duration-200 flex flex-col items-center justify-center relative overflow-hidden': true,
                                                            
                                                                // Month styles
                                                                'text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-900 border-gray-100 dark:border-gray-800':
                                                                    !month.days[(week - 1) * 7 + i - 1].isCurrentMonth,
                                                                'text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700': month
                                                                    .days[(week - 1) * 7 + i - 1].isCurrentMonth,
                                                            
                                                                // Today highlight
                                                                'ring-2 ring-blue-500 ring-offset-1 bg-blue-50 dark:bg-blue-900/30 border-blue-300 dark:border-blue-600 text-blue-700 dark:text-blue-300 font-bold': month
                                                                    .days[(week - 1) * 7 + i - 1].isToday,
                                                            
                                                                // Status colors (only for current month)
                                                                'bg-green-50 hover:bg-green-100 border-green-200 text-green-800 dark:bg-green-900/20 dark:hover:bg-green-900/30 dark:border-green-700 dark:text-green-300': month
                                                                    .days[(week - 1) * 7 + i - 1]
                                                                    .status === 'available' && month.days[(week - 1) *
                                                                        7 + i - 1].isCurrentMonth && !month.days[(week -
                                                                        1) * 7 + i - 1].isToday,
                                                                'bg-yellow-50 hover:bg-yellow-100 border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 dark:border-yellow-700 dark:text-yellow-300': month
                                                                    .days[(week - 1) * 7 + i - 1]
                                                                    .status === 'partial' && month.days[(week - 1) *
                                                                        7 + i - 1].isCurrentMonth && !month.days[(week -
                                                                        1) * 7 + i - 1].isToday,
                                                                'bg-red-50 hover:bg-red-100 border-red-200 text-red-800 dark:bg-red-900/20 dark:hover:bg-red-900/30 dark:border-red-700 dark:text-red-300': month
                                                                    .days[(week - 1) * 7 + i - 1]
                                                                    .status === 'full' && month.days[(week - 1) *
                                                                        7 + i - 1].isCurrentMonth && !month.days[(week -
                                                                        1) * 7 + i - 1].isToday,
                                                                'bg-gray-50 hover:bg-gray-100 border-gray-200 text-gray-500 dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-600 dark:text-gray-400': month
                                                                    .days[(week - 1) * 7 + i - 1]
                                                                    .status === 'unavailable' && month.days[(week - 1) *
                                                                        7 + i - 1].isCurrentMonth && !month.days[(week -
                                                                        1) * 7 + i - 1].isToday,
                                                            
                                                                // Cursor styles
                                                                'cursor-pointer hover:shadow-lg hover:scale-105 transform': month
                                                                    .days[(week - 1) * 7 + i - 1].isCurrentMonth &&
                                                                    month.days[(week - 1) * 7 + i - 1].data,
                                                                'cursor-not-allowed': !month.days[(week - 1) * 7 + i -
                                                                    1].isCurrentMonth || !month.days[(week - 1) *
                                                                    7 + i - 1].data,
                                                            
                                                                // Disabled styles
                                                                'opacity-40': !month.days[(week - 1) * 7 + i - 1]
                                                                    .isCurrentMonth || !month.days[(week - 1) * 7 + i -
                                                                        1].data
                                                            }"
                                                            class="group">

                                                            <!-- Day number -->
                                                            <span class="text-sm font-semibold leading-none mb-1"
                                                                x-text="month.days[(week-1)*7 + i - 1].dayNumber"></span>

                                                            <!-- Available rooms indicator (only for current month with data) -->
                                                            <template
                                                                x-if="month.days[(week-1)*7 + i - 1].isCurrentMonth && month.days[(week-1)*7 + i - 1].data">
                                                                <div
                                                                    class="text-xs font-medium opacity-75 leading-none">
                                                                    <span
                                                                        x-text="month.days[(week-1)*7 + i - 1].data.available_rooms"></span>/<span
                                                                        x-text="month.days[(week-1)*7 + i - 1].data.total_rooms"></span>
                                                                </div>
                                                            </template>

                                                            <!-- Occupancy rate bar (bottom of cell) -->
                                                            <template
                                                                x-if="month.days[(week-1)*7 + i - 1].isCurrentMonth && month.days[(week-1)*7 + i - 1].data && month.days[(week-1)*7 + i - 1].data.occupancy_rate > 0">
                                                                <div class="absolute bottom-0 left-0 h-1 bg-current opacity-50 transition-all duration-200 group-hover:opacity-80"
                                                                    :style="`width: ${Math.min(month.days[(week-1)*7 + i - 1].data.occupancy_rate, 100)}%`">
                                                                </div>
                                                            </template>

                                                            <!-- Status dot indicator (top right) -->
                                                            <template
                                                                x-if="month.days[(week-1)*7 + i - 1].isCurrentMonth && month.days[(week-1)*7 + i - 1].data">
                                                                <div class="absolute top-1 right-1 w-2 h-2 rounded-full"
                                                                    :class="{
                                                                        'bg-green-500': month.days[(week - 1) * 7 + i -
                                                                            1].status === 'available',
                                                                        'bg-yellow-500': month.days[(week - 1) * 7 + i -
                                                                            1].status === 'partial',
                                                                        'bg-red-500': month.days[(week - 1) * 7 + i - 1]
                                                                            .status === 'full',
                                                                        'bg-gray-400': month.days[(week - 1) * 7 + i -
                                                                            1].status === 'unavailable'
                                                                    }">
                                                                </div>
                                                            </template>
                                                        </button>
                                                    </div>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                    </div>
                    </template>
                </div>
            </div>

                        <!-- Legend -->
            <div class="px-6 pb-6">
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Chú thích trạng thái phòng:</h5>
                    
                    <!-- Status Legend -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-50 hover:bg-green-100 border-2 border-green-200 rounded-lg flex items-center justify-center relative">
                                <span class="text-xs font-semibold text-green-800">15</span>
                                <div class="absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full"></div>
                                <div class="absolute bottom-0 left-0 h-1 bg-green-500 opacity-50" style="width: 60%"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Còn trống</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Nhiều phòng available</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-yellow-50 hover:bg-yellow-100 border-2 border-yellow-200 rounded-lg flex items-center justify-center relative">
                                <span class="text-xs font-semibold text-yellow-800">20</span>
                                <div class="absolute top-1 right-1 w-2 h-2 bg-yellow-500 rounded-full"></div>
                                <div class="absolute bottom-0 left-0 h-1 bg-yellow-500 opacity-50" style="width: 80%"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Gần đầy</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ít phòng còn lại</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-50 hover:bg-red-100 border-2 border-red-200 rounded-lg flex items-center justify-center relative">
                                <span class="text-xs font-semibold text-red-800">25</span>
                                <div class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></div>
                                <div class="absolute bottom-0 left-0 h-1 bg-red-500 opacity-50" style="width: 100%"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Đã đầy</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Không còn phòng</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-50 border-2 border-blue-500 rounded-lg flex items-center justify-center relative ring-2 ring-blue-500 ring-offset-1">
                                <span class="text-xs font-semibold text-blue-800">12</span>
                                <div class="absolute top-1 right-1 w-2 h-2 bg-blue-500 rounded-full"></div>
                                <div class="absolute bottom-0 left-0 h-1 bg-blue-500 opacity-50" style="width: 70%"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Hôm nay</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ngày hiện tại</p>
                            </div>
                        </div>
                    </div>

                    <!-- Explanation -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h6 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Cách đọc thông tin:</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-start space-x-2">
                                <div class="w-4 h-4 bg-gray-200 rounded flex items-center justify-center mt-0.5">
                                    <span class="text-xs font-bold">15</span>
                                </div>
                                <div>
                                    <span class="font-medium">Số trong ô:</span> Ngày trong tháng
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-2">
                                <div class="w-4 h-4 bg-gray-200 rounded relative mt-0.5">
                                    <div class="absolute top-0 right-0 w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                </div>
                                <div>
                                    <span class="font-medium">Chấm góc phải:</span> Trạng thái phòng
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-2">
                                <div class="w-4 h-4 bg-gray-200 rounded relative mt-0.5">
                                    <div class="absolute bottom-0 left-0 h-0.5 bg-blue-500 opacity-50" style="width: 60%"></div>
                                </div>
                                <div>
                                    <span class="font-medium">Thanh dưới:</span> Tỷ lệ lấp đầy (%)
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-2">
                                <div class="w-4 h-4 bg-gray-200 rounded border-2 border-blue-500 mt-0.5"></div>
                                <div>
                                    <span class="font-medium">Viền xanh:</span> Ngày hôm nay
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <strong>💡 Mẹo:</strong> Click vào bất kỳ ngày nào trong tháng hiện tại để xem chi tiết booking và thông tin phòng trống.
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Summary Stats -->
            <div class="px-6 pb-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/30 p-6 rounded-xl border border-green-200 dark:border-green-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400"
                                    x-text="summary.available_days || 0"></div>
                                <div class="text-sm font-medium text-green-700 dark:text-green-300">Ngày còn trống
                                </div>
                            </div>
                            <div
                                class="w-12 h-12 bg-green-200 dark:bg-green-800 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-900/30 p-6 rounded-xl border border-yellow-200 dark:border-yellow-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400"
                                    x-text="summary.partial_days || 0"></div>
                                <div class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Ngày gần đầy
                                </div>
                            </div>
                            <div
                                class="w-12 h-12 bg-yellow-200 dark:bg-yellow-800 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-900/30 p-6 rounded-xl border border-red-200 dark:border-red-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-red-600 dark:text-red-400"
                                    x-text="summary.full_days || 0"></div>
                                <div class="text-sm font-medium text-red-700 dark:text-red-300">Ngày đã đầy</div>
                            </div>
                            <div
                                class="w-12 h-12 bg-red-200 dark:bg-red-800 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/30 p-6 rounded-xl border border-purple-200 dark:border-purple-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400"
                                    x-text="(summary.average_occupancy || 0) + '%'"></div>
                                <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Tỷ lệ TB</div>
                            </div>
                            <div
                                class="w-12 h-12 bg-purple-200 dark:bg-purple-800 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div x-show="roomInfo.name"
            class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium">Hôm nay:</span>
                    <span
                        x-text="new Date().toLocaleDateString('vi-VN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                </div>
                <div class="flex space-x-3">
                    <button @click="previousMonth()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-sm">
                        ← Tháng trước
                    </button>
                    <button @click="nextMonth()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-sm">
                        Tháng sau →
                    </button>
                    <button @click="closeModal()"
                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors text-sm font-medium">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function roomCalendar() {
        return {
            isOpen: false,
            roomInfo: {},
            calendarData: [],
            summary: {},
            currentDate: new Date(),
            visibleMonths: [],

            init() {
                console.log('Room calendar initialized');
                this.updateVisibleMonths();
            },

            async openModal(roomId) {
                console.log('Opening modal for room:', roomId);
                this.isOpen = true;
                this.roomInfo = {};
                this.calendarData = [];
                this.summary = {};
                this.visibleMonths = [];

                // Force DOM update
                await this.$nextTick();

                await this.loadCalendarData(roomId);
            },

            closeModal() {
                console.log('Closing modal');
                this.isOpen = false;
                this.visibleMonths = [];
            },

            async loadCalendarData(roomId) {
                const url = `/admin/rooms/${roomId}/calendar-data`;
                console.log('Loading calendar data from:', url);

                try {
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Response is not JSON:', text.substring(0, 200));
                        throw new Error('Server trả về HTML thay vì JSON');
                    }

                    const data = await response.json();
                    console.log('Calendar data loaded:', data);

                    if (data.success) {
                        this.roomInfo = data.room;
                        this.calendarData = data.calendar_data || [];
                        this.summary = data.summary || {};

                        // Force update after data loaded
                        await this.$nextTick();
                        this.updateVisibleMonths();

                        console.log('Data set:', {
                            roomInfo: this.roomInfo,
                            calendarDataLength: this.calendarData.length,
                            visibleMonthsLength: this.visibleMonths.length
                        });
                    } else {
                        throw new Error(data.message || 'Có lỗi xảy ra khi tải dữ liệu');
                    }
                } catch (error) {
                    console.error('Error loading calendar data:', error);
                    alert('Có lỗi xảy ra: ' + error.message);
                    this.closeModal();
                }
            },

            updateVisibleMonths() {
                console.log('Updating visible months...');
                this.visibleMonths = [];

                if (!this.calendarData || this.calendarData.length === 0) {
                    console.log('No calendar data available');
                    return;
                }

                try {
                    for (let i = 0; i < 2; i++) {
                        const monthDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + i, 1);
                        const monthData = this.generateMonthData(monthDate);
                        if (monthData && monthData.days && monthData.days.length > 0) {
                            this.visibleMonths.push(monthData);
                        }
                    }
                    console.log('Visible months generated:', this.visibleMonths.length);
                } catch (error) {
                    console.error('Error generating months:', error);
                    this.visibleMonths = [];
                }
            },

            // Hàm helper để lấy số ngày trong tháng
            getDaysInMonth(year, month) {
                // month: 0-11 (JavaScript Date format)
                // Các tháng có 31 ngày: 0(Jan), 2(Mar), 4(May), 6(Jul), 7(Aug), 9(Oct), 11(Dec)
                const monthsWith31Days = [0, 2, 4, 6, 7, 9, 11];
                const monthsWith30Days = [3, 5, 8, 10]; // Apr, Jun, Sep, Nov
                
                if (monthsWith31Days.includes(month)) {
                    return 31;
                } else if (monthsWith30Days.includes(month)) {
                    return 30;
                } else if (month === 1) { // February
                    // Kiểm tra năm nhuận
                    return this.isLeapYear(year) ? 29 : 28;
                }
                
                // Fallback - sử dụng JavaScript Date để tính
                return new Date(year, month + 1, 0).getDate();
            },

            // Kiểm tra năm nhuận
            isLeapYear(year) {
                return (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
            },

            generateMonthData(monthDate) {
                try {
                    const year = monthDate.getFullYear();
                    const month = monthDate.getMonth();
                    const monthName = monthDate.toLocaleDateString('vi-VN', {
                        month: 'long',
                        year: 'numeric'
                    });

                    const firstDay = new Date(year, month, 1);
                    const daysInMonth = this.getDaysInMonth(year, month);
                    const startingDayOfWeek = (firstDay.getDay() + 6) % 7; // Monday = 0

                    const days = [];
                    const seenDates = new Set();

                    // Previous month days
                    const prevMonth = month === 0 ? 11 : month - 1;
                    const prevYear = month === 0 ? year - 1 : year;
                    const daysInPrevMonth = this.getDaysInMonth(prevYear, prevMonth);
                    
                    for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                        const dayDate = new Date(prevYear, prevMonth, daysInPrevMonth - i);
                        const dayObj = this.createDayObject(dayDate, false);
                        if (dayObj && !seenDates.has(dayObj.date)) {
                            days.push(dayObj);
                            seenDates.add(dayObj.date);
                        }
                    }

                    // Current month days
                    for (let day = 1; day <= daysInMonth; day++) {
                        const dayDate = new Date(year, month, day);
                        const dayObj = this.createDayObject(dayDate, true);
                        if (dayObj && !seenDates.has(dayObj.date)) {
                            days.push(dayObj);
                            seenDates.add(dayObj.date);
                        }
                    }

                    // Next month days to fill the grid (42 cells total - 6 weeks x 7 days)
                    const nextMonth = month === 11 ? 0 : month + 1;
                    const nextYear = month === 11 ? year + 1 : year;
                    let nextMonthDay = 1;
                    
                    while (days.length < 42) {
                        const dayDate = new Date(nextYear, nextMonth, nextMonthDay);
                        const dayObj = this.createDayObject(dayDate, false);
                        if (dayObj && !seenDates.has(dayObj.date)) {
                            days.push(dayObj);
                            seenDates.add(dayObj.date);
                        }
                        nextMonthDay++;
                    }

                    console.log(`Generated month ${monthName}: ${daysInMonth} days, total cells: ${days.length}`);

                    return {
                        key: `${year}-${month}`,
                        name: monthName,
                        days: days
                    };
                } catch (error) {
                    console.error('Error generating month data:', error);
                    return null;
                }
            },

            createDayObject(date, isCurrentMonth) {
                try {
                    const dateStr = date.toISOString().split('T')[0];
                    const today = new Date().toISOString().split('T')[0];

                    const dayData = this.calendarData.find(d => d.date === dateStr);

                    let status = 'unavailable';
                    let occupancyRate = 0;
                    let tooltip = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;

                    if (dayData) {
                        occupancyRate = dayData.occupancy_rate || 0;
                        status = dayData.status || 'unavailable';

                        tooltip += `\nTổng phòng: ${dayData.total_rooms}`;
                        tooltip += `\nCòn trống: ${dayData.available_rooms}`;
                        tooltip += `\nĐã đặt: ${dayData.occupied_rooms}`;
                        tooltip += `\nTỷ lệ: ${occupancyRate}%`;
                    }

                    return {
                        date: dateStr,
                        dayNumber: date.getDate(),
                        isCurrentMonth: isCurrentMonth,
                        isToday: dateStr === today,
                        status: status,
                        occupancyRate: occupancyRate,
                        tooltip: tooltip,
                        data: dayData || null
                    };
                } catch (error) {
                    console.error('Error creating day object:', error);
                    return null;
                }
            },

            previousMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                this.updateVisibleMonths();
            },

            nextMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                this.updateVisibleMonths();
            },

            showDayDetails(day) {
                if (!day || !day.data || !day.isCurrentMonth) return;

                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black/50 z-[70] flex items-center justify-center p-4';
                modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Chi tiết ngày ${day.date}
                        </h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tổng số phòng:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100">${day.data.total_rooms}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Phòng còn trống:</span>
                            <span class="font-medium text-green-600">${day.data.available_rooms}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Phòng đã đặt:</span>
                            <span class="font-medium text-red-600">${day.data.occupied_rooms}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Số booking:</span>
                            <span class="font-medium text-blue-600">${day.data.active_bookings}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tỷ lệ lấp đầy:</span>
                            <span class="font-medium text-purple-600">${day.data.occupancy_rate}%</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Trạng thái:</span>
                                <span class="px-2 py-1 rounded text-xs font-medium ${
                                    day.data.status === 'available' ? 'bg-green-100 text-green-800' :
                                    day.data.status === 'partial' ? 'bg-yellow-100 text-yellow-800' :
                                    day.data.status === 'full' ? 'bg-red-100 text-red-800' :
                                    'bg-gray-100 text-gray-800'
                                }">
                                    ${
                                        day.data.status === 'available' ? 'Còn trống' :
                                        day.data.status === 'partial' ? 'Gần đầy' :
                                        day.data.status === 'full' ? 'Đã đầy' :
                                        'Không khả dụng'
                                    }
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            Đóng
                        </button>
                    </div>
                </div>
            `;

                document.body.appendChild(modal);

                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.remove();
                    }
                });
            }
        }
    }

    // Global function
    window.showRoomCalendar = function(roomId) {
        console.log('showRoomCalendar called with roomId:', roomId);

        // Tìm modal element
        const modalElement = document.querySelector('[x-data*="roomCalendar"]');
        console.log('Modal element found:', modalElement);

        if (modalElement && modalElement._x_dataStack && modalElement._x_dataStack[0]) {
            console.log('Opening modal via Alpine.js');
            modalElement._x_dataStack[0].openModal(roomId);
        } else {
            console.error('Calendar modal not found or not initialized');

            // Fallback: Try to find by Alpine data
            const allElements = document.querySelectorAll('[x-data]');
            let found = false;

            allElements.forEach(el => {
                if (el._x_dataStack && el._x_dataStack[0] && el._x_dataStack[0].openModal) {
                    console.log('Found modal via fallback method');
                    el._x_dataStack[0].openModal(roomId);
                    found = true;
                }
            });

            if (!found) {
                alert('Lỗi: Modal chưa được khởi tạo. Vui lòng thử lại sau khi trang tải xong.');
            }
        }
    };

    // Ensure Alpine.js is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, Alpine should be available');

        // Wait a bit for Alpine to initialize
        setTimeout(() => {
            const modalElement = document.querySelector('[x-data*="roomCalendar"]');
            if (modalElement) {
                console.log('Modal element found after DOM load');
            } else {
                console.warn('Modal element not found after DOM load');
            }
        }, 1000);
    });
</script>
