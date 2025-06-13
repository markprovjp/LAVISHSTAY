<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Giá theo sự kiện / Lễ hội</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Filter button -->
                <button id="filterBtn" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M9 15H7a1 1 0 010-2h2a1 1 0 010 2zM11 11H5a1 1 0 010-2h6a1 1 0 010 2zM13 7H3a1 1 0 010-2h10a1 1 0 010 2zM15 3H1a1 1 0 010-2h14a1 1 0 010 2z" />
                    </svg>
                    <span class="max-xs:sr-only">Lọc</span>
                </button>

                <!-- Export button -->
                <button id="exportBtn" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L3 5h3v6h4V5h3L8 0zM1 14h14v2H1v-2z"/>
                    </svg>
                    <span class="max-xs:sr-only">Xuất Excel</span>
                </button>

                <!-- Add button -->
                <button id="addBtn" class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm mới</span>
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

        <!-- Filters -->
        <div id="filtersContainer" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="filterType">Loại giá</label>
                    <select id="filterType" class="form-select w-full">
                        <option value="">Tất cả</option>
                        <option value="event">Sự kiện</option>
                        <option value="holiday">Lễ hội</option>
                        <option value="weekend">Cuối tuần</option>
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
            </div>
            <div class="flex justify-end mt-4 space-x-2">
                <button id="clearFiltersBtn" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có dữ liệu</h3>
                        <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách thêm giá mới.</p>
                        <div class="mt-6">
                            <button id="addBtnEmpty" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                                Thêm giá mới
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
                                            <div class="font-semibold text-left">Loại</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Tên</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Phòng</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Thời gian</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Giá (VND)</div>
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

    <!-- Add/Edit Modal -->
    <div id="pricingModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thêm giá mới</h3>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="pricingForm">
                    <input type="hidden" id="pricingId" name="pricing_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" for="pricingType">Loại giá <span class="text-red-500">*</span></label>
                            <select id="pricingType" name="pricing_type" class="form-select w-full" required>
                                <option value="">Chọn loại giá</option>
                                <option value="event">Sự kiện</option>
                                <option value="holiday">Lễ hội</option>
                                <option value="weekend">Cuối tuần</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="roomId">Phòng <span class="text-red-500">*</span></label>
                            <select id="roomId" name="room_id" class="form-select w-full" required>
                                <option value="">Chọn phòng</option>
                            </select>
                        </div>
                    </div>

                    <!-- Event Section -->
                    <div id="eventSection" class="mb-4 hidden">
                        <label class="block text-sm font-medium mb-1" for="eventId">Sự kiện <span class="text-red-500">*</span></label>
                        <select id="eventId" name="event_id" class="form-select w-full">
                            <option value="">Chọn sự kiện</option>
                        </select>
                    </div>

                    <!-- Holiday Section -->
                    <div id="holidaySection" class="mb-4 hidden">
                        <label class="block text-sm font-medium mb-1" for="holidayId">Lễ hội <span class="text-red-500">*</span></label>
                        <select id="holidayId" name="holiday_id" class="form-select w-full">
                                                        <option value="">Chọn lễ hội</option>
                        </select>
                    </div>

                    <!-- Weekend Section -->
                    <div id="weekendSection" class="mb-4 hidden">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Giá sẽ được áp dụng cho tất cả các ngày cuối tuần (Thứ 7 và Chủ nhật) trong khoảng thời gian đã chọn.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" for="startDate">Ngày bắt đầu <span class="text-red-500">*</span></label>
                            <input id="startDate" name="start_date" type="date" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="endDate">Ngày kết thúc <span class="text-red-500">*</span></label>
                            <input id="endDate" name="end_date" type="date" class="form-input w-full" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" for="priceVnd">Giá (VND) <span class="text-red-500">*</span></label>
                        <input id="priceVnd" name="price_vnd" type="number" min="0" step="1000" class="form-input w-full" placeholder="Nhập giá phòng" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-1" for="reason">Lý do</label>
                        <textarea id="reason" name="reason" rows="3" class="form-textarea w-full" placeholder="Nhập lý do thay đổi giá (tùy chọn)"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelBtn" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
                            Hủy
                        </button>
                        <button type="submit" id="saveBtn" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                            <span id="saveBtnText">Lưu</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Xác nhận xóa</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Bạn có chắc chắn muốn xóa bản ghi này?</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button id="cancelDeleteBtn" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
                        Hủy
                    </button>
                    <button id="confirmDeleteBtn" class="btn bg-red-500 hover:bg-red-600 text-white">
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
        <div id="toastContent" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div id="toastIcon" class="flex-shrink-0 w-5 h-5 mr-3">
                    <!-- Dynamic icon -->
                </div>
                <div id="toastMessage" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    <!-- Dynamic message -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentPage = 1;
        let currentFilters = {};
        let deleteId = null;
        let isEdit = false;

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
            loadDropdownData();
            loadPricingData(1);
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Filter toggle
            document.getElementById('filterBtn').addEventListener('click', toggleFilters);
            
            // Add buttons
            document.getElementById('addBtn').addEventListener('click', () => openModal());
            document.getElementById('addBtnEmpty').addEventListener('click', () => openModal());
            
            // Export button
            document.getElementById('exportBtn').addEventListener('click', exportData);
            
            // Modal controls
            document.getElementById('closeModalBtn').addEventListener('click', closeModal);
            document.getElementById('cancelBtn').addEventListener('click', closeModal);
            
            // Delete modal controls
            document.getElementById('cancelDeleteBtn').addEventListener('click', closeDeleteModal);
            document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);
            
            // Form submission
            document.getElementById('pricingForm').addEventListener('submit', handleFormSubmit);
            
            // Pricing type change
            document.getElementById('pricingType').addEventListener('change', handlePricingTypeChange);
            
            // Filter controls
            document.getElementById('applyFiltersBtn').addEventListener('click', applyFilters);
            document.getElementById('clearFiltersBtn').addEventListener('click', clearFilters);
            
            // Close modals when clicking outside
            document.getElementById('pricingModal').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });
            
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) closeDeleteModal();
            });
        }

        // Load statistics
        async function loadStatistics() {
            try {
                const response = await fetch('/admin/room-prices/event-festival/statistics');
                const stats = await response.json();
                
                const container = document.getElementById('statisticsContainer');
                container.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Sự kiện</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.events || 0}</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Lễ hội</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.holidays || 0}</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Cuối tuần</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.weekends || 0}</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Đang hoạt động</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.active || 0}</div>
                            </div>
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Error loading statistics:', error);
                showToast('Có lỗi khi tải thống kê', 'error');
            }
        }

   // Load dropdown data
