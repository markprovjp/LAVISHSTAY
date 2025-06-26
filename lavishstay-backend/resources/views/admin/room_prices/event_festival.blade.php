<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý Sự kiện & Lễ hội</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý thời gian và thông tin các sự kiện, lễ hội
                </p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Filter button -->
                <button id="filterBtn"
                    class="btn cursor-pointer bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M9 15H7a1 1 0 010-2h2a1 1 0 010 2zM11 11H5a1 1 0 010-2h6a1 1 0 010 2zM13 7H3a1 1 0 010-2h10a1 1 0 010 2zM15 3H1a1 1 0 010-2h14a1 1 0 010 2z" />
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

                <!-- Add button -->
                <button id="addBtn" class="btn cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
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

        <!-- Upcoming Events Card -->
        <div id="upcomingEventsCard" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6 hidden">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Sự kiện & Lễ hội sắp tới</h3>
            <div id="upcomingEventsList" class="space-y-3">
                <!-- Dynamic content -->
            </div>
        </div>

        <!-- Filters -->
        <div id="filtersContainer" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="filterType">Loại</label>
                    <select id="filterType" class="form-select w-full">
                        <option value="">Tất cả</option>
                        <option value="events">Sự kiện</option>
                        <option value="holidays">Lễ hội</option>
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
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có dữ liệu</h3>
                        <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách thêm sự kiện hoặc lễ hội mới.</p>
                        <div class="mt-6">
                            <button id="addBtnEmpty" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                                Thêm mới
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div id="tableContent" class="hidden">
                        <div class="">
                            <table class="table-auto w-full">
                                <thead>
                                    <tr
                                        class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20">
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Loại</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Tên</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Mô tả</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Thời gian</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Trạng thái</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-center">Thao tác</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody"
                                    class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
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
    <div id="eventModal" class="fixed modal-overlay inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100" id="modalTitle">Thêm mới
                        </h3>
                        <button onclick="closeModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="eventForm" class="space-y-6">
                        <input type="hidden" id="itemId" name="item_id">
                        <input type="hidden" id="itemType" name="item_type">

                        <!-- Type and Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại
                                    <span class="text-red-500">*</span></label>
                                <select id="type" name="type" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">Chọn loại</option>
                                    <option value="event">Sự kiện</option>
                                    <option value="holiday">Lễ hội</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trạng
                                    thái</label>
                                <select id="isActive" name="is_active"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Không hoạt động</option>
                                </select>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tên <span
                                    class="text-red-500">*</span></label>
                            <input id="name" name="name" type="text" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập tên sự kiện/lễ hội">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mô
                                tả</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập mô tả (tùy chọn)"></textarea>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày bắt
                                    đầu <span class="text-red-500">*</span></label>
                                <input id="startDate" name="start_date" type="date" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div id="endDateContainer">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày kết
                                    thúc</label>
                                <input id="endDate" name="end_date" type="date"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500">
                                Hủy
                            </button>
                            <button type="submit" id="submitBtn"
                                class="px-4 py-2 text-sm font-medium btn cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white  focus:outline-none focus:ring-2 focus:ring-violet-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="submitText">Thêm mới</span>
                                <svg id="submitLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    @endif

    <script>
        // Global variables
        let currentPage = 1;
        let currentFilters = {};
        let isLoading = false;

        // DOM elements
        const elements = {
            filterBtn: document.getElementById('filterBtn'),
            exportBtn: document.getElementById('exportBtn'),
            addBtn: document.getElementById('addBtn'),
            addBtnEmpty: document.getElementById('addBtnEmpty'),
            filtersContainer: document.getElementById('filtersContainer'),
            applyFiltersBtn: document.getElementById('applyFiltersBtn'),
            clearFiltersBtn: document.getElementById('clearFiltersBtn'),
            eventModal: document.getElementById('eventModal'),
            eventForm: document.getElementById('eventForm'),
            loadingState: document.getElementById('loadingState'),
            emptyState: document.getElementById('emptyState'),
            tableContent: document.getElementById('tableContent'),
            tableBody: document.getElementById('tableBody'),
            paginationContainer: document.getElementById('paginationContainer'),
            statisticsContainer: document.getElementById('statisticsContainer'),
            upcomingEventsCard: document.getElementById('upcomingEventsCard'),
            upcomingEventsList: document.getElementById('upcomingEventsList'),
            type: document.getElementById('type'),
            endDateContainer: document.getElementById('endDateContainer'),
            endDate: document.getElementById('endDate')
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
            loadUpcomingEvents();
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
            // Filter toggle
            elements.filterBtn.addEventListener('click', toggleFilters);

            // Export
            elements.exportBtn.addEventListener('click', exportData);

            // Add buttons
            elements.addBtn.addEventListener('click', () => showModal());
            if (elements.addBtnEmpty) {
                elements.addBtnEmpty.addEventListener('click', () => showModal());
            }

            // Modal
            elements.eventForm.addEventListener('submit', handleSubmit);

            // Filters
            elements.applyFiltersBtn.addEventListener('click', applyFilters);
            elements.clearFiltersBtn.addEventListener('click', clearFilters);

            // Type change event
            elements.type.addEventListener('change', handleTypeChange);

            // Close modal on outside click
            elements.eventModal.addEventListener('click', function(e) {
                if (e.target === elements.eventModal) {
                    closeModal();
                }
            });
        }

        // Load statistics
        async function loadStatistics() {
            try {
                const response = await fetch('{{ route('admin.event-festival-management.statistics') }}');
                const data = await response.json();

                if (response.ok) {
                    updateStatistics(data);
                } else {
                    console.error('Failed to load statistics:', data);
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // Load upcoming events
        async function loadUpcomingEvents() {
            try {
                const response = await fetch('{{ route('admin.event-festival-management.upcoming') }}');
                const data = await response.json();

                if (response.ok && data.length > 0) {
                    updateUpcomingEvents(data);
                    elements.upcomingEventsCard.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading upcoming events:', error);
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng sự kiện</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.total_events || 0}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng lễ hội</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.total_holidays || 0}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Đang hoạt động</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.active_total || 0}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Sắp diễn ra</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.upcoming_total || 0}</div>
                        </div>
                    </div>
                </div>
            `;

            elements.statisticsContainer.innerHTML = statisticsHtml;
        }

        // Update upcoming events display
        function updateUpcomingEvents(events) {
            const eventsHtml = events.map(event => {
                const typeClass = event.type === 'event' ? 'bg-blue-100 text-blue-800' :
                    'bg-purple-100 text-purple-800';
                const typeName = event.type === 'event' ? 'Sự kiện' : 'Lễ hội';
                const startDate = new Date(event.start_date).toLocaleDateString('vi-VN');
                const endDate = event.end_date && event.end_date !== event.start_date ?
                    new Date(event.end_date).toLocaleDateString('vi-VN') :
                    null;
                const timeDisplay = endDate ? `${startDate} - ${endDate}` : startDate;

                return `
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${typeClass}">
                                ${typeName}
                            </span>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">${event.name}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${timeDisplay}</div>
                            </div>
                        </div>
                        <div class="text-sm bg-green-500 text-white p-2 rounded-xl dark:text-white-900">
                            ${event.days_until} ngày nữa
                        </div>
                    </div>
                `;
            }).join('');

            elements.upcomingEventsList.innerHTML = eventsHtml;
        }

        // Toggle filters
        function toggleFilters() {
            elements.filtersContainer.classList.toggle('hidden');
        }

        // Load data
        async function loadData(page = 1) {
            if (isLoading) return;

            isLoading = true;
            showLoading();

            try {
                const params = new URLSearchParams({
                    page: page,
                    ...currentFilters
                });

                const response = await fetch(`{{ route('admin.event-festival-management.data') }}?${params}`);
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
            const rows = data.data.map(item => {
                const typeClass = item.type === 'event' ? 'bg-blue-100 text-blue-800' :
                    'bg-purple-100 text-purple-800';
                const typeName = item.type === 'event' ? 'Sự kiện' : 'Lễ hội';
                const statusClass = item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const statusText = item.is_active ? 'Hoạt động' : 'Không hoạt động';

                const startDate = new Date(item.start_date).toLocaleDateString('vi-VN');
                const endDate = item.end_date && item.end_date !== item.start_date ?
                    new Date(item.end_date).toLocaleDateString('vi-VN') :
                    null;

                const timeDisplay = endDate ? `${startDate} - ${endDate}` : startDate;

                return `
                    <tr>
                        <td class="p-2 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${typeClass}">
                                ${typeName}
                            </span>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${item.name}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" title="${item.description || ''}">
                                ${item.description || '-'}
                            </div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">${timeDisplay}</div>
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
                                    onclick="toggleDropdown(${item.id}, '${item.type}')"
                                    id="dropdown-button-${item.type}-${item.id}">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                        </path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="dropdown-menu-${item.type}-${item.id}"
                                    class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1" role="menu">
                                        <!-- Edit -->
                                        <button onclick="editItem('${item.type}', ${item.id}); closeDropdown(${item.id}, '${item.type}')"
                                            class="flex items-center w-full cursor-pointer px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20px" height="20px">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Chỉnh sửa
                                        </button>

                                        <!-- Divider -->
                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                        <!-- Delete -->
                                        <button
                                            onclick="deleteItem('${item.type}', ${item.id}, '${item.name}'); closeDropdown(${item.id}, '${item.type}')"
                                            class="flex cursor-pointer cursor-pointer items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20px" height="20px">
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

        // Apply filters
        function applyFilters() {
            currentFilters = {
                type: document.getElementById('filterType').value,
                start_date: document.getElementById('filterStartDate').value,
                end_date: document.getElementById('filterEndDate').value
            };

            // Remove empty filters
            Object.keys(currentFilters).forEach(key => {
                if (!currentFilters[key]) {
                    delete currentFilters[key];
                }
            });

            loadData(1);
        }

        // Clear filters
        function clearFilters() {
            document.getElementById('filterType').value = '';
            document.getElementById('filterStartDate').value = '';
            document.getElementById('filterEndDate').value = '';
            currentFilters = {};
            loadData(1);
        }

        // Show modal
        function showModal(type = null, id = null) {
            // Reset form
            elements.eventForm.reset();
            document.getElementById('itemId').value = '';
            document.getElementById('itemType').value = '';

            if (id) {
                // Edit mode
                document.getElementById('modalTitle').textContent = 'Chỉnh sửa';
                document.getElementById('submitText').textContent = 'Cập nhật';
                document.getElementById('itemId').value = id;
                document.getElementById('itemType').value = type;
                loadItemData(type, id);
            } else {
                // Add mode
                document.getElementById('modalTitle').textContent = 'Thêm mới';
                document.getElementById('submitText').textContent = 'Thêm mới';
                handleTypeChange(); // Initialize form state
            }

            elements.eventModal.classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            elements.eventModal.classList.add('hidden');
        }

        // Handle type change
        function handleTypeChange() {
            const type = elements.type.value;
            const endDateContainer = elements.endDateContainer;
            const endDateInput = elements.endDate;

            if (type === 'holiday') {
                // Show end date for holidays (since holidays now have start_date and end_date)
                endDateContainer.style.display = 'block';
                endDateInput.required = false;
            } else if (type === 'event') {
                // Show end date for events
                endDateContainer.style.display = 'block';
                endDateInput.required = false;
            } else {
                // Hide end date if no type selected
                endDateContainer.style.display = 'none';
                endDateInput.required = false;
            }
        }

        // Load item data for editing
        async function loadItemData(type, id) {
            try {
                const response = await fetch(`{{ url('admin/event-festival-management') }}/${type}/${id}`);
                const result = await response.json();

                if (response.ok && result.success) {
                    const item = result.data;

                    // Fill form
                    document.getElementById('type').value = type;
                    document.getElementById('name').value = item.name || '';
                    document.getElementById('description').value = item.description || '';
                    document.getElementById('isActive').value = item.is_active ? '1' : '0';
                    document.getElementById('startDate').value = item.start_date || '';
                    document.getElementById('endDate').value = item.end_date || '';

                    handleTypeChange();
                } else {
                    showError('Không thể tải dữ liệu: ' + (result.message || 'Lỗi không xác định'));
                }
            } catch (error) {
                console.error('Error loading item data:', error);
                showError('Có lỗi xảy ra khi tải dữ liệu');
            }
        }

        // Handle form submit
        async function handleSubmit(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');

            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');

            try {
                const formData = new FormData(elements.eventForm);
                const itemId = document.getElementById('itemId').value;
                const itemType = document.getElementById('itemType').value;

                let url, method;
                if (itemId && itemType) {
                    // Update
                    url = `{{ url('admin/event-festival-management') }}/${itemType}/${itemId}`;
                    method = 'PUT';
                } else {
                    // Create
                    url = '{{ route('admin.event-festival-management.store') }}';
                    method = 'POST';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    closeModal();
                    showSuccess(result.message || 'Thao tác thành công!');
                    loadData(currentPage);
                    loadStatistics();
                    loadUpcomingEvents();
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
                console.error('Error submitting form:', error);
                showError('Có lỗi xảy ra khi gửi dữ liệu');
            } finally {
                // Hide loading
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        // Toggle dropdown menu
        function toggleDropdown(id, type) {
            const dropdown = document.getElementById(`dropdown-menu-${type}-${id}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            // Close all other dropdowns
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${type}-${id}`) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown
        function closeDropdown(id, type) {
            const dropdown = document.getElementById(`dropdown-menu-${type}-${id}`);
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

        // Edit item
        function editItem(type, id) {
            showModal(type, id);
        }

        // Delete item
        async function deleteItem(type, id, name) {
            if (!confirm(`Bạn có chắc chắn muốn xóa "${name}"?`)) {
                return;
            }

            try {
                const response = await fetch(`{{ url('admin/event-festival-management') }}/${type}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess(result.message || 'Xóa thành công!');
                    loadData(currentPage);
                    loadStatistics();
                    loadUpcomingEvents();
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi xóa');
                }
            } catch (error) {
                console.error('Error deleting item:', error);
                showError('Có lỗi xảy ra khi xóa dữ liệu');
            }
        }

        // Export data
        async function exportData() {
            try {
                const params = new URLSearchParams(currentFilters);
                window.open(`{{ route('admin.event-festival-management.export') }}?${params}`, '_blank');
            } catch (error) {
                console.error('Error exporting data:', error);
                showError('Có lỗi xảy ra khi xuất dữ liệu');
            }
        }

        // Show success notification
        function showSuccess(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.id = 'dynamicNotification';
            notification.className =
                'fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md z-50';
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
                <button onclick="closeDynamicNotification()" class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;

            document.body.appendChild(notification);

            // Auto close after 3 seconds
            setTimeout(() => {
                closeDynamicNotification();
            }, 3000);
        }

        // Show error notification
        function showError(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.id = 'dynamicNotification';
            notification.className =
                'fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md z-50';
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
                <button onclick="closeDynamicNotification()" class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;

            document.body.appendChild(notification);

            // Auto close after 5 seconds
            setTimeout(() => {
                closeDynamicNotification();
            }, 5000);
        }

        // Close dynamic notification
        function closeDynamicNotification() {
            const notification = document.getElementById('dynamicNotification');
            if (notification) {
                notification.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
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
            .form-select:: placeholder {
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
    </style>

</x-app-layout>
