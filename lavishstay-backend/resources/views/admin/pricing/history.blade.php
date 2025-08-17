<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Lịch sử Giá Phòng</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Theo dõi lịch sử thay đổi giá phòng và các quy tắc đã áp dụng</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Filter button -->
                <button id="filterBtn"
                    class="btn cursor-pointer bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M9 15H7a1 1 0 010-2h2a1 1 0 010 2zM11 11H5a1 1 0 010-2h6a1 1 0 010 2zM13 7H3a1 1 0 010-2h10a1 1 0 010 2zM15 3H1a1 1 0 010-2h14a1 1 0 010 2z" />
                    </svg>
                    <span class="max-xs:sr-only">Lọc</span>
                </button>

                <!-- Export button -->
                <button id="exportBtn"
                    class="btn cursor-pointer bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L3 5h3v6h4V5h3L8 0zM1 14h14v2H1v-2z" />
                    </svg>
                    <span class="max-xs:sr-only">Xuất Excel</span>
                </button>

                <a href="{{ route('admin.pricing.index') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M7.3 8.7c-.4-.4-.4-1 0-1.4l7-7c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4L9.4 8l6.3 6.3c.4.4.4 1 0 1.4-.4.4-1 .4-1.4 0l-7-7z"/>
                    </svg>
                    <span class="max-xs:sr-only">Quay lại</span>
                </a>
                <!-- Refresh button -->
                <button id="refreshBtn" class="btn cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 16a7.928 7.928 0 01-3.428-.77l-.194-.09-.866 1.849.194.09A9.928 9.928 0 008 18a10 10 0 000-20 9.928 9.928 0 00-4.294.939l-.194.09.866 1.849.194-.09A7.928 7.928 0 018 0a8 8 0 110 16z"/>
                        <path d="M6.597 11.85l.708-.707L5.011 8.85H10v-1H5.011l2.294-2.293-.708-.707L3.304 8.143a.5.5 0 000 .707l3.293 3.293z"/>
                    </svg>
                    <span class="max-xs:sr-only">Làm mới</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div id="statisticsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Loading skeleton -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 animate-pulse">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 animate-pulse">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 animate-pulse">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 animate-pulse">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
            </div>
        </div>

        <!-- Price Trend Chart -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Xu hướng giá phòng</h3>
                <div class="flex space-x-2">
                    <select id="chartRoomType" class="form-select text-sm">
                        <option value="">Tất cả loại phòng</option>
                    </select>
                    <select id="chartPeriod" class="form-select text-sm">
                        <option value="7">7 ngày qua</option>
                        <option value="30" selected>30 ngày qua</option>
                        <option value="90">90 ngày qua</option>
                    </select>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="priceChartCanvas"></canvas>
                <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg hidden">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Đang tải biểu đồ...</p>
                    </div>
                </div>
                <div id="chartError" class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg hidden">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Không thể tải biểu đồ</p>
                        <button onclick="updatePriceChart()" class="mt-2 text-sm text-indigo-600 hover:text-indigo-500">Thử lại</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div id="filtersContainer" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="filterRoomType">Loại phòng</label>
                    <select id="filterRoomType" class="form-select w-full">
                        <option value="">Tất cả</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="filterStartDate">Từ ngày</label>
                    <input id="filterStartDate" type="date" class="form-input w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="filterEndDate">Đến ngày</label>
                    <input id="filterEndDate" type="date" class="form-input w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="filterPriceRange">Khoảng giá</label>
                    <select id="filterPriceRange" class="form-select w-full">
                        <option value="">Tất cả</option>
                        <option value="0-500000">Dưới 500K</option>
                        <option value="500000-1000000">500K - 1M</option>
                        <option value="1000000-2000000">1M - 2M</option>
                        <option value="2000000-5000000">2M - 5M</option>
                        <option value="5000000-">Trên 5M</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="filterRuleType">Loại quy tắc</label>
                    <select id="filterRuleType" class="form-select w-full">
                        <option value="">Tất cả</option>
                        <option value="weekend">Cuối tuần</option>
                        <option value="event">Sự kiện</option>
                        <option value="holiday">Lễ hội</option>
                        <option value="season">Mùa</option>
                        <option value="dynamic">Giá động</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end mt-4 space-x-2">
                <button id="clearFiltersBtn"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
                    Xóa bộ lọc
                </button>
                <button id="applyFiltersBtn" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    Áp dụng
                </button>
            </div>
        </div>

        <!-- Data Table -->
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có dữ liệu</h3>
                        <p class="mt-1 text-sm text-gray-500">Lịch sử giá phòng sẽ được hiển thị khi có thay đổi giá.</p>
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
                                            <div class="font-semibold text-left">Tùy chọn</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Ngày áp dụng</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Giá gốc</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Giá điều chỉnh</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Thay đổi</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Quy tắc áp dụng</div>
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

    <!-- Price History Detail Modal -->
    <div id="priceHistoryModal" class="fixed modal-overlay inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Chi tiết lịch sử giá</h3>
                        <button onclick="closePriceHistoryModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div id="priceHistoryContent">
                        <!-- Dynamic content -->
                    </div>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

    <!-- Chart.js v3 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
        // Global variables
        let currentPage = 1;
        let currentFilters = {};
        let isLoading = false;
        let priceChart = null;
        let chartUpdateTimeout = null;

        // DOM elements
        const elements = {
            filterBtn: document.getElementById('filterBtn'),
            exportBtn: document.getElementById('exportBtn'),
            refreshBtn: document.getElementById('refreshBtn'),
            filtersContainer: document.getElementById('filtersContainer'),
            applyFiltersBtn: document.getElementById('applyFiltersBtn'),
            clearFiltersBtn: document.getElementById('clearFiltersBtn'),
            priceHistoryModal: document.getElementById('priceHistoryModal'),
            loadingState: document.getElementById('loadingState'),
            emptyState: document.getElementById('emptyState'),
            tableContent: document.getElementById('tableContent'),
            tableBody: document.getElementById('tableBody'),
            paginationContainer: document.getElementById('paginationContainer'),
            statisticsContainer: document.getElementById('statisticsContainer'),
            chartRoomType: document.getElementById('chartRoomType'),
            chartPeriod: document.getElementById('chartPeriod'),
            priceChartCanvas: document.getElementById('priceChartCanvas'),
            chartLoading: document.getElementById('chartLoading'),
            chartError: document.getElementById('chartError')
        };

        // Utility functions
        function formatCurrency(amount) {
            if (!amount && amount !== 0) return '0₫';
            return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                return new Date(dateString).toLocaleDateString('vi-VN');
            } catch (e) {
                return dateString;
            }
        }

        function formatDateTime(dateString) {
            if (!dateString) return 'N/A';
            try {
                return new Date(dateString).toLocaleString('vi-VN');
            } catch (e) {
                return dateString;
            }
        }

        function showChartLoading() {
            elements.chartLoading?.classList.remove('hidden');
            elements.chartError?.classList.add('hidden');
        }

        function hideChartLoading() {
            elements.chartLoading?.classList.add('hidden');
        }

        function showChartError() {
            elements.chartLoading?.classList.add('hidden');
            elements.chartError?.classList.remove('hidden');
        }

        function hideChartError() {
            elements.chartError?.classList.add('hidden');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initializing Price History Page...');
            
            // Initialize components
            loadStatistics();
            loadRoomTypes();
            loadData();
            initializePriceChart();
            bindEvents();

            // Auto close notification
            if (document.getElementById('notification')) {
                setTimeout(() => {
                    closeNotification();
                }, 5000);
            }

            console.log('✅ Price History Page initialized');
        });

        // Bind events
        function bindEvents() {
            // Filter toggle
            elements.filterBtn?.addEventListener('click', toggleFilters);

            // Export
            elements.exportBtn?.addEventListener('click', exportData);

            // Refresh
            elements.refreshBtn?.addEventListener('click', () => {
                console.log('🔄 Refreshing data...');
                loadData();
                loadStatistics();
                updatePriceChart();
            });

            // Filters
            elements.applyFiltersBtn?.addEventListener('click', applyFilters);
            elements.clearFiltersBtn?.addEventListener('click', clearFilters);

            // Chart controls with debounce
            elements.chartRoomType?.addEventListener('change', () => {
                clearTimeout(chartUpdateTimeout);
                chartUpdateTimeout = setTimeout(updatePriceChart, 300);
            });
            
            elements.chartPeriod?.addEventListener('change', () => {
                clearTimeout(chartUpdateTimeout);
                chartUpdateTimeout = setTimeout(updatePriceChart, 300);
            });

            // Close modal on outside click
            elements.priceHistoryModal?.addEventListener('click', function(e) {
                if (e.target === elements.priceHistoryModal) {
                    closePriceHistoryModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePriceHistoryModal();
                }
            });
        }

        // Load statistics
        async function loadStatistics() {
            try {
                console.log('📊 Loading statistics...');
                const response = await fetch('{{ route('admin.pricing.history.statistics') }}', {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('✅ Statistics loaded:', data);
                updateStatistics(data);
            } catch (error) {
                console.error('❌ Error loading statistics:', error);
                // Show default stats on error
                updateStatistics({ total_records: 0, average_price: 0, average_increase: 0, most_common_rule: 'N/A' });
            }
        }

        // Load room types - FIX: Use correct route
        async function loadRoomTypes() {
            try {
                console.log('🏠 Loading room types...');
                // Try the pricing history route first, fallback to weekend-price route
                let response;
                try {
                    response = await fetch('{{ route('admin.pricing.history.room-types') }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                } catch (e) {
                    console.log('⚠️ Fallback to weekend-price route');
                    response = await fetch('{{ route("admin.weekend-price.room-types") }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                }
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const roomTypes = await response.json();
                console.log('✅ Room types loaded:', roomTypes);
                
                // Update filter dropdown
                const filterSelect = document.getElementById('filterRoomType');
                if (filterSelect) {
                    filterSelect.innerHTML = '<option value="">Tất cả</option>';
                    roomTypes.forEach(roomType => {
                        const option = document.createElement('option');
                        option.value = roomType.room_type_id;
                        option.textContent = roomType.name;
                        filterSelect.appendChild(option);
                    });
                }
                
                // Update chart dropdown
                if (elements.chartRoomType) {
                    elements.chartRoomType.innerHTML = '<option value="">Tất cả loại phòng</option>';
                    roomTypes.forEach(roomType => {
                        const option = document.createElement('option');
                        option.value = roomType.room_type_id;
                        option.textContent = roomType.name;
                        elements.chartRoomType.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('❌ Error loading room types:', error);
            }
        }

        // Update statistics display
        function updateStatistics(stats) {
            const statisticsHtml = `
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng lịch sử</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.total_records || 0}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Giá trung bình</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${formatCurrency(stats.average_price || 0)}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tăng giá trung bình</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.average_increase || 0}%</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Quy tắc phổ biến</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.most_common_rule || 'N/A'}</div>
                        </div>
                    </div>
                </div>
            `;

            elements.statisticsContainer.innerHTML = statisticsHtml;
        }

        // Toggle filters
        function toggleFilters() {
            elements.filtersContainer?.classList.toggle('hidden');
        }

        // Load data
        async function loadData(page = 1) {
            if (isLoading) return;

            isLoading = true;
            showLoading();

            try {
                console.log(`📋 Loading data for page ${page}...`);
                const params = new URLSearchParams({
                    page: page,
                    ...currentFilters
                });

                const response = await fetch(`{{ route('admin.pricing.history.data') }}?${params}`, {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('✅ Data loaded:', data);
                
                currentPage = page;
                displayData(data);
            } catch (error) {
                console.error('❌ Error loading data:', error);
                showError('Có lỗi xảy ra khi tải dữ liệu: ' + error.message);
                showEmptyState();
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
            const rows = data.data.map(history => {
                const priceChange = (history.adjusted_price || 0) - (history.base_price || 0);
                const priceChangePercent = history.base_price > 0 ? ((priceChange / history.base_price) * 100).toFixed(2) : 0;
                const priceChangeClass = priceChange >= 0 ? 'text-green-600' : 'text-red-600';
                
                let appliedRules = [];
                try {
                    appliedRules = JSON.parse(history.applied_rules || '[]');
                } catch (e) {
                    console.warn('Invalid applied_rules JSON:', history.applied_rules);
                }
                
                const rulesDisplay = appliedRules.length > 0 
                    ? appliedRules.map(rule => `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">${rule.type || 'Unknown'}</span>`).join('')
                    : '<span class="text-gray-500">Không có</span>';

                return `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="p-2 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${history.history_id || history.id || '-'}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">${history.room_type_name || 'N/A'}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">${history.option_name || 'N/A'}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">${formatDate(history.applied_date || history.date)}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${formatCurrency(history.base_price)}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${formatCurrency(history.adjusted_price)}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm font-medium ${priceChangeClass}">
                                ${priceChange >= 0 ? '+' : ''}${formatCurrency(priceChange)}
                                <div class="text-xs">(${priceChangePercent >= 0 ? '+' : ''}${priceChangePercent}%)</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex flex-wrap">${rulesDisplay}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap text-center">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                    class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                    onclick="toggleDropdown('${history.history_id || history.id}')"
                                    id="dropdown-button-${history.history_id || history.id}">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="dropdown-menu-${history.history_id || history.id}"
                                    class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1" role="menu">
                                        <!-- View Details -->
                                        <button onclick="viewPriceHistoryDetails('${history.history_id || history.id}'); closeDropdown('${history.history_id || history.id}')"
                                            class="flex items-center w-full cursor-pointer px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Xem chi tiết
                                        </button>

                                        <!-- Export Single -->
                                        <button onclick="exportSingleHistory('${history.history_id || history.id}'); closeDropdown('${history.history_id || history.id}')"
                                            class="flex items-center w-full cursor-pointer px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Xuất báo cáo
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
            if (!data.last_page || data.last_page <= 1) {
                elements.paginationContainer.innerHTML = '';
                return;
            }

            let paginationHtml = '<div class="flex items-center justify-between">';

            // Info
            paginationHtml += `
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Hiển thị <span class="font-medium">${data.from || 0}</span> đến <span class="font-medium">${data.to || 0}</span> 
                    trong tổng số <span class="font-medium">${data.total || 0}</span> kết quả
                </div>
            `;

            // Pagination buttons
            paginationHtml += '<div class="flex space-x-1">';

            // Previous button
            if (data.current_page > 1) {
                paginationHtml += `
                    <button onclick="loadData(${data.current_page - 1})" class="px-3 py-2 text-sm font-medium cursor-pointer text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
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
                    <button onclick="loadData(${i})" class=" cursor-pointer ${buttonClass}">
                        ${i}
                    </button>
                `;
            }

            // Next button
            if (data.current_page < data.last_page) {
                paginationHtml += `
                    <button onclick="loadData(${data.current_page + 1})" class="px-3 py-2 cursor-pointer text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Sau
                    </button>
                `;
            }

            paginationHtml += '</div></div>';

            elements.paginationContainer.innerHTML = paginationHtml;
        }

        // Show/hide states
        function showLoading() {
            elements.loadingState?.classList.remove('hidden');
            elements.emptyState?.classList.add('hidden');
            elements.tableContent?.classList.add('hidden');
        }

        function showEmptyState() {
            elements.loadingState?.classList.add('hidden');
            elements.emptyState?.classList.remove('hidden');
            elements.tableContent?.classList.add('hidden');
        }

        function showTableContent() {
            elements.loadingState?.classList.add('hidden');
            elements.emptyState?.classList.add('hidden');
            elements.tableContent?.classList.remove('hidden');
        }

        // Apply filters
        function applyFilters() {
            currentFilters = {
                room_type_id: document.getElementById('filterRoomType')?.value || '',
                start_date: document.getElementById('filterStartDate')?.value || '',
                end_date: document.getElementById('filterEndDate')?.value || '',
                price_range: document.getElementById('filterPriceRange')?.value || '',
                rule_type: document.getElementById('filterRuleType')?.value || ''
            };

            // Remove empty filters
            Object.keys(currentFilters).forEach(key => {
                if (!currentFilters[key]) {
                    delete currentFilters[key];
                }
            });

            console.log('🔍 Applying filters:', currentFilters);
            loadData(1);
        }

        // Clear filters
        function clearFilters() {
            document.getElementById('filterRoomType').value = '';
            document.getElementById('filterStartDate').value = '';
            document.getElementById('filterEndDate').value = '';
            document.getElementById('filterPriceRange').value = '';
            document.getElementById('filterRuleType').value = '';
            currentFilters = {};
            console.log('🧹 Filters cleared');
            loadData(1);
        }

        // Initialize price chart - FIXED for Chart.js v3
        function initializePriceChart() {
            if (!elements.priceChartCanvas) {
                console.error('❌ Chart canvas not found');
                return;
            }

            console.log('📈 Initializing price chart...');
            
            try {
                const ctx = elements.priceChartCanvas.getContext('2d');
                
                priceChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Giá trung bình (VNĐ)',
                            data: [],
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4, // Chart.js v3 syntax
                            pointBackgroundColor: 'rgb(99, 102, 241)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { // Chart.js v3 syntax
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgb(99, 102, 241)',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return 'Giá: ' + formatCurrency(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: { // Chart.js v3 syntax
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('vi-VN', {
                                            notation: 'compact',
                                            compactDisplay: 'short'
                                        }).format(value) + '₫';
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });

                console.log('✅ Price chart initialized');
                updatePriceChart();
            } catch (error) {
                console.error('❌ Error initializing chart:', error);
                showChartError();
            }
        }

        // Update price chart - FIXED with better error handling
        async function updatePriceChart() {
            if (!priceChart) {
                console.warn('⚠️ Chart not initialized, skipping update');
                return;
            }

            showChartLoading();
            hideChartError();

            try {
                console.log('📈 Updating price chart...');
                
                const params = new URLSearchParams({
                    room_type_id: elements.chartRoomType?.value || '',
                    period: elements.chartPeriod?.value || '30'
                });

                const response = await fetch(`{{ route('admin.pricing.history.chart') }}?${params}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                console.log('✅ Chart data received:', data);

                // Validate data structure
                if (!data || (!Array.isArray(data.labels) && !Array.isArray(data.data))) {
                    throw new Error('Invalid chart data structure');
                }

                // Update chart data
                priceChart.data.labels = data.labels || [];
                priceChart.data.datasets[0].data = (data.data || []).map(value => value === null ? null : Number(value));
                
                priceChart.update();
                hideChartLoading();
                
                console.log('✅ Chart updated successfully');
            } catch (error) {
                console.error('❌ Error updating chart:', error);
                hideChartLoading();
                showChartError();
                
                // Clear chart data on error
                if (priceChart) {
                    priceChart.data.labels = [];
                    priceChart.data.datasets[0].data = [];
                    priceChart.update();
                }
            }
        }

        // Dropdown functions
        window.toggleDropdown = function(id) {
            const dropdown = document.getElementById(`dropdown-menu-${id}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${id}`) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown?.classList.toggle('hidden');
        };

        window.closeDropdown = function(id) {
            const dropdown = document.getElementById(`dropdown-menu-${id}`);
            dropdown?.classList.add('hidden');
        };

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

        // View price history details
        window.viewPriceHistoryDetails = async function(id) {
            try {
                console.log(`👁️ Loading details for history ID: ${id}`);
                
                const response = await fetch(`{{ route('admin.pricing.history.show', ':id') }}`.replace(':id', id), {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Failed to load details');
                }

                const history = result.data;
                let appliedRules = [];
                
                try {
                    appliedRules = JSON.parse(history.applied_rules || '[]');
                } catch (e) {
                    console.warn('Invalid applied_rules JSON:', history.applied_rules);
                }
                
                const priceChange = (history.adjusted_price || 0) - (history.base_price || 0);
                const priceChangePercent = history.base_price > 0 ? ((priceChange / history.base_price) * 100).toFixed(2) : 0;
                const priceChangeClass = priceChange >= 0 ? 'text-green-600' : 'text-red-600';

                const detailsHtml = `
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin cơ bản</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Lịch sử</label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">${history.history_id || history.id || 'N/A'}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Loại phòng</label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">${history.room_type_name || 'N/A'}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tùy chọn phòng</label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">${history.option_name || 'N/A'}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ngày áp dụng</label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">${formatDate(history.applied_date || history.date)}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin giá</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giá gốc</label>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">${formatCurrency(history.base_price)}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giá điều chỉnh</label>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">${formatCurrency(history.adjusted_price)}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thay đổi</label>
                                    <div class="text-lg font-semibold ${priceChangeClass}">
                                        ${priceChange >= 0 ? '+' : ''}${formatCurrency(priceChange)}
                                        <div class="text-sm">(${priceChangePercent >= 0 ? '+' : ''}${priceChangePercent}%)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Applied Rules -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Quy tắc đã áp dụng</h4>
                            ${appliedRules.length > 0 ? `
                                <div class="space-y-3">
                                    ${appliedRules.map(rule => `
                                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-600 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    ${rule.type || 'Unknown'}
                                                </span>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-gray-100">${rule.name || 'N/A'}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">ID: ${rule.rule_id || 'N/A'}</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-medium ${(rule.adjustment || 0) >= 0 ? 'text-green-600' : 'text-red-600'}">
                                                    ${(rule.adjustment || 0) >= 0 ? '+' : ''}${rule.adjustment || 0}%
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Ưu tiên: ${rule.priority || 'N/A'}
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            ` : `
                                <div class="text-center py-4">
                                    <div class="text-gray-500 dark:text-gray-400">Không có quy tắc nào được áp dụng</div>
                                </div>
                            `}
                        </div>

                        <!-- Additional Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin bổ sung</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tỷ lệ lấp đầy</label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">${history.occupancy_rate || 0}%</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cơ chế tính giá</label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">${history.pricing_mechanism || 'Cộng dồn'}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ngày tạo</label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">${formatDateTime(history.created_at)}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ghi chú</label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">${history.notes || 'Không có'}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById('priceHistoryContent').innerHTML = detailsHtml;
                elements.priceHistoryModal?.classList.remove('hidden');
                
                console.log('✅ Details modal opened');
            } catch (error) {
                console.error('❌ Error loading price history details:', error);
                showError('Không thể tải chi tiết: ' + error.message);
            }
        };

        // Close price history modal
        function closePriceHistoryModal() {
            elements.priceHistoryModal?.classList.add('hidden');
        }

        // Export functions
        window.exportSingleHistory = async function(id) {
            try {
                console.log(`📤 Exporting single history: ${id}`);
                window.open(`{{ route('admin.pricing.history.export-single', ':id') }}`.replace(':id', id), '_blank');
            } catch (error) {
                console.error('❌ Error exporting single history:', error);
                showError('Có lỗi xảy ra khi xuất báo cáo');
            }
        };

        function exportData() {
            try {
                console.log('📤 Exporting all data with filters:', currentFilters);
                const params = new URLSearchParams(currentFilters);
                window.open(`{{ route('admin.pricing.history.export') }}?${params}`, '_blank');
            } catch (error) {
                console.error('❌ Error exporting data:', error);
                showError('Có lỗi xảy ra khi xuất dữ liệu');
            }
        }

        // Notification functions
        function showSuccess(message) {
            showNotification(message, 'success');
        }

        function showError(message) {
            showNotification(message, 'error');
        }

        function showNotification(message, type = 'success') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.dynamic-notification');
            existingNotifications.forEach(notification => notification.remove());

            const isSuccess = type === 'success';
            const bgColor = isSuccess ? 'from-green-50 to-green-100' : 'from-red-50 to-red-100';
            const borderColor = isSuccess ? 'border-green-500' : 'border-red-500';
            const textColor = isSuccess ? 'text-green-600' : 'text-red-600';
            const iconColor = isSuccess ? 'text-green-500' : 'text-red-500';
            const title = isSuccess ? 'Thành công!' : 'Lỗi!';
            const icon = isSuccess 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `dynamic-notification fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r ${bgColor} border-l-4 ${borderColor} shadow-md z-50`;
            notification.innerHTML = `
                <div class="flex items-center justify-center w-8 h-8 ${iconColor}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${icon}
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold ${textColor.replace('600', '700')}">${title}</h3>
                    <div class="text-sm ${textColor}">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="absolute right-2 top-2 ${textColor} hover:${textColor.replace('600', '800')}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;

            document.body.appendChild(notification);

            // Auto close after appropriate time
            const autoCloseTime = isSuccess ? 3000 : 5000;
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, autoCloseTime);
        }

        // Show success notification
        function showSuccess(message) {
            showNotification(message, 'success');
        }

        // Show error notification
        function showError(message) {
            showNotification(message, 'error');
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

        // Initialize price chart - FIXED for Chart.js v3
        function initializePriceChart() {
            if (!elements.priceChartCanvas) {
                console.error('Chart canvas not found');
                return;
            }

            console.log('Initializing price chart...');
            
            try {
                const ctx = elements.priceChartCanvas.getContext('2d');
                
                priceChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Giá trung bình (VNĐ)',
                            data: [],
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4, // Chart.js v3 syntax
                            pointBackgroundColor: 'rgb(99, 102, 241)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { // Chart.js v3 syntax
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgb(99, 102, 241)',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return 'Giá: ' + formatCurrency(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: { // Chart.js v3 syntax
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('vi-VN', {
                                            notation: 'compact',
                                            compactDisplay: 'short'
                                        }).format(value) + '₫';
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });

                console.log('Price chart initialized successfully');
                updatePriceChart();
            } catch (error) {
                console.error('Error initializing chart:', error);
                showError('Không thể khởi tạo biểu đồ');
            }
        }

        // Update price chart - FIXED with better error handling
        async function updatePriceChart() {
            if (!priceChart) {
                console.warn('Chart not initialized, skipping update');
                return;
            }

            try {
                console.log('Updating price chart...');
                
                const params = new URLSearchParams({
                    room_type_id: elements.chartRoomType?.value || '',
                    period: elements.chartPeriod?.value || '30'
                });

                const response = await fetch(`{{ route('admin.pricing.history.chart') }}?${params}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                console.log('Chart data received:', data);

                // Validate data structure
                if (!data || !Array.isArray(data.labels) || !Array.isArray(data.data)) {
                    throw new Error('Invalid chart data structure');
                }

                // Update chart data
                priceChart.data.labels = data.labels;
                priceChart.data.datasets[0].data = data.data.map(value => value === null ? null : Number(value));
                
                priceChart.update();
                
                console.log('Chart updated successfully');
            } catch (error) {
                console.error('Error updating chart:', error);
                
                // Clear chart data on error
                if (priceChart) {
                    priceChart.data.labels = [];
                    priceChart.data.datasets[0].data = [];
                    priceChart.update();
                }
                
                showError('Không thể cập nhật biểu đồ: ' + error.message);
            }
        }

        // Fixed formatCurrency function
        function formatCurrency(amount) {
            if (!amount && amount !== 0) return '0₫';
            return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
        }

        // Dark mode chart updates
        function updateChartsForTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            
            if (priceChart && Chart.defaults) {
                Chart.defaults.color = isDark ? '#9CA3AF' : '#6B7280';
                priceChart.update();
            }
        }

        // Listen for theme changes
        const themeObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    updateChartsForTheme();
                }
            });
        });

        themeObserver.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Handle window resize for chart
        window.addEventListener('resize', function() {
            if (priceChart) {
                priceChart.resize();
            }
        });

        // Auto refresh data every 5 minutes
        setInterval(() => {
            if (!document.hidden) { // Only refresh if page is visible
                loadData(currentPage);
                loadStatistics();
                updatePriceChart();
            }
        }, 300000); // 5 minutes

        // Handle page visibility change
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Page became visible, refresh data
                loadData(currentPage);
                loadStatistics();
                updatePriceChart();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Escape key - close modals
            if (e.key === 'Escape') {
                closePriceHistoryModal();
                
                // Close all dropdowns
                const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
            
            // Ctrl/Cmd + R - refresh data
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                loadData(currentPage);
                loadStatistics();
                updatePriceChart();
                showSuccess('Dữ liệu đã được làm mới');
            }
            
            // Ctrl/Cmd + F - focus on filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                if (elements.filtersContainer.classList.contains('hidden')) {
                    toggleFilters();
                }
                document.getElementById('filterRoomType')?.focus();
            }
        });

        // Add loading states to buttons
        function setButtonLoading(button, loading = true) {
            if (!button) return;
            
            if (loading) {
                button.disabled = true;
                button.classList.add('btn-loading');
                button.setAttribute('data-original-text', button.textContent);
                button.textContent = 'Đang xử lý...';
            } else {
                button.disabled = false;
                button.classList.remove('btn-loading');
                const originalText = button.getAttribute('data-original-text');
                if (originalText) {
                    button.textContent = originalText;
                    button.removeAttribute('data-original-text');
                }
            }
        }

        // Enhanced error handling for fetch requests
        async function fetchWithErrorHandling(url, options = {}) {
            try {
                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' },
                    ...options
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
                }

                return await response.json();
            } catch (error) {
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    throw new Error('Không thể kết nối đến server. Vui lòng kiểm tra kết nối mạng.');
                }
                throw error;
            }
        }

        // Add retry mechanism for failed requests
        async function fetchWithRetry(url, options = {}, maxRetries = 3) {
            let lastError;
            
            for (let i = 0; i < maxRetries; i++) {
                try {
                    return await fetchWithErrorHandling(url, options);
                } catch (error) {
                    lastError = error;
                    if (i < maxRetries - 1) {
                        // Wait before retry (exponential backoff)
                        await new Promise(resolve => setTimeout(resolve, Math.pow(2, i) * 1000));
                    }
                }
            }
            
            throw lastError;
        }

        // Performance monitoring
        function measurePerformance(name, fn) {
            return async function(...args) {
                const start = performance.now();
                try {
                    const result = await fn.apply(this, args);
                    const end = performance.now();
                    console.log(`${name} took ${(end - start).toFixed(2)}ms`);
                    return result;
                } catch (error) {
                    const end = performance.now();
                    console.error(`${name} failed after ${(end - start).toFixed(2)}ms:`, error);
                    throw error;
                }
            };
        }

        // Wrap performance-critical functions
        loadData = measurePerformance('loadData', loadData);
        loadStatistics = measurePerformance('loadStatistics', loadStatistics);
        updatePriceChart = measurePerformance('updatePriceChart', updatePriceChart);

        // Add accessibility improvements
        function enhanceAccessibility() {
            // Add ARIA labels to interactive elements
            const buttons = document.querySelectorAll('button:not([aria-label])');
            buttons.forEach(button => {
                const text = button.textContent.trim() || button.getAttribute('title') || 'Button';
                button.setAttribute('aria-label', text);
            });

            // Add keyboard navigation for dropdowns
            document.addEventListener('keydown', function(e) {
                const activeDropdown = document.querySelector('[id^="dropdown-menu-"]:not(.hidden)');
                if (!activeDropdown) return;

                const menuItems = activeDropdown.querySelectorAll('[role="menuitem"]');
                const currentIndex = Array.from(menuItems).findIndex(item => item === document.activeElement);

                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        const nextIndex = currentIndex < menuItems.length - 1 ? currentIndex + 1 : 0;
                        menuItems[nextIndex]?.focus();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        const prevIndex = currentIndex > 0 ? currentIndex - 1 : menuItems.length - 1;
                        menuItems[prevIndex]?.focus();
                        break;
                    case 'Enter':
                    case ' ':
                        e.preventDefault();
                        document.activeElement?.click();
                        break;
                }
            });
        }

        // Initialize accessibility enhancements
        document.addEventListener('DOMContentLoaded', enhanceAccessibility);

        // Add data validation
        function validateFormData(data) {
            const errors = [];

            if (data.start_date && data.end_date) {
                const startDate = new Date(data.start_date);
                const endDate = new Date(data.end_date);
                
                if (startDate > endDate) {
                    errors.push('Ngày bắt đầu không thể sau ngày kết thúc');
                }
                
                const maxRange = 365 * 24 * 60 * 60 * 1000; // 1 year in milliseconds
                if (endDate - startDate > maxRange) {
                    errors.push('Khoảng thời gian không thể vượt quá 1 năm');
                }
            }

            return errors;
        }

        // Enhanced applyFilters with validation
        const originalApplyFilters = applyFilters;
        applyFilters = function() {
            const formData = {
                room_type_id: document.getElementById('filterRoomType')?.value || '',
                start_date: document.getElementById('filterStartDate')?.value || '',
                end_date: document.getElementById('filterEndDate')?.value || '',
                price_range: document.getElementById('filterPriceRange')?.value || '',
                rule_type: document.getElementById('filterRuleType')?.value || ''
            };

            const errors = validateFormData(formData);
            if (errors.length > 0) {
                showError('Lỗi validation: ' + errors.join(', '));
                return;
            }

            originalApplyFilters();
        };

        // Add export progress tracking
        async function trackExportProgress(exportFunction, filename) {
            const progressNotification = document.createElement('div');
            progressNotification.className = 'dynamic-notification fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 shadow-md z-50';
            progressNotification.innerHTML = `
                <div class="flex items-center justify-center w-8 h-8 text-blue-500">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold text-blue-700">Đang xuất dữ liệu...</h3>
                    <div class="text-sm text-blue-600">Vui lòng đợi trong giây lát</div>
                </div>
            `;

            document.body.appendChild(progressNotification);

            try {
                await exportFunction();
                progressNotification.remove();
                showSuccess(`Đã xuất thành công: ${filename}`);
            } catch (error) {
                progressNotification.remove();
                showError('Lỗi khi xuất dữ liệu: ' + error.message);
            }
        }

        // Enhanced export functions
        const originalExportData = exportData;
        exportData = function() {
            return trackExportProgress(originalExportData, 'Lịch sử giá phòng');
        };

        const originalExportSingleHistory = exportSingleHistory;
        exportSingleHistory = function(id) {
            return trackExportProgress(() => originalExportSingleHistory(id), `Lịch sử #${id}`);
        };

        // Add connection status monitoring
        function monitorConnection() {
            let isOnline = navigator.onLine;

            function updateConnectionStatus() {
                const newStatus = navigator.onLine;
                if (newStatus !== isOnline) {
                    isOnline = newStatus;
                    if (isOnline) {
                        showSuccess('Kết nối mạng đã được khôi phục');
                        // Refresh data when connection is restored
                        loadData(currentPage);
                        loadStatistics();
                        updatePriceChart();
                    } else {
                        showError('Mất kết nối mạng. Một số tính năng có thể không hoạt động.');
                    }
                }
            }

            window.addEventListener('online', updateConnectionStatus);
            window.addEventListener('offline', updateConnectionStatus);
        }

        // Initialize connection monitoring
        monitorConnection();

        // Add data caching for better performance
        const cache = new Map();
        const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

        function getCacheKey(url, params) {
            return url + '?' + new URLSearchParams(params).toString();
        }

        
        function getCachedData(key) {
            const cached = cache.get(key);
            if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
                return cached.data;
            }
            return null;
        }

        function setCachedData(key, data) {
            cache.set(key, {
                data: data,
                timestamp: Date.now()
            });
        }

        // Clear old cache entries periodically
        setInterval(() => {
            const now = Date.now();
            for (const [key, value] of cache.entries()) {
                if (now - value.timestamp > CACHE_DURATION) {
                    cache.delete(key);
                }
            }
        }, CACHE_DURATION);

        console.log('✅ Price History page fully initialized with all enhancements');
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

        /* Chart container */
        #priceChart {
            position: relative;
        }

        /* Button loading state */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Table responsive */
        @media (max-width: 768px) {
            .table-auto {
                font-size: 0.875rem;
            }
            
            .table-auto th,
            .table-auto td {
                padding: 0.5rem;
            }

            .overflow-x-auto {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Price change indicators */
        .price-increase {
            color: #059669;
        }

        .price-decrease {
            color: #dc2626;
        }

        /* Statistics cards hover effect */
        .statistics-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .statistics-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Loading skeleton animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Filter panel animation */
        .filter-panel {
            transition: all 0.3s ease-in-out;
            transform-origin: top;
        }

        .filter-panel.hidden {
            transform: scaleY(0);
            opacity: 0;
        }

        .filter-panel:not(.hidden) {
            transform: scaleY(1);
            opacity: 1;
        }

        /* Dropdown menu animation */
        .dropdown-menu {
            transition: all 0.2s ease-in-out;
            transform-origin: top right;
        }

        .dropdown-menu.hidden {
            transform: scale(0.95);
            opacity: 0;
        }

        .dropdown-menu:not(.hidden) {
            transform: scale(1);
            opacity: 1;
        }

        /* Dark mode improvements */
        .dark .bg-gradient-to-r.from-green-50.to-green-100 {
            background: linear-gradient(to right, #064e3b, #065f46);
        }

        .dark .bg-gradient-to-r.from-red-50.to-red-100 {
            background: linear-gradient(to right, #7f1d1d, #991b1b);
        }

        .dark .bg-gradient-to-r.from-blue-50.to-blue-100 {
            background: linear-gradient(to right, #1e3a8a, #1e40af);
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .print-full-width {
                width: 100% !important;
                max-width: none !important;
            }
        }

        /* Accessibility improvements */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Focus indicators */
        button:focus,
        select:focus,
        input:focus {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .bg-gray-50 {
                background-color: #ffffff;
            }
            
            .text-gray-500 {
                color: #000000;
            }
            
            .border-gray-300 {
                border-color: #000000;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .animate-pulse,
            .animate-spin,
            .transition-all,
            .transition-colors,
            .transition-transform {
                animation: none;
                transition: none;
            }
        }
    </style>

</x-app-layout>