async function loadDropdownData() {
    try {
        console.log('Loading dropdown data...');
        
        // Load rooms
        try {
            console.log('Fetching rooms...');
            const roomsResponse = await fetch('/admin/room-prices/event-festival/rooms');
            console.log('Rooms response status:', roomsResponse.status);
            
            if (roomsResponse.ok) {
                const rooms = await roomsResponse.json();
                console.log('Rooms data:', rooms);
                
                const roomSelect = document.getElementById('roomId');
                if (roomSelect) {
                    roomSelect.innerHTML = '<option value="">Chọn phòng</option>';
                    if (Array.isArray(rooms) && rooms.length > 0) {
                        rooms.forEach(room => {
                            roomSelect.innerHTML += `<option value="${room.room_id}">${room.room_name} (${room.room_type})</option>`;
                        });
                        console.log('Rooms loaded successfully:', rooms.length);
                    } else {
                        console.warn('No rooms data received');
                    }
                }
            } else {
                console.error('Failed to load rooms:', roomsResponse.status);
            }
        } catch (error) {
            console.error('Error loading rooms:', error);
        }

        // Load events
        try {
            console.log('Fetching events...');
            const eventsResponse = await fetch('/admin/room-prices/event-festival/events');
            console.log('Events response status:', eventsResponse.status);
            
            if (eventsResponse.ok) {
                const events = await eventsResponse.json();
                console.log('Events data:', events);
                
                const eventSelect = document.getElementById('eventId');
                if (eventSelect) {
                    eventSelect.innerHTML = '<option value="">Chọn sự kiện</option>';
                    if (Array.isArray(events) && events.length > 0) {
                        events.forEach(event => {
                            eventSelect.innerHTML += `<option value="${event.event_id}">${event.name}</option>`;
                        });
                        console.log('Events loaded successfully:', events.length);
                    } else {
                        console.warn('No events data received');
                    }
                }
            } else {
                console.error('Failed to load events:', eventsResponse.status);
            }
        } catch (error) {
            console.error('Error loading events:', error);
        }

        // Load holidays
        try {
            console.log('Fetching holidays...');
            const holidaysResponse = await fetch('/admin/room-prices/event-festival/holidays');
            console.log('Holidays response status:', holidaysResponse.status);
            
            if (holidaysResponse.ok) {
                const holidays = await holidaysResponse.json();
                console.log('Holidays data:', holidays);
                
                const holidaySelect = document.getElementById('holidayId');
                if (holidaySelect) {
                    holidaySelect.innerHTML = '<option value="">Chọn lễ hội</option>';
                    if (Array.isArray(holidays) && holidays.length > 0) {
                        holidays.forEach(holiday => {
                            holidaySelect.innerHTML += `<option value="${holiday.holiday_id}">${holiday.name}</option>`;
                        });
                        console.log('Holidays loaded successfully:', holidays.length);
                    } else {
                        console.warn('No holidays data received');
                    }
                }
            } else {
                console.error('Failed to load holidays:', holidaysResponse.status);
            }
        } catch (error) {
            console.error('Error loading holidays:', error);
        }

    } catch (error) {
        console.error('Error in loadDropdownData:', error);
        showToast('Có lỗi khi tải dữ liệu dropdown: ' + error.message, 'error');
    }
}


        // Load pricing data
        async function loadPricingData(page = 1) {
            try {
                showLoading();
                
                const params = new URLSearchParams({
                    page: page,
                    ...currentFilters
                });

                const response = await fetch(`/admin/room-prices/event-festival/data?${params}`);
                const data = await response.json();

                if (data.data && data.data.length > 0) {
                    renderTable(data.data);
                    renderPagination(data);
                    showTable();
                } else {
                    showEmptyState();
                }

                hideLoading();
            } catch (error) {
                console.error('Error loading pricing data:', error);
                showToast('Có lỗi khi tải dữ liệu', 'error');
                hideLoading();
                showEmptyState();
            }
        }

        // Render table
        function renderTable(data) {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';

            data.forEach(item => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50';
                
                // Determine type and name
                let type = '';
                let name = '';
                let badge = '';
                
                if (item.event_id) {
                    type = 'Sự kiện';
                    name = item.event_name || 'N/A';
                    badge = 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400';
                } else if (item.holiday_id) {
                    type = 'Lễ hội';
                    name = item.holiday_name || 'N/A';
                    badge = 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
                } else if (item.is_weekend) {
                    type = 'Cuối tuần';
                    name = 'Thứ 7 & Chủ nhật';
                    badge = 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400';
                }

                // Format price
                const formattedPrice = new Intl.NumberFormat('vi-VN').format(item.price_vnd);
                
                // Format dates
                const startDate = new Date(item.start_date).toLocaleDateString('vi-VN');
                const endDate = new Date(item.end_date).toLocaleDateString('vi-VN');
                
                // Check if active
                const today = new Date();
                const start = new Date(item.start_date);
                const end = new Date(item.end_date);
                const isActive = today >= start && today <= end;

                row.innerHTML = `
                    <td class="p-2 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badge}">
                            ${type}
                        </span>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="font-medium text-gray-900 dark:text-gray-100">${name}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-gray-900 dark:text-gray-100">${item.room_name || 'N/A'}</div>
                        <div class="text-xs text-gray-500">${item.room_type || ''}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-gray-900 dark:text-gray-100">${startDate}</div>
                        <div class="text-xs text-gray-500">đến ${endDate}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="font-medium text-gray-900 dark:text-gray-100">${formattedPrice} ₫</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                            isActive 
                                ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' 
                                : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'
                        }">
                            ${isActive ? 'Đang áp dụng' : 'Chưa áp dụng'}
                        </span>
                    </td>
                    <td class="p-2 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="editPricing(${item.pricing_id})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="deletePricing(${item.pricing_id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        // Render pagination
        function renderPagination(data) {
            const container = document.getElementById('paginationContainer');
            
            if (data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let paginationHTML = '<div class="flex items-center justify-between">';
            
            // Info
            paginationHTML += `
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Hiển thị <span class="font-medium">${data.from || 0}</span> đến <span class="font-medium">${data.to || 0}</span> 
                    trong tổng số <span class="font-medium">${data.total}</span> kết quả
                </div>
            `;
            
            // Pagination buttons
            paginationHTML += '<div class="flex items-center space-x-2">';
            
            // Previous button
            if (data.current_page > 1) {
                paginationHTML += `
                    <button onclick="loadPricingData(${data.current_page - 1})" 
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
                paginationHTML += `
                    <button onclick="loadPricingData(${i})" 
                            class="px-3 py-2 text-sm font-medium ${
                                isActive 
                                    ? 'text-white bg-indigo-600 border border-indigo-600' 
                                    : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'
                            } rounded-md">
                        ${i}
                    </button>
                `;
            }
            
            // Next button
            if (data.current_page < data.last_page) {
                paginationHTML += `
                    <button onclick="loadPricingData(${data.current_page + 1})" 
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700">
                        Sau
                    </button>
                `;
            }
            
            paginationHTML += '</div></div>';
            container.innerHTML = paginationHTML;
        }

        // Show/hide states
        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('tableContent').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingState').classList.add('hidden');
        }

        function showEmptyState() {
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('tableContent').classList.add('hidden');
        }

        function showTable() {
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('tableContent').classList.remove('hidden');
        }

        // Modal functions
        function openModal(id = null) {
            isEdit = !!id;
            document.getElementById('modalTitle').textContent = isEdit ? 'Chỉnh sửa giá' : 'Thêm giá mới';
            document.getElementById('saveBtnText').textContent = isEdit ? 'Cập nhật' : 'Lưu';
            
            if (isEdit) {
                loadPricingForEdit(id);
            } else {
                resetForm();
            }
            
            document.getElementById('pricingModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('pricingModal').classList.add('hidden');
            resetForm();
        }

        function resetForm() {
            document.getElementById('pricingForm').reset();
            document.getElementById('pricingId').value = '';
            hideAllSections();
            clearRequiredAttributes();
        }

        // Handle pricing type change
        function handlePricingTypeChange(e) {
            const type = e.target.value;
            hideAllSections();
            clearRequiredAttributes();
            
            switch(type) {
                case 'event':
                    document.getElementById('eventSection').classList.remove('hidden');
                    document.getElementById('eventId').setAttribute('required', 'required');
                    break;
                case 'holiday':
                    document.getElementById('holidaySection').classList.remove('hidden');
                    document.getElementById('holidayId').setAttribute('required', 'required');
                    break;
                case 'weekend':
                    document.getElementById('weekendSection').classList.remove('hidden');
                    break;
            }
        }

        function hideAllSections() {
            document.getElementById('eventSection').classList.add('hidden');
            document.getElementById('holidaySection').classList.add('hidden');
            document.getElementById('weekendSection').classList.add('hidden');
        }

        function clearRequiredAttributes() {
            document.getElementById('eventId').removeAttribute('required');
            document.getElementById('holidayId').removeAttribute('required');
        }

        // Load pricing for edit
        async function loadPricingForEdit(id) {
            try {
                const response = await fetch(`/admin/room-prices/event-festival/${id}`);
                const data = await response.json();
                
                if (data.success) {
                    const pricing = data.data;
                    
                    // Fill form
                    document.getElementById('pricingId').value = pricing.pricing_id;
                    document.getElementById('roomId').value = pricing.room_id;
                    document.getElementById('startDate').value = pricing.start_date;
                    document.getElementById('endDate').value = pricing.end_date;
                    document.getElementById('priceVnd').value = pricing.price_vnd;
                    document.getElementById('reason').value = pricing.reason || '';
                    
                    // Set pricing type and related fields
                    if (pricing.event_id) {
                        document.getElementById('pricingType').value = 'event';
                        document.getElementById('eventId').value = pricing.event_id;
                        handlePricingTypeChange({target: {value: 'event'}});
                                       } else if (pricing.holiday_id) {
                        document.getElementById('pricingType').value = 'holiday';
                        document.getElementById('holidayId').value = pricing.holiday_id;
                        handlePricingTypeChange({target: {value: 'holiday'}});
                    } else if (pricing.is_weekend) {
                        document.getElementById('pricingType').value = 'weekend';
                        handlePricingTypeChange({target: {value: 'weekend'}});
                    }
                } else {
                    showToast('Không thể tải dữ liệu để chỉnh sửa', 'error');
                }
            } catch (error) {
                console.error('Error loading pricing for edit:', error);
                showToast('Có lỗi khi tải dữ liệu', 'error');
            }
        }

        // Handle form submit
        async function handleFormSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = {};
            
            // Convert FormData to object
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            // Remove empty values for optional fields
            Object.keys(data).forEach(key => {
                if (data[key] === '' && !['reason'].includes(key)) {
                    delete data[key];
                }
            });
            
            try {
                const pricingId = data.pricing_id;
                const url = isEdit ? `/admin/room-prices/event-festival/${pricingId}` : '/admin/room-prices/event-festival';
                const method = isEdit ? 'PUT' : 'POST';
                
                // Show loading on save button
                const saveBtn = document.getElementById('saveBtn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>Đang lưu...';
                saveBtn.disabled = true;
                
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
                    showToast(result.message, 'success');
                    closeModal();
                    loadPricingData(currentPage);
                    loadStatistics();
                } else {
                    showToast(result.message || 'Có lỗi xảy ra', 'error');
                    
                    // Show validation errors if any
                    if (result.errors) {
                        Object.keys(result.errors).forEach(field => {
                            console.error(`${field}: ${result.errors[field].join(', ')}`);
                        });
                    }
                }
                
                // Restore button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                
            } catch (error) {
                console.error('Error saving pricing:', error);
                showToast('Có lỗi khi lưu dữ liệu', 'error');
                
                // Restore button
                const saveBtn = document.getElementById('saveBtn');
                saveBtn.innerHTML = document.getElementById('saveBtnText').textContent;
                saveBtn.disabled = false;
            }
        }

        // Edit pricing
        function editPricing(id) {
            openModal(id);
        }

        // Delete pricing
        function deletePricing(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteId = null;
        }

        async function confirmDelete() {
            if (!deleteId) return;
            
            try {
                const response = await fetch(`/admin/room-prices/event-festival/${deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    closeDeleteModal();
                    loadPricingData(currentPage);
                    loadStatistics();
                } else {
                    showToast(result.message || 'Có lỗi khi xóa', 'error');
                }
            } catch (error) {
                console.error('Error deleting pricing:', error);
                showToast('Có lỗi khi xóa dữ liệu', 'error');
            }
        }

        // Filter functions
        function toggleFilters() {
            const container = document.getElementById('filtersContainer');
            container.classList.toggle('hidden');
        }

        function applyFilters() {
            const type = document.getElementById('filterType').value;
            const startDate = document.getElementById('filterStartDate').value;
            const endDate = document.getElementById('filterEndDate').value;
            
            currentFilters = {};
            
            if (type) currentFilters.type = type;
            if (startDate) currentFilters.start_date = startDate;
            if (endDate) currentFilters.end_date = endDate;
            
            currentPage = 1;
            loadPricingData(1);
        }

        function clearFilters() {
            document.getElementById('filterType').value = '';
            document.getElementById('filterStartDate').value = '';
            document.getElementById('filterEndDate').value = '';
            
            currentFilters = {};
            currentPage = 1;
            loadPricingData(1);
        }

        // Export function
        async function exportData() {
            try {
                const params = new URLSearchParams(currentFilters);
                const url = `/admin/room-prices/event-festival/export/csv?${params}`;
                
                // Create temporary link and trigger download
                const link = document.createElement('a');
                link.href = url;
                link.download = `room-prices-${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                showToast('Đang tải file xuống...', 'success');
            } catch (error) {
                console.error('Error exporting data:', error);
                showToast('Có lỗi khi xuất dữ liệu', 'error');
            }
        }

        // Toast notification
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toastContent');
            const toastIcon = document.getElementById('toastIcon');
            const toastMessage = document.getElementById('toastMessage');
            
            // Set icon based on type
            let iconHTML = '';
            let bgColor = '';
            
            switch(type) {
                case 'success':
                    iconHTML = `
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    `;
                    bgColor = 'border-green-200 dark:border-green-800';
                    break;
                case 'error':
                    iconHTML = `
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    `;
                    bgColor = 'border-red-200 dark:border-red-800';
                    break;
                case 'warning':
                    iconHTML = `
                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    `;
                    bgColor = 'border-yellow-200 dark:border-yellow-800';
                    break;
                default:
                    iconHTML = `
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    `;
                    bgColor = 'border-blue-200 dark:border-blue-800';
            }
            
            toastIcon.innerHTML = iconHTML;
            toastMessage.textContent = message;
            toastContent.className = `bg-white dark:bg-gray-800 border ${bgColor} rounded-lg shadow-lg p-4 max-w-sm`;
            
            // Show toast
            toast.classList.remove('hidden');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        }
    </script>

    <style>
        .form-input, .form-select, .form-textarea {
            @apply block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500;
        }
        
        .btn {
            @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
    </style>
</x-app-layout>

