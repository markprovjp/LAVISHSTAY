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
                    class="btn cursor-pointer bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 24 24">
                        <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/>
                    </svg>
                    <span class="max-xs:sr-only">Đồng bộ dữ liệu</span>
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
        let currentPage = 1;
        let isLoading = false;
        let isDynamicRuleEditMode = false;
        let editingDynamicRuleId = null;

        // DOM elements
        const elements = {
            syncOccupancyBtn: document.getElementById('syncOccupancyBtn'),
            addDynamicRuleBtn: document.getElementById('addDynamicRuleBtn'),
            addDynamicRuleBtnEmpty: document.getElementById('addDynamicRuleBtnEmpty'),
            dynamicRuleModal: document.getElementById('dynamicRuleModal'),
            dynamicRuleForm: document.getElementById('dynamicRuleForm'),
            loadingState: document.getElementById('loadingState'),
            emptyState: document.getElementById('emptyState'),
            tableContent: document.getElementById('tableContent'),
            tableBody: document.getElementById('tableBody'),
            paginationContainer: document.getElementById('paginationContainer'),
            occupancyStatsContainer: document.getElementById('occupancyStatsContainer')
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadOccupancyStats();
            loadRoomTypes();
            loadData();
            bindEvents();

            // Auto close notification
            if (document.getElementById('notification')) {
                setTimeout(() => {
                    closeNotification();
                }, 5000);
            }
        });

        // Bind events
        function bindEvents() {
            // Sync occupancy
            elements.syncOccupancyBtn.addEventListener('click', syncOccupancyData);

            // Add buttons
            elements.addDynamicRuleBtn.addEventListener('click', () => showDynamicRuleModal());
            if (elements.addDynamicRuleBtnEmpty) {
                elements.addDynamicRuleBtnEmpty.addEventListener('click', () => showDynamicRuleModal());
            }

            // Modal form
            elements.dynamicRuleForm.addEventListener('submit', handleDynamicRuleSubmit);

            // Close modal on outside click
            elements.dynamicRuleModal.addEventListener('click', function(e) {
                if (e.target === elements.dynamicRuleModal) {
                    closeDynamicRuleModal();
                }
            });
        }

        // Load occupancy statistics
        async function loadOccupancyStats() {
            try {
                const response = await fetch('{{ route('admin.dynamic-pricing.occupancy-stats') }}');
                const data = await response.json();

                if (response.ok) {
                    updateOccupancyStats(data);
                } else {
                    console.error('Failed to load occupancy stats:', data);
                }
            } catch (error) {
                console.error('Error loading occupancy stats:', error);
            }
        }

        // Update occupancy statistics display
        function updateOccupancyStats(stats) {
            const statsHtml = stats.map(stat => {
                const statusClass = {
                    'Rất cao': 'bg-red-100 text-red-800',
                    'Cao': 'bg-orange-100 text-orange-800',
                    'Trung bình': 'bg-yellow-100 text-yellow-800',
                    'Thấp': 'bg-blue-100 text-blue-800',
                    'Rất thấp': 'bg-gray-100 text-gray-800'
                };

                return `
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">${stat.room_type_name}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass[stat.status] || 'bg-gray-100 text-gray-800'}">
                                ${stat.status}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Tỷ lệ lấp đầy:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${stat.occupancy_rate}%</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Phòng đã đặt:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${stat.booked_rooms}/${stat.total_rooms}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Phòng trống:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${stat.available_rooms}</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                                <div class="bg-blue-600 h-2 rounded-full" style="width: ${stat.occupancy_rate}%"></div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            elements.occupancyStatsContainer.innerHTML = statsHtml;
        }

        // Load room types
        async function loadRoomTypes() {
            try {
                const response = await fetch('{{ route("admin.dynamic-pricing.room-types") }}');
                const roomTypes = await response.json();
                
                const select = document.getElementById('roomTypeId');
                select.innerHTML = '<option value="">Chọn loại phòng</option>';
                
                roomTypes.forEach(roomType => {
                    const option = document.createElement('option');
                    option.value = roomType.room_type_id;
                    option.textContent = roomType.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading room types:', error);
            }
        }

        // Load data
        async function loadData(page = 1) {
            if (isLoading) return;

            isLoading = true;
            showLoading();

            try {
                const response = await fetch(`{{ route('admin.dynamic-pricing.data') }}?page=${page}`);
                const data = await response.json();

                if (response.ok) {
                    currentPage = page;
                    displayData(data);
                } else {
                    showError('Không thể tải dữ liệu: ' + (data.message || 'Lỗi không xác định'));
                }
            } catch (error) {
                console.error('Error loading data:', error);
                showError('Có lỗi xảy ra khi tải dữ liệu');
            } finally {
                isLoading = false;
            }
        }

        // Display data
        function displayData(data) {
            if (!data.data || data.data.length === 0) {
                showEmptyState();
                return;
            }

            showTableContent();

            // Generate table rows
            const rows = data.data.map(rule => {
                const statusClass = rule.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const statusText = rule.is_active ? 'Hoạt động' : 'Không hoạt động';
                
                const triggeredClass = rule.is_triggered ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800';
                const triggeredText = rule.is_triggered ? 'Đang kích hoạt' : 'Chưa kích hoạt';

                return `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="p-2 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${rule.rule_id}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">${rule.room_type_name}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${rule.occupancy_threshold}%</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm font-medium ${rule.price_adjustment >= 0 ? 'text-green-600' : 'text-red-600'}">
                                ${rule.price_adjustment >= 0 ? '+' : ''}${rule.price_adjustment}%
                            </div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${rule.current_occupancy}%</div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${triggeredClass}">
                                    ${triggeredText}
                                </span>
                            </div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                <div>Tổng: ${rule.total_rooms || 0}</div>
                                <div>Đã đặt: ${rule.booked_rooms || 0}</div>
                                <div>Trống: ${rule.available_rooms || 0}</div>
                            </div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td class="p-2 whitespace-nowrap text-center">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                    class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                    onclick="toggleDropdown(${rule.rule_id})"
                                    id="dropdown-button-${rule.rule_id}">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                        </path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="dropdown-menu-${rule.rule_id}"
                                    class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1" role="menu">
                                        <!-- Edit -->
                                        <button onclick="editDynamicRule(${rule.rule_id}); closeDropdown(${rule.rule_id})"
                                            class="flex items-center w-full cursor-pointer px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Chỉnh sửa
                                        </button>

                                        <!-- Toggle Status -->
                                        <button onclick="toggleDynamicRuleStatus(${rule.rule_id}); closeDropdown(${rule.rule_id})"
                                            class="flex items-center w-full cursor-pointer px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            ${rule.is_active ? 'Tạm dừng' : 'Kích hoạt'}
                                        </button>

                                        <!-- Divider -->
                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                        <!-- Delete -->
                                        <button onclick="deleteDynamicRule(${rule.rule_id}); closeDropdown(${rule.rule_id})"
                                            class="flex cursor-pointer items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
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

            elements.tableBody.innerHTML = rows;

            // Update pagination
            updatePagination(data);
        }

        // Update pagination
        function updatePagination(data) {
            if (data.last_page <= 1) {
                elements.paginationContainer.innerHTML = '';
                return;
            }

            let paginationHtml = '<div class="flex items-center justify-between">';

            // Info
            paginationHtml += `
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Hiển thị <span class="font-medium">${data.from}</span> đến <span class="font-medium">${data.to}</span> 
                    trong tổng số <span class="font-medium">${data.total}</span> kết quả
                </div>
            `;

            // Pagination buttons
            paginationHtml += '<div class="flex space-x-1">';

            // Previous button
            if (data.current_page > 1) {
                paginationHtml += `
                    <button onclick="loadData(${data.current_page - 1})" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Trước
                    </button>
                `;
            }

            // Page numbers
            const startPage = Math.max(1, data.current_page - 2);
            const endPage = Math.min(data.last_page, data.current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === data.current_page;
                const buttonClass = isActive ?
                    'px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 rounded-md' :
                    'px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50';

                paginationHtml += `
                    <button onclick="loadData(${i})" class="${buttonClass}">
                        ${i}
                    </button>
                `;
            }

            // Next button
            if (data.current_page < data.last_page) {
                paginationHtml += `
                    <button onclick="loadData(${data.current_page + 1})" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Sau
                    </button>
                `;
            }

            paginationHtml += '</div></div>';

            elements.paginationContainer.innerHTML = paginationHtml;
        }

        // Show/hide states
        function showLoading() {
            elements.loadingState.classList.remove('hidden');
            elements.emptyState.classList.add('hidden');
            elements.tableContent.classList.add('hidden');
        }

        function showEmptyState() {
            elements.loadingState.classList.add('hidden');
            elements.emptyState.classList.remove('hidden');
            elements.tableContent.classList.add('hidden');
        }

        function showTableContent() {
            elements.loadingState.classList.add('hidden');
            elements.emptyState.classList.add('hidden');
            elements.tableContent.classList.remove('hidden');
        }

        // Sync occupancy data
        async function syncOccupancyData() {
            const btn = elements.syncOccupancyBtn;
            const originalText = btn.innerHTML;
            
            // Show loading
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                            <span class="max-xs:sr-only">Đang đồng bộ...</span>
            `;

            try {
                // Call sync API endpoint (you'll need to create this)
                const response = await fetch('{{ route("admin.dynamic-pricing.sync-occupancy") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess(result.message || 'Đồng bộ dữ liệu thành công!');
                    // Reload occupancy stats and table data
                    loadOccupancyStats();
                    loadData(currentPage);
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi đồng bộ dữ liệu');
                }
            } catch (error) {
                console.error('Error syncing occupancy data:', error);
                showError('Có lỗi xảy ra khi đồng bộ dữ liệu');
            } finally {
                // Restore button
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        // Show dynamic rule modal
        function showDynamicRuleModal(id = null) {
            // Reset form
            elements.dynamicRuleForm.reset();
            document.getElementById('dynamicRuleId').value = '';

            if (id) {
                // Edit mode
                isDynamicRuleEditMode = true;
                editingDynamicRuleId = id;
                document.getElementById('dynamicRuleModalTitle').textContent = 'Chỉnh sửa quy tắc giá động';
                document.getElementById('dynamicRuleSubmitText').textContent = 'Cập nhật';
                loadDynamicRuleData(id);
            } else {
                // Add mode
                isDynamicRuleEditMode = false;
                editingDynamicRuleId = null;
                document.getElementById('dynamicRuleModalTitle').textContent = 'Thêm quy tắc giá động';
                document.getElementById('dynamicRuleSubmitText').textContent = 'Thêm mới';
            }

            elements.dynamicRuleModal.classList.remove('hidden');
        }

        // Close dynamic rule modal
        function closeDynamicRuleModal() {
            elements.dynamicRuleModal.classList.add('hidden');
            elements.dynamicRuleForm.reset();
        }

        // Load dynamic rule data for editing
        async function loadDynamicRuleData(id) {
            try {
                const response = await fetch(`{{ route("admin.dynamic-pricing.show", ":id") }}`.replace(':id', id));
                const result = await response.json();

                if (response.ok && result.success) {
                    const rule = result.data;

                    // Fill form
                    document.getElementById('dynamicRuleId').value = rule.rule_id;
                    document.getElementById('roomTypeId').value = rule.room_type_id || '';
                    document.getElementById('occupancyThreshold').value = rule.occupancy_threshold;
                    document.getElementById('priceAdjustment').value = rule.price_adjustment;
                    document.getElementById('isActive').checked = rule.is_active;
                } else {
                    showError('Không thể tải dữ liệu: ' + (result.message || 'Lỗi không xác định'));
                }
            } catch (error) {
                console.error('Error loading dynamic rule data:', error);
                showError('Có lỗi xảy ra khi tải dữ liệu');
            }
        }

        // Handle dynamic rule form submit
        async function handleDynamicRuleSubmit(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('dynamicRuleSubmitBtn');
            const submitText = document.getElementById('dynamicRuleSubmitText');
            const submitLoading = document.getElementById('dynamicRuleSubmitLoading');

            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');

            try {
                const formData = new FormData(elements.dynamicRuleForm);

                // Build request data
                const requestData = {
                    room_type_id: formData.get('room_type_id'),
                    occupancy_threshold: parseFloat(formData.get('occupancy_threshold')),
                    price_adjustment: parseFloat(formData.get('price_adjustment')),
                    is_active: document.getElementById('isActive').checked
                };

                const url = isDynamicRuleEditMode 
                    ? `{{ route("admin.dynamic-pricing.update", ":id") }}`.replace(':id', editingDynamicRuleId)
                    : '{{ route("admin.dynamic-pricing.store") }}';
                
                const method = isDynamicRuleEditMode ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    closeDynamicRuleModal();
                    showSuccess(result.message || 'Thao tác thành công!');
                    loadData(currentPage);
                    loadOccupancyStats();
                } else {
                    showError(result.message || 'Có lỗi xảy ra');

                    // Show validation errors
                    if (result.errors) {
                        Object.keys(result.errors).forEach(field => {
                            console.error(`${field}: ${result.errors[field].join(', ')}`);
                        });
                    }
                }
            } catch (error) {
                console.error('Error submitting dynamic rule form:', error);
                showError('Có lỗi xảy ra khi gửi dữ liệu: ' + error.message);
            } finally {
                // Hide loading
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
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

        // Edit dynamic rule
        function editDynamicRule(id) {
            showDynamicRuleModal(id);
        }

        // Toggle dynamic rule status
        async function toggleDynamicRuleStatus(id) {
            try {
                const response = await fetch(`{{ route("admin.dynamic-pricing.toggle-status", ":id") }}`.replace(':id', id), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess(result.message || 'Cập nhật trạng thái thành công!');
                    loadData(currentPage);
                    loadOccupancyStats();
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi cập nhật trạng thái');
                }
            } catch (error) {
                console.error('Error toggling dynamic rule status:', error);
                showError('Có lỗi xảy ra khi cập nhật trạng thái');
            }
        }

        // Delete dynamic rule
        async function deleteDynamicRule(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa quy tắc giá động này?')) {
                return;
            }

            try {
                const response = await fetch(`{{ route("admin.dynamic-pricing.destroy", ":id") }}`.replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess(result.message || 'Xóa thành công!');
                    loadData(currentPage);
                    loadOccupancyStats();
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi xóa');
                }
            } catch (error) {
                console.error('Error deleting dynamic rule:', error);
                showError('Có lỗi xảy ra khi xóa dữ liệu');
            }
        }

        // Show success notification
        function showSuccess(message) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.dynamic-notification');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'dynamic-notification fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md z-50';
            notification.innerHTML = `
                <div class="flex items-center justify-center w-8 h-8 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-green-700">Thành công!</h3>
                    <div class="text-sm text-green-600">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;

            document.body.appendChild(notification);

            // Auto close after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Show error notification
        function showError(message) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.dynamic-notification');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'dynamic-notification fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md z-50';
            notification.innerHTML = `
                <div class="flex items-center justify-center w-8 h-8 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;

            document.body.appendChild(notification);

            // Auto close after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Close static notification (from session)
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                                closeDynamicRuleModal();
            }
        });
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



