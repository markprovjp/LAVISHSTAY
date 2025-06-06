
<!-- Room Calendar Modal -->
<div x-data="roomCalendar()" 
     x-init="init()" 
     x-show="isOpen" 
     x-cloak
     style="display: none;"
     class="fixed inset-0 bg-black/50 z-50 overflow-y-auto">
    
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden"
             @click.away="closeModal()">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Lịch đặt phòng - <span x-text="roomInfo.name || 'Loading...'"></span>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="roomInfo.type || ''"></p>
                    </div>
                    <button @click="closeModal()" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Loading State -->
            <div x-show="!roomInfo.name && isOpen" class="p-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
                <p class="text-gray-500 dark:text-gray-400">Đang tải dữ liệu...</p>
            </div>
            
            <!-- Content -->
            <div x-show="roomInfo.name" class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                
                <!-- Calendar Navigation -->
                <div class="flex justify-between items-center mb-6">
                    <button @click="previousMonth()" 
                            class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Lịch đặt phòng
                    </h4>
                    
                    <button @click="nextMonth()" 
                            class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Debug Info -->
                <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm">
                    <p><strong>Debug:</strong></p>
                    <p>Room ID: <span x-text="roomInfo.id"></span></p>
                    <p>Calendar Data Count: <span x-text="calendarData.length"></span></p>
                    <p>Visible Months: <span x-text="visibleMonths.length"></span></p>
                </div>
                
                <!-- Calendar Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <template x-for="month in visibleMonths" :key="month.key">
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                            <h5 class="text-center font-medium text-gray-900 dark:text-gray-100 mb-4" x-text="month.name"></h5>
                            
                            <!-- Days of week header -->
                            <div class="flex justify-between gap-1 mb-2">
                                <template x-for="day in ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']">
                                    <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2" x-text="day"></div>
                                </template>
                            </div>
                            
                            <!-- Calendar days -->
                            <div class="flex gap-1">
                                <template x-for="day in month.days" :key="day.date">
                                    <div class="relative" style="width: 14.28%;">
                                        <button 
                                            @click="showDayDetails(day)"
                                            :title="day.tooltip"
                                            :class="{
                                                'text-gray-400 dark:text-gray-600': !day.isCurrentMonth,
                                                'text-gray-900 dark:text-gray-100': day.isCurrentMonth,
                                                'bg-blue-500 text-white': day.isToday,
                                                'bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50': day.status === 'available' && day.isCurrentMonth,
                                                'bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50': day.status === 'partial' && day.isCurrentMonth,
                                                'bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50': day.status === 'full' && day.isCurrentMonth,
                                                'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': day.status === 'unavailable' && day.isCurrentMonth,
                                                'cursor-pointer': day.isCurrentMonth && day.data,
                                                'cursor-default': !day.isCurrentMonth || !day.data
                                            }"
                                            class="w-full  h-10 text-sm rounded transition-colors flex items-center justify-center relative"
                                            :disabled="!day.isCurrentMonth || !day.data">
                                            
                                            <span x-text="day.dayNumber"></span>
                                            
                                            <!-- Occupancy indicator -->
                                            <template x-if="day.isCurrentMonth && day.data && day.occupancyRate > 0">
                                                <div class="absolute bottom-0 left-0 h-1 bg-current opacity-50 rounded-b"
                                                     :style="`width: ${Math.min(day.occupancyRate, 100)}%`"></div>
                                            </template>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Summary Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400" x-text="summary.available_days || 0"></div>
                        <div class="text-sm text-green-700 dark:text-green-300">Ngày còn trống</div>
                    </div>
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" x-text="summary.partial_days || 0"></div>
                        <div class="text-sm text-yellow-700 dark:text-yellow-300">Ngày gần đầy</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400" x-text="summary.full_days || 0"></div>
                        <div class="text-sm text-red-700 dark:text-red-300">Ngày đã đầy</div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400" x-text="(summary.average_occupancy || 0) + '%'"></div>
                        <div class="text-sm text-purple-700 dark:text-purple-300">Tỷ lệ TB</div>
                    </div>
                </div>
                
            </div>
            
            <!-- Footer -->
            <div x-show="roomInfo.name" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Click vào ngày để xem chi tiết
                    </div>
                    <button @click="closeModal()" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Đóng
                    </button>
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
        
        generateMonthData(monthDate) {
            try {
                const year = monthDate.getFullYear();
                const month = monthDate.getMonth();
                const monthName = monthDate.toLocaleDateString('vi-VN', { month: 'long', year: 'numeric' });
                
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startingDayOfWeek = (firstDay.getDay() + 6) % 7; // Monday = 0
                
                const days = [];
                const seenDates = new Set();
                
                // Previous month days
                const prevMonth = new Date(year, month - 1, 0);
                for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                    const dayDate = new Date(year, month - 1, prevMonth.getDate() - i);
                    const dayObj = this.createDayObject(dayDate, false);
                    if (dayObj && !seenDates.has(dayObj.date)) {
                        days.push(dayObj);
                        seenDates.add(dayObj.date);
                    }
                }
                
                // Current month days
                for (let day = 1; day <= lastDay.getDate(); day++) {
                    const dayDate = new Date(year, month, day);
                    const dayObj = this.createDayObject(dayDate, true);
                    if (dayObj && !seenDates.has(dayObj.date)) {
                        days.push(dayObj);
                        seenDates.add(dayObj.date);
                    }
                }
                
                // Next month days to fill the grid
                const totalCells = Math.ceil(days.length / 7) * 7;
                let nextMonthDay = 1;
                for (let i = days.length; i < totalCells; i++) {
                    const dayDate = new Date(year, month + 1, nextMonthDay);
                    const dayObj = this.createDayObject(dayDate, false);
                    if (dayObj && !seenDates.has(dayObj.date)) {
                        days.push(dayObj);
                        seenDates.add(dayObj.date);
                    }
                    nextMonthDay++;
                }
                
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

