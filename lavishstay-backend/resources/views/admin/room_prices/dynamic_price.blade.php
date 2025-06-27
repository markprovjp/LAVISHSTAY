<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý giá động</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Tự động điều chỉnh giá dựa trên tỷ lệ lấp đầy phòng</p>
            </div>
            
            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button onclick="showModal()" 
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm quy tắc</span>
                </button>
            </div>
        </div>

        <!-- Occupancy Statistics -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tỷ lệ lấp đầy hiện tại</h2>
                    <button onclick="refreshOccupancyStats()" 
                        class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Làm mới
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="occupancyStatsContainer">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                        <p class="mt-2">Đang tải dữ liệu...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Price Calculator -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Công cụ tính giá động</h2>
            </div>
            <div class="p-6">
                <form id="priceCalculatorForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại phòng</label>
                        <select id="calcRoomType" name="room_type_id" required
                            class="form-select w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Chọn loại phòng</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Giá gốc (VND)</label>
                        <input type="number" id="calcBasePrice" name="base_price" required min="0" step="1000"
                            class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="500000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày</label>
                        <input type="date" id="calcDate" name="date" required
                            class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                            class="w-full btn bg-indigo-600 hover:bg-indigo-700 text-white">
                            Tính toán
                        </button>
                    </div>
                </form>
                
                <!-- Price calculation result -->
                <div id="priceCalculationResult" class="hidden mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>

        <!-- Dynamic Pricing Rules Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Danh sách quy tắc giá động</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto w-full dark:text-gray-300">
                    <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Loại phòng</th>
                            <th class="px-6 py-4 text-left">Ngưỡng lấp đầy</th>
                            <th class="px-6 py-4 text-left">Điều chỉnh giá</th>
                            <th class="px-6 py-4 text-left">Lấp đầy hiện tại</th>
                            <th class="px-6 py-4 text-left">Trạng thái kích hoạt</th>
                            <th class="px-6 py-4 text-left">Hoạt động</th>
                            <th class="px-6 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="dynamicPricingTableBody" class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        <!-- Table rows will be populated here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <!-- Pagination will be populated here -->
            </div>
        </div>
    </div>

    <!-- Dynamic Pricing Modal -->
    <div id="dynamicPricingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Thêm quy tắc giá động
                    </h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="dynamicPricingForm" onsubmit="handleSubmit(event)">
                    <input type="hidden" id="ruleId" name="rule_id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Loại phòng <span class="text-red-500">*</span>
                        </label>
                        <select id="roomTypeId" name="room_type_id" required
                            class="form-select w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Chọn loại phòng</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ngưỡng tỷ lệ lấp đầy (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="occupancyThreshold" name="occupancy_threshold" required min="0" max="100" step="0.01"
                            class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Ví dụ: 80">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Khi tỷ lệ lấp đầy đạt ngưỡng này, quy tắc sẽ được áp dụng</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tỷ lệ điều chỉnh giá (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="priceAdjustment" name="price_adjustment" required min="-100" max="500" step="0.01"
                            class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Ví dụ: 20 (tăng 20%) hoặc -10 (giảm 10%)">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Số dương để tăng giá, số âm để giảm giá</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Hủy
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText">Thêm mới</span>
                            <div id="submitLoading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Đang xử lý...
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let isEditMode = false;
        let editingId = null;
        let roomTypes = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadRoomTypes();
            loadData();
            loadOccupancyStats();
            
            // Set default date to today
            document.getElementById('calcDate').value = new Date().toISOString().split('T')[0];
            
            // Setup price calculator form
            document.getElementById('priceCalculatorForm').addEventListener('submit', handlePriceCalculation);
        });

        // Load room types
        async function loadRoomTypes() {
            try {
                const response = await fetch('{{ route("admin.dynamic-pricing.room-types") }}');
                const result = await response.json();
                
                roomTypes = result;
                
                // Populate room type selects
                const selects = ['roomTypeId', 'calcRoomType'];
                selects.forEach(selectId => {
                    const select = document.getElementById(selectId);
                    select.innerHTML = '<option value="">Chọn loại phòng</option>';
                    
                    roomTypes.forEach(roomType => {
                        select.innerHTML += `<option value="${roomType.room_type_id}">${roomType.name}</option>`;
                    });
                });
                
            } catch (error) {
                console.error('Error loading room types:', error);
                showNotification('Có lỗi xảy ra khi tải danh sách loại phòng', 'error');
            }
        }

        // Load occupancy statistics
        async function loadOccupancyStats() {
            try {
                const response = await fetch('{{ route("admin.dynamic-pricing.occupancy-stats") }}');
                const stats = await response.json();
                
                renderOccupancyStats(stats);
                
            } catch (error) {
                console.error('Error loading occupancy stats:', error);
                document.getElementById('occupancyStatsContainer').innerHTML = `
                    <div class="text-center text-red-500">
                        <p>Có lỗi xảy ra khi tải dữ liệu thống kê</p>
                    </div>
                `;
            }
        }

        // Refresh occupancy statistics
        function refreshOccupancyStats() {
            document.getElementById('occupancyStatsContainer').innerHTML = `
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                    <p class="mt-2">Đang tải dữ liệu...</p>
                </div>
            `;
            loadOccupancyStats();
        }

        // Load dynamic pricing rules data
        async function loadData(page = 1) {
            try {
                const response = await fetch(`{{ route("admin.dynamic-pricing.data") }}?page=${page}`);
                const result = await response.json();
                
                currentPage = page;
                renderTable(result.data);
                renderPagination(result);
                
            } catch (error) {
                console.error('Error loading data:', error);
                showNotification('Có lỗi xảy ra khi tải dữ liệu', 'error');
            }
        }

        // Render occupancy statistics
        function renderOccupancyStats(stats) {
            const container = document.getElementById('occupancyStatsContainer');
            
            if (!stats || stats.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <p>Chưa có dữ liệu thống kê</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    ${stats.map(stat => {
                        const occupancyColor = 
                            stat.occupancy_rate >= 90 ? 'text-red-600 dark:text-red-400' :
                            stat.occupancy_rate >= 70 ? 'text-orange-600 dark:text-orange-400' :
                            stat.occupancy_rate >= 50 ? 'text-yellow-600 dark:text-yellow-400' :
                            'text-green-600 dark:text-green-400';
                        
                        const bgColor = 
                            stat.occupancy_rate >= 90 ? 'bg-red-50 dark:bg-red-900/20' :
                            stat.occupancy_rate >= 70 ? 'bg-orange-50 dark:bg-orange-900/20' :
                            stat.occupancy_rate >= 50 ? 'bg-yellow-50 dark:bg-yellow-900/20' :
                            'bg-green-50 dark:bg-green-900/20';
                        
                        return `
                            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 ${bgColor}">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">${stat.room_type_name}</h3>
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        ${stat.status}
                                    </span>
                                </div>
                                <div class="text-2xl font-bold ${occupancyColor} mb-1">
                                    ${stat.occupancy_rate}%
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    ${stat.total_rooms - stat.available_rooms}/${stat.total_rooms} phòng đã đặt
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
        }

        // Handle price calculation
        async function handlePriceCalculation(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            
            try {
                const response = await fetch('{{ route("admin.dynamic-pricing.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    renderPriceCalculationResult(result.data);
                } else {
                    showNotification('Có lỗi xảy ra khi tính toán giá', 'error');
                }
                
            } catch (error) {
                console.error('Error calculating price:', error);
                showNotification('Có lỗi xảy ra khi tính toán giá', 'error');
            }
        }

        // Render price calculation result
        function renderPriceCalculationResult(data) {
            const container = document.getElementById('priceCalculationResult');
            const priceChangeColor = data.price_adjustment > 0 ? 'text-red-600' : data.price_adjustment < 0 ? 'text-green-600' : 'text-gray-600';
            const priceChangeIcon = data.price_adjustment > 0 ? '↗' : data.price_adjustment < 0 ? '↘' : '→';
            
            container.innerHTML = `
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Giá gốc</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            ${formatCurrency(data.base_price)}
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Tỷ lệ lấp đầy</div>
                        <div class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                            ${data.occupancy_rate}%
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Điều chỉnh</div>
                        <div class="text-lg font-semibold ${priceChangeColor}">
                            ${data.price_adjustment > 0 ? '+' : ''}${data.price_adjustment}%
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Giá sau điều chỉnh</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            ${formatCurrency(data.adjusted_price)}
                            <span class="text-sm ${priceChangeColor}">${priceChangeIcon}</span>
                        </div>
                    </div>
                </div>
                ${data.price_difference !== 0 ? `
                    <div class="mt-4 p-3 rounded-lg ${data.price_difference > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20'}">
                        <div class="text-sm ${data.price_difference > 0 ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300'}">
                            ${data.price_difference > 0 ? 'Tăng' : 'Giảm'} ${formatCurrency(Math.abs(data.price_difference))} so với giá gốc
                        </div>
                    </div>
                ` : ''}
            `;
            
            container.classList.remove('hidden');
        }

        // Render table
        function renderTable(rules) {
            const tbody = document.getElementById('dynamicPricingTableBody');
            
            if (!rules || rules.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="text-lg font-medium mb-2">Chưa có quy tắc giá động</h3>
                                <p class="text-sm">Nhấn "Thêm quy tắc" để bắt đầu</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = rules.map(rule => {
                const isTriggered = rule.is_triggered;
                const occupancyColor = isTriggered ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400';
                const adjustmentColor = rule.price_adjustment >= 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400';
                const statusColor = rule.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                const triggerStatusColor = isTriggered ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                
                return `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${rule.rule_id}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                            ${rule.room_type_name || 'N/A'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${rule.occupancy_threshold}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium ${adjustmentColor}">
                            ${rule.price_adjustment > 0 ? '+' : ''}${rule.price_adjustment}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium ${occupancyColor}">
                            ${rule.current_occupancy}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${triggerStatusColor}">
                                ${isTriggered ? 'Đang kích hoạt' : 'Chưa kích hoạt'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleStatus(${rule.rule_id})"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColor} hover:opacity-80 transition-opacity">
                                ${rule.is_active ? 'Hoạt động' : 'Tạm dừng'}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                    class="inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                    onclick="toggleDropdown(${rule.rule_id})"
                                    id="dropdown-button-${rule.rule_id}">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="dropdown-menu-${rule.rule_id}"
                                    class="hidden absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1" role="menu">
                                        <!-- Edit -->
                                        <button onclick="editRule(${rule.rule_id}); closeDropdown(${rule.rule_id})"
                                            class="flex cursor-pointer items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Chỉnh sửa
                                        </button>

                                        <!-- Toggle Status -->
                                        <button onclick="toggleStatus(${rule.rule_id}); closeDropdown(${rule.rule_id})"
                                            class="flex cursor-pointer items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                            ${rule.is_active ? 'Tạm dừng' : 'Kích hoạt'}
                                        </button>

                                        <!-- Divider -->
                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                        <!-- Delete -->
                                        <button onclick="deleteRule(${rule.rule_id}); closeDropdown(${rule.rule_id})"
                                            class="flex cursor-pointer items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Render pagination
        function renderPagination(data) {
            const container = document.getElementById('paginationContainer');
            
            if (data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }
            
            let paginationHtml = '<div class="flex items-center justify-between">';
            
            // Info
            paginationHtml += `
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Hiển thị <span class="font-medium">${data.from || 0}</span> đến <span class="font-medium">${data.to || 0}</span> 
                    trong tổng số <span class="font-medium">${data.total}</span> kết quả
                </div>
            `;
            
            // Pagination buttons
            paginationHtml += '<div class="flex items-center space-x-2">';
            
            // Previous button
            if (data.current_page > 1) {
                paginationHtml += `
                    <button onclick="loadData(${data.current_page - 1})" 
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700">
                        Trước
                    </button>
                `;
            }
            
            // Page numbers
            const startPage = Math.max(1, data.current_page - 2);
            const endPage = Math.min(data.last_page, data.current_page + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === data.current_page;
                paginationHtml += `
                    <button onclick="loadData(${i})" 
                        class="px-3 py-2 text-sm font-medium ${isActive 
                            ? 'text-indigo-600 bg-indigo-50 border-indigo-500 dark:bg-indigo-900/20 dark:text-indigo-400' 
                            : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'
                        } border rounded-md">
                        ${i}
                    </button>
                `;
            }
            
            // Next button
            if (data.current_page < data.last_page) {
                paginationHtml += `
                    <button onclick="loadData(${data.current_page + 1})" 
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700">
                        Sau
                    </button>
                `;
            }
            
            paginationHtml += '</div></div>';
            container.innerHTML = paginationHtml;
        }

        // Show modal
        function showModal() {
            isEditMode = false;
            editingId = null;
            
            document.getElementById('modalTitle').textContent = 'Thêm quy tắc giá động';
            document.getElementById('submitText').textContent = 'Thêm mới';
            document.getElementById('dynamicPricingForm').reset();
            document.getElementById('ruleId').value = '';
            
            document.getElementById('dynamicPricingModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('dynamicPricingModal').classList.add('hidden');
            document.getElementById('dynamicPricingForm').reset();
        }

        // Edit rule
        async function editRule(id) {
            try {
                const response = await fetch(`{{ url('admin/dynamic-pricing') }}/${id}`);
                const result = await response.json();
                
                if (result.success) {
                    const rule = result.data;
                    
                    isEditMode = true;
                    editingId = id;
                    
                    document.getElementById('modalTitle').textContent = 'Chỉnh sửa quy tắc giá động';
                    document.getElementById('submitText').textContent = 'Cập nhật';
                    
                    document.getElementById('ruleId').value = rule.rule_id;
                    document.getElementById('roomTypeId').value = rule.room_type_id;
                    document.getElementById('occupancyThreshold').value = rule.occupancy_threshold;
                    document.getElementById('priceAdjustment').value = rule.price_adjustment;
                    
                    document.getElementById('dynamicPricingModal').classList.remove('hidden');
                } else {
                    showNotification('Không thể tải thông tin quy tắc', 'error');
                }
                
            } catch (error) {
                console.error('Error loading rule:', error);
                showNotification('Có lỗi xảy ra khi tải thông tin quy tắc', 'error');
            }
        }

        // Handle form submit
        async function handleSubmit(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                const formData = new FormData(event.target);
                const data = Object.fromEntries(formData.entries());
                
                const url = isEditMode 
                    ? `{{ url('admin/dynamic-pricing') }}/${editingId}`
                    : '{{ route("admin.dynamic-pricing.store") }}';
                
                const method = isEditMode ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message, 'success');
                    closeModal();
                    loadData(currentPage);
                    loadOccupancyStats(); // Refresh stats
                } else {
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('\n');
                        showNotification(errorMessages, 'error');
                    } else {
                        showNotification(result.message || 'Có lỗi xảy ra', 'error');
                    }
                }
                
            } catch (error) {
                console.error('Error submitting form:', error);
                showNotification('Có lỗi xảy ra khi xử lý yêu cầu', 'error');
            } finally {
                // Reset loading state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        // Toggle status
        async function toggleStatus(id) {
            try {
                const response = await fetch(`{{ url('admin/dynamic-pricing') }}/${id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message, 'success');
                    loadData(currentPage);
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra', 'error');
                }
                
            } catch (error) {
                console.error('Error toggling status:', error);
                showNotification('Có lỗi xảy ra khi thay đổi trạng thái', 'error');
            }
        }

        // Delete rule
        async function deleteRule(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa quy tắc này? Hành động này không thể hoàn tác.')) {
                return;
            }
            
            try {
                const response = await fetch(`{{ url('admin/dynamic-pricing') }}/${id}`, {
                    method:'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message, 'success');
                    loadData(currentPage);
                    loadOccupancyStats(); // Refresh stats
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra', 'error');
                }
                
            } catch (error) {
                console.error('Error deleting rule:', error);
                showNotification('Có lỗi xảy ra khi xóa quy tắc', 'error');
            }
        }

        // Toggle dropdown menu
        function toggleDropdown(id) {
            const dropdown = document.getElementById(`dropdown-menu-${id}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${id}`) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown
        function closeDropdown(id) {
            const dropdown = document.getElementById(`dropdown-menu-${id}`);
            dropdown.classList.add('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');

            let clickedInsideDropdown = false;

            // Check if clicked inside any dropdown or button
            dropdowns.forEach(dropdown => {
                if (dropdown.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            buttons.forEach(button => {
                if (button.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            // If clicked outside, close all dropdowns
            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Close modal when clicking outside
        document.getElementById('dynamicPricingModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Utility functions
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 transform transition-all duration-300 ease-out translate-x-full opacity-0`;
            
            const bgColor = type === 'success' ? 'bg-green-50 dark:bg-green-900/20' : 
                           type === 'error' ? 'bg-red-50 dark:bg-red-900/20' : 
                           'bg-blue-50 dark:bg-blue-900/20';
            
            const textColor = type === 'success' ? 'text-green-800 dark:text-green-200' : 
                            type === 'error' ? 'text-red-800 dark:text-red-200' : 
                            'text-blue-800 dark:text-blue-200';
            
            const iconColor = type === 'success' ? 'text-green-400' : 
                            type === 'error' ? 'text-red-400' : 
                            'text-blue-400';

            const icon = type === 'success' ? 
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                type === 'error' ? 
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';

            notification.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 ${iconColor}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                ${icon}
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium ${textColor}">
                                ${message}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button onclick="this.closest('.notification').remove()" 
                                class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
                notification.classList.add('translate-x-0', 'opacity-100');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
    </script>

    <style>
        /* Custom styles for better UX */
        .form-input:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .btn {
            @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
        }
        
        .notification {
            backdrop-filter: blur(10px);
        }
        
        /* Loading animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        
        /* Hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }
        
        /* Focus styles */
        .focus\:ring-2:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px var(--tw-ring-color);
        }
    </style>
</x-app-layout>



