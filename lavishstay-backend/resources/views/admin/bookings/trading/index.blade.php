<x-app-layout>    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div x-data="adminPanel()" x-init="init()">
            <!-- Bookings Table with Integrated Header -->
            <div class=" dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden  dark:border-gray-700">
                <!-- Integrated Header with Filters -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Left side: Title and Stats -->
                        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                            <div>
                                <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                    </svg>
                                    Quản lý đặt phòng 
                                </h1>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    <span x-text="filteredBookings.length"></span> / <span x-text="bookings.length"></span> booking
                                </p>
                            </div>
                            
                            <!-- Compact Stats -->
                            <div class="flex gap-4 text-xs ">
                                <div class=" dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-600 dark:text-gray-400">Chờ duyệt:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-1" x-text="bookings.filter(b => b.payment_status === 'pending').length"></span>
                                </div>
                                <div class=" dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-600 dark:text-gray-400">Đã duyệt:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-1" x-text="bookings.filter(b => b.payment_status === 'confirmed').length"></span>
                                </div>
                                <div class=" dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-600 dark:text-gray-400">Tổng:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-1" x-text="formatCurrency(bookings.reduce((sum, b) => sum + parseFloat(b.total_amount || 0), 0))"></span>
                                </div>
                                    <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                            <!-- Compact Filters -->
                            <div class="flex gap-4 text-xs">
                                <!-- Status Filter -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="inline-flex items-center px-3 py-2 text-xs border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300  dark:bg-gray-700  dark:">
                                        <span x-text="statusFilter === '' ? 'Tất cả' : (statusFilter === 'pending' ? 'Chờ' : 'Duyệt')"></span>
                                        <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="absolute z-50 mt-1 w-32  dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600">
                                        <div class="py-1">
                                            <button @click="statusFilter = ''; open = false" 
                                                    class="block w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300  dark:">
                                                Tất cả
                                            </button>
                                            <button @click="statusFilter = 'pending'; open = false" 
                                                    class="block w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300  dark:">
                                                Chờ duyệt
                                            </button>
                                            <button @click="statusFilter = 'confirmed'; open = false" 
                                                    class="block w-full text-left px-3 py-2 text-xs text-gray-700 dark:text-gray-300  dark:">
                                                Đã duyệt
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date Filter -->
                                <input x-model="dateFilter"
                                       type="date"
                                       class="px-3 py-2 text-xs border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300  dark:bg-gray-700">
                                       <!-- Search -->
                                       <div class="">
                                           <input x-model="searchQuery"
                                                  type="text"
                                                  placeholder="Tìm kiếm..."
                                                  class=" pr-3 py-2 text-xs border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300  dark:bg-gray-700 w-40">
                                       </div>
                                       
                                       <!-- Refresh Button -->
                                       <button @click="fetchBookings()" 
                                               :disabled="loading"
                                               class="px-3 py-2 text-xs border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300  dark:bg-gray-700  dark: disabled:opacity-50">
                                           <svg class="w-3 h-3" :class="{'animate-spin': loading}" fill="currentColor" viewBox="0 0 20 20">
                                               <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                           </svg>
                                           </button>
                                       </div>
                                    </div>
                            
                            </div>
                        </div>
                        
                  
                    
                    </div>
                    
                    <!-- Active Filters Display -->
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600" x-show="statusFilter || searchQuery || dateFilter">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Lọc:</span>
                            <template x-if="statusFilter">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    <span x-text="statusFilter === 'pending' ? 'Chờ duyệt' : 'Đã duyệt'"></span>
                                    <button @click="statusFilter = ''" class="ml-1 text-gray-500">×</button>
                                </span>
                            </template>
                            <template x-if="dateFilter">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    <span x-text="dateFilter"></span>
                                    <button @click="dateFilter = ''" class="ml-1 text-gray-500">×</button>
                                </span>
                            </template>
                            <button @click="statusFilter = ''; searchQuery = ''; dateFilter = ''" 
                                    class="text-xs text-gray-500 hover:text-gray-700 underline">
                                Xóa tất cả
                            </button>
                        </div>
                    </div>                
                <!-- Loading State -->
                <div x-show="loading" class="p-8 text-center">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin h-8 w-8 text-gray-600 mr-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-lg text-gray-700 dark:text-gray-300">Đang tải dữ liệu...</span>
                    </div>
                </div>

                <!-- Empty State -->
                <div x-show="!loading && filteredBookings.length === 0" class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Không có booking nào</h3>
                    <p class="text-gray-600 dark:text-gray-400">Hiện tại chưa có booking nào phù hợp với bộ lọc.</p>
                </div>                <!-- Table Content -->
                <div x-show="!loading && filteredBookings.length > 0" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Booking
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Khách hàng
                                </th>                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Phòng & Thời gian
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Số tiền
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class=" dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="booking in filteredBookings" :key="booking.id">
                                <tr class=" dark:/50 transition-colors duration-200">
                                    <!-- Booking Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                ID: <span x-text="booking.id"></span>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs" x-text="booking.booking_code"></span>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span x-text="formatDateTime(booking.created_at)"></span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Customer Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400" x-text="(booking.customer_name || 'G').charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="booking.customer_name || 'Khách'"></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="booking.customer_email || 'N/A'"></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="booking.customer_phone || 'N/A'"></div>
                                            </div>
                                        </div>
                                    </td>
                                      <!-- Room Info (Optimized) -->
                                    <td class="px-6 py-4" x-data="{ expanded: false }">
                                        <div class="min-w-0">
                                            <!-- Compact Room Summary -->
                                            <div class="text-sm text-gray-900 dark:text-white font-medium">
                                                <span x-text="getRoomSummary(booking.rooms_data)"></span>
                                                <template x-if="getRoomCount(booking.rooms_data) > 1">
                                                    <button @click="expanded = !expanded" 
                                                            class="ml-1 text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 underline">
                                                        <span x-text="expanded ? 'ẩn' : '+' + (getRoomCount(booking.rooms_data) - 1) + ' phòng khác'"></span>
                                                    </button>
                                                </template>
                                            </div>
                                            
                                            <!-- Expanded Room Details -->
                                            <div x-show="expanded" x-transition class="mt-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-2 rounded border">
                                                <span x-text="getRoomInfo(booking.rooms_data)"></span>
                                            </div>
                                            
                                            <!-- Compact Date & Nights -->
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <div class="flex items-center space-x-2">
                                                    <span x-text="formatDateRange(booking.check_in, booking.check_out)"></span>
                                                    <span class="text-gray-400">•</span>
                                                    <span x-text="calculateNights(booking.check_in, booking.check_out) + ' đêm'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Amount -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-bold text-gray-900 dark:text-white" x-text="formatCurrency(booking.total_amount)"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400" x-text="booking.payment_method || 'vietqr'"></div>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div x-show="booking.payment_status === 'pending'" 
                                              class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Chờ duyệt
                                        </div>
                                        <div x-show="booking.payment_status === 'confirmed'" 
                                              class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Đã xác nhận
                                        </div>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- View Details Button -->
                                            <button @click="showDetails(booking)" 
                                                    class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300  dark:bg-gray-700  dark: transition-colors duration-200">
                                                Chi tiết
                                            </button>
                                            
                                            <!-- Approve Button -->
                                            <button x-show="booking.payment_status === 'pending'" 
                                                    @click="confirmPayment(booking.id)" 
                                                    :disabled="confirming"
                                                    class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300  dark:bg-gray-700  dark: transition-colors duration-200">
                                                Duyệt
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>        <!-- Detail Modal -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4"
             @click.self="showModal = false">
            
            <div class="relative  dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden border border-gray-200 dark:border-gray-700"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-6 h-6 mr-2 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Chi tiết booking
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                                Mã: <span x-text="selectedBooking?.booking_code" class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"></span>
                            </p>
                        </div>
                        <button @click="showModal = false" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-2 rounded-lg  dark:">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 max-h-[calc(90vh-180px)] overflow-y-auto" x-show="selectedBooking">
                    
                    <!-- Customer Info Card -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-600">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Thông tin khách hàng
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-gray-700 dark:text-gray-300 font-bold text-lg" x-text="selectedBooking?.customer_name ? selectedBooking.customer_name.charAt(0).toUpperCase() : 'G'"></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedBooking?.customer_name || 'Khách'"></p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedBooking?.customer_email || 'N/A'"></p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Điện thoại</label>
                                <p class="text-gray-900 dark:text-white font-medium" x-text="selectedBooking?.customer_phone || 'N/A'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details Cards -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Room Info -->
                        <div class=" dark:bg-gray-700 rounded-xl p-6 border border-gray-200 dark:border-gray-600 shadow-sm">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Thông tin phòng
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chi tiết phòng</label>
                                    <p class="text-gray-900 dark:text-white font-medium" x-text="getRoomInfo(selectedBooking?.rooms_data)"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Check-in</label>
                                        <p class="text-gray-900 dark:text-white" x-text="formatDate(selectedBooking?.check_in)"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Check-out</label>
                                        <p class="text-gray-900 dark:text-white" x-text="formatDate(selectedBooking?.check_out)"></p>
                                    </div>
                                </div>
                                <div x-show="selectedBooking?.special_requests">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Yêu cầu đặc biệt</label>
                                    <p class="text-gray-900 dark:text-white text-sm bg-gray-50 dark:bg-gray-600 p-3 rounded-lg" x-text="selectedBooking?.special_requests"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class=" dark:bg-gray-700 rounded-xl p-6 border border-gray-200 dark:border-gray-600 shadow-sm">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                </svg>
                                Thông tin thanh toán
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tổng tiền</label>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(selectedBooking?.total_amount)"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái</label>
                                    <div x-show="selectedBooking?.payment_status === 'pending'" 
                                          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Chờ xác nhận
                                    </div>
                                    <div x-show="selectedBooking?.payment_status === 'confirmed'" 
                                          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Đã xác nhận
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức</label>
                                    <p class="text-gray-900 dark:text-white" x-text="selectedBooking?.payment_method || 'VietQR'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung CK</label>
                                    <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-600 px-3 py-2 rounded-lg font-mono text-sm" 
                                       x-text="selectedBooking?.booking_code"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class=" dark:bg-gray-700 rounded-xl p-6 border border-gray-200 dark:border-gray-600 shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Lịch sử booking
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-gray-400 rounded-full mr-3"></div>
                                <span class="text-gray-700 dark:text-gray-300">Tạo booking:</span>
                                <span class="ml-2 text-gray-900 dark:text-white font-medium" x-text="formatDateTime(selectedBooking?.created_at)"></span>
                            </div>
                            <div x-show="selectedBooking?.payment_status === 'confirmed'" class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-gray-600 rounded-full mr-3"></div>
                                <span class="text-gray-700 dark:text-gray-300">Xác nhận thanh toán:</span>
                                <span class="ml-2 text-gray-900 dark:text-white font-medium" x-text="formatDateTime(selectedBooking?.updated_at)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <button @click="showModal = false" 
                                class="px-6 py-2 bg-gray-300  text-gray-800 rounded-lg transition-colors">
                            Đóng
                        </button>
                        <div class="space-x-3">
                            <button x-show="selectedBooking?.payment_status === 'pending'" 
                                    @click="confirmPayment(selectedBooking?.id); showModal = false" 
                                    :disabled="confirming"
                                    class="px-6 py-2 bg-gray-800  disabled:bg-gray-400 text-white rounded-lg transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Xác nhận thanh toán
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>    <script>
        function adminPanel() {
            return {
                bookings: [],
                statusFilter: '',
                searchQuery: '',
                dateFilter: '',
                loading: false,
                confirming: false,
                showModal: false,
                selectedBooking: null,
                lastUpdate: '',
                
                get filteredBookings() {
                    let filtered = this.bookings;
                    
                    // Filter by status
                    if (this.statusFilter) {
                        filtered = filtered.filter(booking => booking.payment_status === this.statusFilter);
                    }
                    
                    // Filter by date
                    if (this.dateFilter) {
                        filtered = filtered.filter(booking => {
                            const bookingDate = new Date(booking.created_at).toISOString().split('T')[0];
                            return bookingDate === this.dateFilter;
                        });
                    }
                    
                    // Filter by search query
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(booking => 
                            booking.booking_code?.toLowerCase().includes(query) ||
                            booking.customer_name?.toLowerCase().includes(query) ||
                            booking.customer_email?.toLowerCase().includes(query) ||
                            booking.customer_phone?.toLowerCase().includes(query)
                        );
                    }
                    
                    return filtered;
                },
                
                async init() {
                    console.log('Admin Panel Initialized!');
                    await this.fetchBookings();
                    this.updateLastUpdateTime();
                    
                    // Auto refresh every 30 seconds
                    setInterval(() => {
                        this.fetchBookings();
                    }, 30000);
                },
                
                async fetchBookings() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/payment/admin/pending');
                        const result = await response.json();
                        
                        if (result.success) {
                            this.bookings = result.data;
                            this.updateLastUpdateTime();
                        } else {
                            this.showError('Lỗi khi tải dữ liệu: ' + (result.message || ''));
                        }
                    } catch (error) {
                        console.error('Error fetching bookings:', error);
                        this.showError('Lỗi kết nối API');
                    } finally {
                        this.loading = false;
                    }
                },

                async confirmPayment(bookingId) {
                    if (!confirm(`Xác nhận thanh toán cho booking này?`)) {
                        return;
                    }

                    this.confirming = true;
                    try {
                        const response = await fetch(`/api/payment/admin/confirm/${bookingId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.showSuccess(`Đã xác nhận thanh toán cho booking ${result.data.booking_code}`);
                            await this.fetchBookings(); // Refresh list
                        } else {
                            this.showError(result.message || 'Lỗi khi xác nhận thanh toán');
                        }
                    } catch (error) {
                        console.error('Error confirming payment:', error);
                        this.showError('Lỗi kết nối API');
                    } finally {
                        this.confirming = false;
                    }
                },

                showDetails(booking) {
                    this.selectedBooking = booking;
                    this.showModal = true;
                },

                getRoomInfo(roomsData) {
                    if (!roomsData) return 'N/A';
                    
                    try {
                        // Parse if it's a string
                        const data = typeof roomsData === 'string' ? JSON.parse(roomsData) : roomsData;
                        
                        if (data.rooms) {
                            let roomInfo = [];
                            
                            // Iterate through rooms object
                            Object.keys(data.rooms).forEach(roomId => {
                                const roomTypes = data.rooms[roomId];
                                Object.keys(roomTypes).forEach(roomType => {
                                    const quantity = roomTypes[roomType];
                                    // Format room type name
                                    const formattedType = roomType.replace(/_/g, ' ')
                                                                 .replace(/\b\w/g, l => l.toUpperCase());
                                    roomInfo.push(`${formattedType} (x${quantity})`);
                                });
                            });
                            
                            return roomInfo.join(', ') || 'N/A';
                        }
                        
                        return 'N/A';
                    } catch (e) {
                        console.error('Error parsing room data:', e);
                        return 'N/A';
                    }                },

                getRoomSummary(roomsData) {
                    if (!roomsData) return 'N/A';
                    
                    try {
                        const data = typeof roomsData === 'string' ? JSON.parse(roomsData) : roomsData;
                        
                        if (data.rooms) {
                            const firstRoom = Object.keys(data.rooms)[0];
                            if (firstRoom) {
                                const roomTypes = data.rooms[firstRoom];
                                const firstRoomType = Object.keys(roomTypes)[0];
                                const quantity = roomTypes[firstRoomType];
                                
                                // Format first room type name
                                const formattedType = firstRoomType.replace(/_/g, ' ')
                                                                 .replace(/\b\w/g, l => l.toUpperCase());
                                return `${formattedType} (x${quantity})`;
                            }
                        }
                        
                        return 'N/A';
                    } catch (e) {
                        console.error('Error parsing room data:', e);
                        return 'N/A';
                    }
                },

                getRoomCount(roomsData) {
                    if (!roomsData) return 0;
                    
                    try {
                        const data = typeof roomsData === 'string' ? JSON.parse(roomsData) : roomsData;
                        
                        if (data.rooms) {
                            let totalRooms = 0;
                            Object.keys(data.rooms).forEach(roomId => {
                                const roomTypes = data.rooms[roomId];
                                Object.keys(roomTypes).forEach(roomType => {
                                    totalRooms += roomTypes[roomType];
                                });
                            });
                            return totalRooms;
                        }
                        
                        return 0;
                    } catch (e) {
                        return 0;
                    }
                },

                formatDateRange(checkIn, checkOut) {
                    if (!checkIn || !checkOut) return 'N/A';
                    
                    const startDate = new Date(checkIn);
                    const endDate = new Date(checkOut);
                    
                    const formatShort = (date) => {
                        return date.toLocaleDateString('vi-VN', { 
                            day: '2-digit', 
                            month: '2-digit' 
                        });
                    };
                    
                    return `${formatShort(startDate)} → ${formatShort(endDate)}`;
                },

                calculateNights(checkIn, checkOut) {
                    if (!checkIn || !checkOut) return 0;
                    
                    const startDate = new Date(checkIn);
                    const endDate = new Date(checkOut);
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    return diffDays;
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    }).format(amount);
                },

                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    return new Date(dateString).toLocaleDateString('vi-VN');
                },

                formatDateTime(dateString) {
                    if (!dateString) return 'N/A';
                    return new Date(dateString).toLocaleString('vi-VN');
                },

                updateLastUpdateTime() {
                    this.lastUpdate = new Date().toLocaleTimeString('vi-VN');
                },

                showSuccess(message) {
                    // Create a better notification system
                    this.showNotification(message, 'success');
                },

                showError(message) {
                    this.showNotification(message, 'error');
                },

                showNotification(message, type) {
                    // Simple notification - you can enhance this with a toast library
                    const icon = type === 'success' ? '✅' : '❌';
                    alert(`${icon} ${message}`);
                }
            };
        }
    </script>
    
</x-app-layout>
