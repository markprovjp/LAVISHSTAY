<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý Giá Động</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Điều chỉnh giá theo tỷ lệ lấp đầy phòng</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Sync Occupancy button -->
                <button id="syncOccupancyBtn"
                    class="btn cursor-pointer bg-green-500 hover:bg-green-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" viewBox="0 0 24 24" height="24px" width="24px">
                        <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/>
                    </svg>
                    <span class="max-xs:sr-only">Đồng bộ dữ liệu</span>
                </button>

                <button id="refreshBtn" class="btn cursor-pointer bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zM7 11.4L3.6 8 5 6.6l2 2 4-4L12.4 6 7 11.4z"/>
                    </svg>
                    <span class="max-xs:sr-only">Làm mới</span>
                </button>

                <!-- Add Dynamic Rule button -->
                <button id="addDynamicRuleBtn" class="btn cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm quy tắc</span>
                </button>
            </div>
        </div>

        <!-- Occupancy Statistics Cards -->
        <div id="occupancyStatsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Loading skeleton -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 animate-pulse">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
            </div>
        </div>

        <!-- Dynamic Pricing Rules Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="p-6">
                <div id="tableContainer">
                    <!-- Loading state -->
                    <div id="loadingState" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                        <p class="mt-2 text-gray-500">Đang tải dữ liệu...</p>
                    </div>

                    <!-- Empty state -->
                    <div id="emptyState" class="text-center py-8 hidden">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có quy tắc giá động</h3>
                        <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách thêm quy tắc giá động mới.</p>
                        <div class="mt-6">
                            <button id="addDynamicRuleBtnEmpty" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                                Thêm quy tắc
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div id="tableContent" class="hidden">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full">
                                <thead>
                                    <tr class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20">
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">ID</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Loại phòng</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Ngưỡng lấp đầy (%)</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Điều chỉnh giá (%)</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Tỷ lệ hiện tại</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Thông tin phòng</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Trạng thái</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-center">Thao tác</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody" class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                                    <!-- Dynamic content -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="paginationContainer" class="mt-6">
                            <!-- Dynamic pagination -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Dynamic Rule Modal -->
    <div id="dynamicRuleModal" class="fixed modal-overlay inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100" id="dynamicRuleModalTitle">Thêm quy tắc giá động</h3>
                        <button onclick="closeDynamicRuleModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="dynamicRuleForm" class="space-y-6">
                        <input type="hidden" id="dynamicRuleId" name="rule_id">

                        <!-- Room Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại phòng
                                <span class="text-red-500">*</span></label>
                            <select id="roomTypeId" name="room_type_id" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Chọn loại phòng</option>
                            </select>
                        </div>

                        <!-- Occupancy Threshold -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngưỡng lấp đầy (%)
                                <span class="text-red-500">*</span></label>
                            <input id="occupancyThreshold" name="occupancy_threshold" type="number" required min="0" max="100" step="0.1"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập ngưỡng lấp đầy (VD: 70)">
                            <p class="text-xs text-gray-500 mt-1">Quy tắc sẽ được áp dụng khi tỷ lệ lấp đầy >= ngưỡng này</p>
                        </div>

                        <!-- Price Adjustment -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Điều chỉnh giá (%)
                                <span class="text-red-500">*</span></label>
                            <input id="priceAdjustment" name="price_adjustment" type="number" required step="0.01" min="-100" max="500"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập tỷ lệ điều chỉnh giá (VD: 20 = tăng 20%, -10 = giảm 10%)">
                            <p class="text-xs text-gray-500 mt-1">Số dương để tăng giá, số âm để giảm giá</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="isActive" name="is_active" checked
                                    class="form-checkbox h-5 w-5 text-violet-600">
                                <label for="isActive" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kích hoạt quy tắc
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <button type="button" onclick="closeDynamicRuleModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500">
                                Hủy
                            </button>
                            <button type="submit" id="dynamicRuleSubmitBtn"
                                class="px-4 py-2 text-sm font-medium btn cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white focus:outline-none focus:ring-2 focus:ring-violet-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="dynamicRuleSubmitText">Thêm mới</span>
                                <svg id="dynamicRuleSubmitLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Notification -->
    @if (session('success'))
        <div id="notification"
            class="fixed top-4 right-4 transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md z-50">
            <div class="flex items-center justify-center w-8 h-8 text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3 mr-8">
                <h3 class="font-semibold text-green-700">Thành công!</h3>
                <div class="text-sm text-green-600">{{ session('success') }}</div>
            </div>
            <button onclick="closeNotification()" class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <script>
        // Global variables
        let currentEditingRuleId = null;
        let isSubmitting = false;

        // Safe Dynamic Pricing JavaScript with proper error handling
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the dynamic pricing system safely
            initializeDynamicPricingSafe();
        });

        function initializeDynamicPricingSafe() {
            console.log('🚀 Initializing Dynamic Pricing System...');
            
            // Load initial data with error handling
            loadOccupancyStatsSafe();
            loadRoomTypesSafe();
            loadDataSafe();
            bindEventsSafe();
        }

        // Safe function to load occupancy stats
        async function loadOccupancyStatsSafe() {
            try {
                console.log('📊 Loading occupancy stats...');
                
                const response = await fetch('/admin/dynamic-pricing/occupancy-stats', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('📊 Occupancy stats response:', result);

                if (result.success && result.data) {
                    // Ensure data is an array
                    const stats = Array.isArray(result.data) ? result.data : [];
                    updateOccupancyStatsSafe(stats);
                } else {
                    console.warn('⚠️ No occupancy stats data received');
                    updateOccupancyStatsSafe([]);
                }

            } catch (error) {
                console.error('❌ Error loading occupancy stats:', error);
                showNotificationSafe('Không thể tải thống kê lấp đầy: ' + error.message, 'error');
                updateOccupancyStatsSafe([]);
            }
        }

        // Safe function to update occupancy stats in UI
        function updateOccupancyStatsSafe(stats) {
            try {
                console.log('📊 Updating occupancy stats UI with:', stats);
                
                // Ensure stats is an array
                if (!Array.isArray(stats)) {
                    console.warn('⚠️ Stats is not an array, converting:', stats);
                    stats = [];
                }

                const container = document.getElementById('occupancyStatsContainer');
                if (!container) {
                    console.warn('⚠️ Occupancy stats container not found');
                    return;
                }

                if (stats.length === 0) {
                    container.innerHTML = `
                        <div class="col-span-full text-center py-8">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có dữ liệu</h3>
                                <p class="mt-1 text-sm text-gray-500">Chưa có thống kê lấp đầy phòng nào.</p>
                            </div>
                        </div>
                    `;
                    return;
                }

                let html = '';
                
                stats.forEach(stat => {
                    const occupancyRate = parseFloat(stat.occupancy_rate || 0);
                    const statusColor = getOccupancyStatusColor(occupancyRate);
                    
                    html += `
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">${stat.room_type_name || 'N/A'}</h3>
                                    <p class="text-sm text-gray-500">Loại phòng</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold ${statusColor.text}">${occupancyRate.toFixed(1)}%</div>
                                    <div class="text-xs ${statusColor.bg} ${statusColor.text} px-2 py-1 rounded-full">
                                        ${stat.status || 'N/A'}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <div class="text-gray-500 dark:text-gray-400">Tổng phòng</div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">${stat.total_rooms || 0}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500 dark:text-gray-400">Đã đặt</div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">${stat.booked_rooms || 0}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500 dark:text-gray-400">Còn trống</div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">${stat.available_rooms || 0}</div>
                                </div>
                            </div>
                            <div class="mt-4 text-sm">
                                <div class="text-gray-500 dark:text-gray-400">Quy tắc hoạt động: ${(stat.active_rules || []).length}</div>
                                <div class="text-gray-500 dark:text-gray-400">Quy tắc được kích hoạt: ${(stat.triggered_rules || []).length}</div>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;

            } catch (error) {
                console.error('❌ Error updating occupancy stats UI:', error);
            }
        }

        // Safe function to load room types
        async function loadRoomTypesSafe() {
            try {
                console.log('🏨 Loading room types...');
                
                const response = await fetch('/admin/dynamic-pricing/room-types', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('🏨 Room types response:', result);

                if (result.success && result.data) {
                    // Ensure data is an array
                    const roomTypes = Array.isArray(result.data) ? result.data : [];
                    updateRoomTypesSelectSafe(roomTypes);
                } else {
                    console.warn('⚠️ No room types data received');
                    updateRoomTypesSelectSafe([]);
                }

            } catch (error) {
                console.error('❌ Error loading room types:', error);
                showNotificationSafe('Không thể tải danh sách loại phòng: ' + error.message, 'error');
                updateRoomTypesSelectSafe([]);
            }
        }

        // Safe function to update room types select options
        function updateRoomTypesSelectSafe(roomTypes) {
            try {
                console.log('🏨 Updating room types select with:', roomTypes);
                
                // Ensure roomTypes is an array
                if (!Array.isArray(roomTypes)) {
                    console.warn('⚠️ Room types is not an array, converting:', roomTypes);
                    roomTypes = [];
                }

                const selects = document.querySelectorAll('select[name="room_type_id"]');
                
                selects.forEach(select => {
                    // Clear existing options except the first one (placeholder)
                    const firstOption = select.querySelector('option:first-child');
                    select.innerHTML = '';
                    
                    if (firstOption) {
                        select.appendChild(firstOption);
                    } else {
                        select.innerHTML = '<option value="">Chọn loại phòng</option>';
                    }

                    // Add room type options
                    roomTypes.forEach(roomType => {
                        const option = document.createElement('option');
                        option.value = roomType.room_type_id || '';
                        option.textContent = `${roomType.name || 'N/A'} - $${parseFloat(roomType.base_price || 0).toFixed(2)}`;
                        select.appendChild(option);
                    });
                });

            } catch (error) {
                console.error('❌ Error updating room types select:', error);
            }
        }

        // Safe function to load dynamic pricing data
        async function loadDataSafe(page = 1) {
            try {
                console.log('📋 Loading dynamic pricing data...');
                
                // Show loading state
                showLoadingState();
                
                const response = await fetch(`/admin/dynamic-pricing/data?page=${page}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('📋 Dynamic pricing data response:', result);

                if (result.success && result.data) {
                    // Ensure data is an array
                    const data = Array.isArray(result.data) ? result.data : [];
                    updateDataTableSafe(data, result);
                } else {
                    console.warn('⚠️ No dynamic pricing data received');
                    updateDataTableSafe([], result);
                }

            } catch (error) {
                console.error('❌ Error loading dynamic pricing data:', error);
                showNotificationSafe('Không thể tải dữ liệu quy tắc giá động: ' + error.message, 'error');
                updateDataTableSafe([], {});
            }
        }

        // Show loading state
        function showLoadingState() {
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');
            const tableContent = document.getElementById('tableContent');
            
            if (loadingState) loadingState.classList.remove('hidden');
            if (emptyState) emptyState.classList.add('hidden');
            if (tableContent) tableContent.classList.add('hidden');
        }

        // Safe function to update data table
        function updateDataTableSafe(data, pagination = {}) {
            try {
                console.log('📋 Updating data table with:', data);
                
                // Ensure data is an array
                if (!Array.isArray(data)) {
                    console.warn('⚠️ Data is not an array, converting:', data);
                    data = [];
                }

                const loadingState = document.getElementById('loadingState');
                const emptyState = document.getElementById('emptyState');
                const tableContent = document.getElementById('tableContent');
                const tableBody = document.getElementById('tableBody');

                // Hide loading state
                if (loadingState) loadingState.classList.add('hidden');

                if (data.length === 0) {
                    // Show empty state
                    if (emptyState) emptyState.classList.remove('hidden');
                    if (tableContent) tableContent.classList.add('hidden');
                    return;
                }

                // Show table content
                if (emptyState) emptyState.classList.add('hidden');
                if (tableContent) tableContent.classList.remove('hidden');

                if (!tableBody) {
                    console.warn('⚠️ Table body not found');
                    return;
                }

                let html = '';
                data.forEach(rule => {
                    const occupancyRate = parseFloat(rule.current_occupancy || 0);
                    const isTriggered = rule.is_triggered || false;
                    const statusClass = rule.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                    const triggeredClass = isTriggered ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800';
                    
                    html += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${rule.rule_id || 'N/A'}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${rule.room_type_name || 'N/A'}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">${parseFloat(rule.occupancy_threshold || 0).toFixed(1)}%</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    ${parseFloat(rule.price_adjustment || 0) >= 0 ? '+' : ''}${parseFloat(rule.price_adjustment || 0).toFixed(1)}%
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">${occupancyRate.toFixed(1)}%</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">${rule.available_rooms || 0}/${rule.total_rooms || 0}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                    ${rule.is_active ? 'Hoạt động' : 'Tạm dừng'}
                                </span>
                            </td>
                            <td class="p-2 whitespace-nowrap text-right">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="editRule(${rule.rule_id})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">
                                        Sửa
                                    </button>
                                    <button onclick="toggleRuleStatus(${rule.rule_id})" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 text-sm">
                                        ${rule.is_active ? 'Tạm dừng' : 'Kích hoạt'}
                                    </button>
                                    <button onclick="deleteRule(${rule.rule_id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                        Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                tableBody.innerHTML = html;

                // Update pagination if provided
                if (pagination.total) {
                    updatePaginationSafe(pagination);
                }

            } catch (error) {
                console.error('❌ Error updating data table:', error);
            }
        }

        // Safe function to update pagination
        function updatePaginationSafe(pagination) {
            try {
                const paginationContainer = document.getElementById('paginationContainer');
                if (!paginationContainer) return;

                const currentPage = pagination.current_page || 1;
                const lastPage = pagination.last_page || 1;
                const total = pagination.total || 0;

                let html = `
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Hiển thị ${pagination.from || 0} đến ${pagination.to || 0} trong tổng số ${total} kết quả
                        </div>
                        <div class="flex space-x-1">
                `;

                // Previous button
                if (currentPage > 1) {
                    html += `<button onclick="loadDataSafe(${currentPage - 1})" class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300">Trước</button>`;
                }

                // Page numbers
                for (let i = Math.max(1, currentPage - 2); i <= Math.min(lastPage, currentPage + 2); i++) {
                    const activeClass = i === currentPage ? 'bg-blue-500 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600';
                    html += `<button onclick="loadDataSafe(${i})" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md ${activeClass}">${i}</button>`;
                }

                // Next button
                if (currentPage < lastPage) {
                    html += `<button onclick="loadDataSafe(${currentPage + 1})" class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300">Sau</button>`;
                }

                html += `
                        </div>
                    </div>
                `;

                paginationContainer.innerHTML = html;

            } catch (error) {
                console.error('❌ Error updating pagination:', error);
            }
        }

        // Safe function to bind events
        function bindEventsSafe() {
            try {
                console.log('🔗 Binding events...');

                // Sync occupancy button
                const syncButton = document.getElementById('syncOccupancyBtn');
                if (syncButton) {
                    syncButton.addEventListener('click', syncOccupancySafe);
                }

                // Refresh button
                const refreshButton = document.getElementById('refreshBtn');
                if (refreshButton) {
                    refreshButton.addEventListener('click', () => {
                        loadOccupancyStatsSafe();
                        loadDataSafe();
                    });
                }

                // Add rule buttons
                const addRuleBtn = document.getElementById('addDynamicRuleBtn');
                const addRuleBtnEmpty = document.getElementById('addDynamicRuleBtnEmpty');
                
                if (addRuleBtn) {
                    addRuleBtn.addEventListener('click', openCreateModal);
                }
                if (addRuleBtnEmpty) {
                    addRuleBtnEmpty.addEventListener('click', openCreateModal);
                }

                // Form submission
                const dynamicRuleForm = document.getElementById('dynamicRuleForm');
                if (dynamicRuleForm) {
                    dynamicRuleForm.addEventListener('submit', handleFormSubmit);
                }

            } catch (error) {
                console.error('❌ Error binding events:', error);
            }
        }

        // Safe function to sync occupancy
        async function syncOccupancySafe() {
            try {
                console.log('🔄 Syncing occupancy data...');
                
                const syncButton = document.getElementById('syncOccupancyBtn');
                if (syncButton) {
                    syncButton.disabled = true;
                    syncButton.innerHTML = '<svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span class="max-xs:sr-only">Đang đồng bộ...</span>';
                }
                
                const response = await fetch('/admin/dynamic-pricing/sync-occupancy', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('🔄 Sync response:', result);

                if (result.success) {
                    showNotificationSafe('Đồng bộ dữ liệu lấp đầy thành công!', 'success');
                    // Reload data
                    loadOccupancyStatsSafe();
                    loadDataSafe();
                } else {
                    throw new Error(result.message || 'Sync failed');
                }

            } catch (error) {
                console.error('❌ Error syncing occupancy:', error);
                showNotificationSafe('Lỗi khi đồng bộ dữ liệu: ' + error.message, 'error');
            } finally {
                // Reset sync button
                const syncButton = document.getElementById('syncOccupancyBtn');
                if (syncButton) {
                    syncButton.disabled = false;
                    syncButton.innerHTML = '<svg class="fill-current shrink-0 xs:hidden" viewBox="0 0 24 24" height="24px" width="24px"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg> <span class="max-xs:sr-only">Đồng bộ dữ liệu</span>';
                }
            }
        }

        // Modal functions
        function openCreateModal() {
            console.log('Opening create modal...');
            currentEditingRuleId = null;
            
            // Reset form
            const form = document.getElementById('dynamicRuleForm');
            if (form) form.reset();
            
            // Update modal title and button text
            const modalTitle = document.getElementById('dynamicRuleModalTitle');
            const submitText = document.getElementById('dynamicRuleSubmitText');
            
            if (modalTitle) modalTitle.textContent = 'Thêm quy tắc giá động';
            if (submitText) submitText.textContent = 'Thêm mới';
            
            // Show modal
            const modal = document.getElementById('dynamicRuleModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeDynamicRuleModal() {
            const modal = document.getElementById('dynamicRuleModal');
            if (modal) modal.classList.add('hidden');
            currentEditingRuleId = null;
        }

        // Edit rule function
        async function editRule(ruleId) {
            try {
                console.log('Editing rule:', ruleId);
                
                const response = await fetch(`/admin/dynamic-pricing/${ruleId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                if (result.success && result.data) {
                    const rule = result.data;
                    currentEditingRuleId = ruleId;
                    
                    // Populate form
                    document.getElementById('roomTypeId').value = rule.room_type_id || '';
                    document.getElementById('occupancyThreshold').value = rule.occupancy_threshold || '';
                    document.getElementById('priceAdjustment').value = rule.price_adjustment || '';
                    document.getElementById('isActive').checked = rule.is_active || false;
                    
                    // Update modal title and button text
                    const modalTitle = document.getElementById('dynamicRuleModalTitle');
                    const submitText = document.getElementById('dynamicRuleSubmitText');
                    
                    if (modalTitle) modalTitle.textContent = 'Chỉnh sửa quy tắc giá động';
                    if (submitText) submitText.textContent = 'Cập nhật';
                    
                    // Show modal
                    const modal = document.getElementById('dynamicRuleModal');
                    if (modal) modal.classList.remove('hidden');
                } else {
                    throw new Error(result.message || 'Failed to load rule data');
                }

            } catch (error) {
                console.error('❌ Error editing rule:', error);
                showNotificationSafe('Lỗi khi tải dữ liệu quy tắc: ' + error.message, 'error');
            }
        }

        // Toggle rule status
        async function toggleRuleStatus(ruleId) {
            try {
                console.log('Toggling rule status:', ruleId);
                
                const response = await fetch(`/admin/dynamic-pricing/${ruleId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                if (result.success) {
                    showNotificationSafe(result.message, 'success');
                    loadDataSafe();
                } else {
                    throw new Error(result.message || 'Failed to toggle rule status');
                }

            } catch (error) {
                console.error('❌ Error toggling rule status:', error);
                showNotificationSafe('Lỗi khi thay đổi trạng thái: ' + error.message, 'error');
            }
        }

        // Delete rule
        async function deleteRule(ruleId) {
            if (!confirm('Bạn có chắc chắn muốn xóa quy tắc này?')) {
                return;
            }

            try {
                console.log('Deleting rule:', ruleId);
                
                const response = await fetch(`/admin/dynamic-pricing/${ruleId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                if (result.success) {
                    showNotificationSafe(result.message, 'success');
                    loadDataSafe();
                } else {
                    throw new Error(result.message || 'Failed to delete rule');
                }

            } catch (error) {
                console.error('❌ Error deleting rule:', error);
                showNotificationSafe('Lỗi khi xóa quy tắc: ' + error.message, 'error');
            }
        }

        // Handle form submission
        async function handleFormSubmit(event) {
            event.preventDefault();
            
            if (isSubmitting) return;
            
            try {
                isSubmitting = true;
                
                // Show loading state
                const submitBtn = document.getElementById('dynamicRuleSubmitBtn');
                const submitText = document.getElementById('dynamicRuleSubmitText');
                const submitLoading = document.getElementById('dynamicRuleSubmitLoading');
                
                if (submitBtn) submitBtn.disabled = true;
                if (submitText) submitText.classList.add('hidden');
                if (submitLoading) submitLoading.classList.remove('hidden');
                
                // Get form data
                const formData = new FormData(event.target);
                const data = {
                    room_type_id: formData.get('room_type_id'),
                    occupancy_threshold: formData.get('occupancy_threshold'),
                    price_adjustment: formData.get('price_adjustment'),
                    is_active: formData.get('is_active') ? true : false
                };
                
                // Determine URL and method
                const url = currentEditingRuleId 
                    ? `/admin/dynamic-pricing/${currentEditingRuleId}`
                    : '/admin/dynamic-pricing';
                const method = currentEditingRuleId ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                if (result.success) {
                    showNotificationSafe(result.message, 'success');
                    closeDynamicRuleModal();
                    loadDataSafe();
                    loadOccupancyStatsSafe();
                } else {
                    if (result.errors) {
                        // Show validation errors
                        let errorMessage = 'Dữ liệu không hợp lệ:\n';
                        Object.values(result.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += '- ' + error + '\n';
                            });
                        });
                        showNotificationSafe(errorMessage, 'error');
                    } else {
                        throw new Error(result.message || 'Failed to save rule');
                    }
                }

            } catch (error) {
                console.error('❌ Error submitting form:', error);
                showNotificationSafe('Lỗi khi lưu quy tắc: ' + error.message, 'error');
            } finally {
                isSubmitting = false;
                
                // Reset loading state
                const submitBtn = document.getElementById('dynamicRuleSubmitBtn');
                const submitText = document.getElementById('dynamicRuleSubmitText');
                const submitLoading = document.getElementById('dynamicRuleSubmitLoading');
                
                if (submitBtn) submitBtn.disabled = false;
                if (submitText) submitText.classList.remove('hidden');
                if (submitLoading) submitLoading.classList.add('hidden');
            }
        }

        // Helper function to get occupancy status color
        function getOccupancyStatusColor(occupancyRate) {
            if (occupancyRate >= 90) {
                return { bg: 'bg-red-100', text: 'text-red-800' };
            } else if (occupancyRate >= 75) {
                return { bg: 'bg-orange-100', text: 'text-orange-800' };
            } else if (occupancyRate >= 50) {
                return { bg: 'bg-yellow-100', text: 'text-yellow-800' };
            } else if (occupancyRate >= 25) {
                return { bg: 'bg-blue-100', text: 'text-blue-800' };
            } else {
                return { bg: 'bg-gray-100', text: 'text-gray-800' };
            }
        }

        // Safe notification function
        function showNotificationSafe(message, type = 'success') {
            try {
                // Remove existing notifications
                const existingNotifications = document.querySelectorAll('.dynamic-notification');
                existingNotifications.forEach(notification => notification.remove());

                const colors = {
                    success: {
                        bg: 'from-green-50 to-green-100',
                        border: 'border-green-500',
                        text: 'text-green-600',
                        icon: 'text-green-500'
                    },
                    error: {
                        bg: 'from-red-50 to-red-100',
                        border: 'border-red-500',
                        text: 'text-red-600',
                        icon: 'text-red-500'
                    },
                    warning: {
                        bg: 'from-yellow-50 to-yellow-100',
                        border: 'border-yellow-500',
                        text: 'text-yellow-600',
                        icon: 'text-yellow-500'
                    }
                };

                const color = colors[type] || colors.success;

                // Create notification element
                const notification = document.createElement('div');
                notification.className = `dynamic-notification fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r ${color.bg} border-l-4 ${color.border} shadow-md z-50`;
                notification.innerHTML = `
                    <div class="flex items-center justify-center w-8 h-8 ${color.icon}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success' ? 
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                            }
                        </svg>
                    </div>
                    <div class="ml-3 mr-8">
                        <h3 class="font-semibold ${color.text}">${type === 'success' ? 'Thành công!' : 'Thông báo'}</h3>
                        <div class="text-sm ${color.text}" style="white-space: pre-line;">${message}</div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="absolute right-2 top-2 ${color.text} hover:opacity-75">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;

                document.body.appendChild(notification);

                // Auto close after 3 seconds for success, 5 seconds for errors
                const autoCloseTime = type === 'success' ? 3000 : 5000;
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, autoCloseTime);

            } catch (error) {
                console.error('❌ Error showing notification:', error);
            }
        }

        // Close notification function for session notifications
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.remove();
            }
        }

        // Auto close session notification
        setTimeout(() => {
            closeNotification();
        }, 5000);
    </script>

    <style>
        .modal-overlay {
            z-index: 50;
            background-color: rgba(0, 0, 0, 0.621)
        }

        .button-action {
            position: relative;
        }

        .menu-button-action {
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 50;
            width: 200px;
        }

        .form-input,
        .form-select {
            @apply block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500;
        }

        .form-input:focus,
        .form-select:focus {
            @apply ring-2 ring-violet-500;
        }

        .form-input::placeholder,
        .form-select::placeholder {
            @apply text-gray-400;
        }

        .form-input:disabled,
        .form-select:disabled {
            @apply bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400;
        }

        .form-input:disabled::placeholder,
        .form-select:disabled::placeholder {
            @apply text-gray-400;
        }

        .form-input:disabled:focus,
        .form-select:disabled:focus {
            @apply ring-0;
        }

        /* Custom scrollbar for modals */
        .modal-overlay .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .modal-overlay .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-overlay .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .modal-overlay .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animation for notifications */
        .dynamic-notification {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive table */
        @media (max-width: 768px) {
            .table-auto {
                font-size: 0.875rem;
            }
            
            .table-auto th,
            .table-auto td {
                padding: 0.5rem;
            }
        }

        /* Progress bar for occupancy */
        .occupancy-progress {
            transition: width 0.3s ease;
        }
    </style>

</x-app-layout>
