<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý Quy tắc Giá Linh hoạt
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý quy tắc giá theo cuối tuần, sự kiện, lễ hội
                    và mùa</p>
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

                <!-- Add Pricing Rule button -->
                <button id="addPricingRuleBtn" class="btn cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm quy tắc giá</span>
                </button>

                <!-- Add Event/Holiday button -->
                <button id="addEventHolidayBtn" class="btn cursor-pointer bg-green-500 hover:bg-green-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm sự kiện/lễ hội</span>
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="filterRuleType">Loại quy tắc</label>
                    <select id="filterRuleType" class="form-select w-full">
                        <option value="">Tất cả</option>
                        <option value="weekend">Cuối tuần</option>
                        <option value="event">Sự kiện</option>
                        <option value="holiday">Lễ hội</option>
                        <option value="season">Mùa</option>
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
                    <label class="block text-sm font-medium mb-1" for="filterStatus">Trạng thái</label>
                    <select id="filterStatus" class="form-select w-full">
                        <option value="">Tất cả</option>
                        <option value="1">Hoạt động</option>
                        <option value="0">Không hoạt động</option>
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
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có dữ liệu</h3>
                        <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách thêm quy tắc giá mới.</p>
                        <div class="mt-6">
                            <button id="addPricingRuleBtnEmpty"
                                class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                                Thêm quy tắc giá
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
                                            <div class="font-semibold text-left">ID</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Loại quy tắc</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Loại phòng</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Chi tiết</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Thời gian</div>
                                        </th>
                                        <th class="p-2 whitespace-nowrap">
                                            <div class="font-semibold text-left">Điều chỉnh giá</div>
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

    <!-- Add/Edit Pricing Rule Modal -->
    <div id="pricingRuleModal" class="fixed modal-overlay inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-7xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100" id="pricingRuleModalTitle">
                            Thêm quy tắc giá</h3>
                        <button onclick="closePricingRuleModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="pricingRuleForm" class="space-y-6">
                        <input type="hidden" id="pricingRuleId" name="rule_id">

                        <!-- Rule Type and Room Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại quy
                                    tắc
                                    <span class="text-red-500">*</span></label>
                                <select id="ruleType" name="rule_type" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">Chọn loại quy tắc</option>
                                    <option value="weekend">Cuối tuần</option>
                                    <option value="event">Sự kiện</option>
                                    <option value="holiday">Lễ hội</option>
                                    <option value="season">Mùa</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại
                                    phòng</label>
                                <select id="roomTypeId" name="room_type_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">Áp dụng cho tất cả loại phòng</option>
                                </select>
                            </div>
                        </div>

                        <!-- Price Adjustment -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Điều chỉnh
                                giá (%)
                                <span class="text-red-500">*</span></label>
                            <input id="priceAdjustment" name="price_adjustment" type="number" required
                                step="0.01" min="-100" max="1000"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập tỷ lệ điều chỉnh giá (VD: 20 = tăng 20%, -10 = giảm 10%)">
                            <p class="text-xs text-gray-500 mt-1">Số dương để tăng giá, số âm để giảm giá</p>
                        </div>

                        <!-- Weekend Days Selection -->
                        <div id="weekendDaysContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày cuối
                                tuần áp dụng
                                <span class="text-red-500">*</span></label>
                            <div id="weekendDaysSelection" class="flex justify-between gap-2">
                                <div
                                    class="flex items-center p-2 border border-gray-200 cursor-pointer dark:border-gray-600 rounded-lg">
                                    <input type="checkbox" id="monday" name="days_of_week[]" value="Monday"
                                        class="form-checkbox  h-5 w-5 text-violet-600 ">
                                    <label for="monday"
                                        class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Thứ
                                        Hai</label>
                                </div>
                                <div
                                    class="flex items-center p-2 border border-gray-200 cursor-pointer dark:border-gray-600 rounded-lg">
                                    <input type="checkbox" id="tuesday" name="days_of_week[]" value="Tuesday"
                                        class="form-checkbox  h-5 w-5 text-violet-600 ">
                                    <label for="tuesday"
                                        class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Thứ
                                        Ba</label>
                                </div>
                                <div
                                    class="flex items-center p-2 border border-gray-200 cursor-pointer dark:border-gray-600 rounded-lg">
                                    <input type="checkbox" id="wednesday" name="days_of_week[]" value="Wednesday"
                                        class="form-checkbox  h-5 w-5 text-violet-600 ">
                                    <label for="wednesday"
                                        class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Thứ
                                        Tư</label>
                                </div>
                                <div
                                    class="flex items-center p-2 border border-gray-200 cursor-pointer dark:border-gray-600 rounded-lg">
                                    <input type="checkbox" id="thursday" name="days_of_week[]" value="Thursday"
                                        class="form-checkbox  h-5 w-5 text-violet-600 ">
                                    <label for="thursday"
                                        class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Thứ
                                        Năm</label>
                                </div>
                                <div
                                    class="flex items-center p-2 border border-gray-200 cursor-pointer dark:border-gray-600 rounded-lg">
                                    <input type="checkbox" id="friday" name="days_of_week[]" value="Friday"
                                        class="form-checkbox  h-5 w-5 text-violet-600 ">
                                    <label for="friday"
                                        class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Thứ
                                        Sáu</label>
                                </div>
                                <div
                                    class="flex items-center p-2 border border-gray-200 cursor-pointer dark:border-gray-600 rounded-lg">
                                    <input type="checkbox" id="saturday" name="days_of_week[]" value="Saturday"
                                        class="form-checkbox  h-5 w-5 text-violet-600 ">
                                    <label for="saturday"
                                        class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Thứ
                                        Bảy</label>
                                </div>
                                <div
                                    class="flex items-center p-2 border border-gray-200 cursor-pointer dark:border-gray-600 rounded-lg">
                                    <input type="checkbox" id="sunday" name="days_of_week[]" value="Sunday"
                                        class="form-checkbox  h-5 w-5 text-violet-600 ">
                                    <label for="sunday"
                                        class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Chủ
                                        Nhật</label>
                                </div>
                            </div>
                        </div>

                        <!-- Event Selection -->
                        <div id="eventContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sự kiện
                                <span class="text-red-500">*</span></label>
                            <select id="eventId" name="event_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Chọn sự kiện</option>
                            </select>
                        </div>

                        <!-- Holiday Selection -->
                        <div id="holidayContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lễ hội
                                <span class="text-red-500">*</span></label>
                            <select id="holidayId" name="holiday_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Chọn lễ hội</option>
                            </select>
                        </div>

                        <!-- Season Details -->
                        <div id="seasonContainer" class="hidden">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tên mùa
                                    <span class="text-red-500">*</span></label>
                                <input id="seasonName" name="season_name" type="text"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="VD: Mùa hè, Mùa cao điểm">
                            </div>
                        </div>

                        <!-- Date Range (for weekend and season) -->
                        <div id="dateRangeContainer" class="hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày
                                        bắt đầu</label>
                                    <input id="startDate" name="start_date" type="date"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <p class="text-xs text-gray-500 mt-1">Để trống nếu áp dụng vô thời hạn</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày
                                        kết thúc</label>
                                    <input id="endDate" name="end_date" type="date"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <p class="text-xs text-gray-500 mt-1">Để trống nếu áp dụng vô thời hạn</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="isActive" name="is_active" checked
                                    class="form-checkbox  h-5 w-5 text-violet-600">
                                <label for="isActive"
                                    class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kích hoạt quy tắc
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <button type="button" onclick="closePricingRuleModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500">
                                Hủy
                            </button>
                            <button type="submit" id="pricingRuleSubmitBtn"
                                class="px-4 py-2 text-sm font-medium btn cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white focus:outline-none focus:ring-2 focus:ring-violet-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="pricingRuleSubmitText">Thêm mới</span>
                                <svg id="pricingRuleSubmitLoading"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" fill="none"
                                    viewBox="0 0 24 24">
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

    <!-- Add/Edit Event/Holiday Modal -->
    <div id="eventHolidayModal" class="fixed modal-overlay inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-7xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100"
                            id="eventHolidayModalTitle">Thêm sự kiện/lễ hội</h3>
                        <button onclick="closeEventHolidayModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="eventHolidayForm" class="space-y-6">
                        <input type="hidden" id="eventHolidayId" name="item_id">
                        <input type="hidden" id="eventHolidayType" name="item_type">

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
                                <select id="eventHolidayIsActive" name="is_active"
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
                            <input id="eventHolidayName" name="name" type="text" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập tên sự kiện/lễ hội">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mô
                                tả</label>
                            <textarea id="eventHolidayDescription" name="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập mô tả (tùy chọn)"></textarea>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày bắt
                                    đầu <span class="text-red-500">*</span></label>
                                <input id="eventHolidayStartDate" name="start_date" type="date" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày kết
                                    thúc</label>
                                <input id="eventHolidayEndDate" name="end_date" type="date"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <button type="button" onclick="closeEventHolidayModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500">
                                Hủy
                            </button>
                            <button type="submit" id="eventHolidaySubmitBtn"
                                class="px-4 py-2 text-sm font-medium btn cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white focus:outline-none focus:ring-2 focus:ring-violet-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="eventHolidaySubmitText">Thêm mới</span>
                                <svg id="eventHolidaySubmitLoading"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" fill="none"
                                    viewBox="0 0 24 24">
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
        let isPricingRuleEditMode = false;
        let isEventHolidayEditMode = false;
        let editingPricingRuleId = null;
        let editingEventHolidayId = null;
        let editingEventHolidayType = null;

        // DOM elements
        const elements = {
            filterBtn: document.getElementById('filterBtn'),
            exportBtn: document.getElementById('exportBtn'),
            addPricingRuleBtn: document.getElementById('addPricingRuleBtn'),
            addEventHolidayBtn: document.getElementById('addEventHolidayBtn'),
            addPricingRuleBtnEmpty: document.getElementById('addPricingRuleBtnEmpty'),
            filtersContainer: document.getElementById('filtersContainer'),
            applyFiltersBtn: document.getElementById('applyFiltersBtn'),
            clearFiltersBtn: document.getElementById('clearFiltersBtn'),
            pricingRuleModal: document.getElementById('pricingRuleModal'),
            eventHolidayModal: document.getElementById('eventHolidayModal'),
            pricingRuleForm: document.getElementById('pricingRuleForm'),
            eventHolidayForm: document.getElementById('eventHolidayForm'),
            loadingState: document.getElementById('loadingState'),
            emptyState: document.getElementById('emptyState'),
            tableContent: document.getElementById('tableContent'),
            tableBody: document.getElementById('tableBody'),
            paginationContainer: document.getElementById('paginationContainer'),
            statisticsContainer: document.getElementById('statisticsContainer'),
            upcomingEventsCard: document.getElementById('upcomingEventsCard'),
            upcomingEventsList: document.getElementById('upcomingEventsList'),
            ruleType: document.getElementById('ruleType'),
            weekendDaysContainer: document.getElementById('weekendDaysContainer'),
            eventContainer: document.getElementById('eventContainer'),
            holidayContainer: document.getElementById('holidayContainer'),
            seasonContainer: document.getElementById('seasonContainer'),
            dateRangeContainer: document.getElementById('dateRangeContainer')
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
            loadUpcomingEvents();
            loadRoomTypes();
            loadEvents();
            loadHolidays();
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
            elements.addPricingRuleBtn.addEventListener('click', () => showPricingRuleModal());
            elements.addEventHolidayBtn.addEventListener('click', () => showEventHolidayModal());
            if (elements.addPricingRuleBtnEmpty) {
                elements.addPricingRuleBtnEmpty.addEventListener('click', () => showPricingRuleModal());
            }

            // Modal forms
            elements.pricingRuleForm.addEventListener('submit', handlePricingRuleSubmit);
            elements.eventHolidayForm.addEventListener('submit', handleEventHolidaySubmit);

            // Filters
            elements.applyFiltersBtn.addEventListener('click', applyFilters);
            elements.clearFiltersBtn.addEventListener('click', clearFilters);

            // Rule type change event
            elements.ruleType.addEventListener('change', handleRuleTypeChange);

            // Close modals on outside click
            elements.pricingRuleModal.addEventListener('click', function(e) {
                if (e.target === elements.pricingRuleModal) {
                    closePricingRuleModal();
                }
            });

            elements.eventHolidayModal.addEventListener('click', function(e) {
                if (e.target === elements.eventHolidayModal) {
                    closeEventHolidayModal();
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

        // Load room types
        async function loadRoomTypes() {
            try {
                const response = await fetch('{{ route('admin.dynamic-pricing.room-types') }}');
                const roomTypes = await response.json();

                const select = document.getElementById('roomTypeId');
                select.innerHTML = '<option value="">Áp dụng cho tất cả loại phòng</option>';

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

        async function loadEvents() {
            try {
                const response = await fetch('{{ route('admin.events.data') }}');
                const data = await response.json();
                console.log('Loaded events:', data);

                const select = document.getElementById('eventId');
                select.innerHTML = '<option value="">Chọn sự kiện</option>';

                if (data.data && data.data.length > 0) {
                    data.data.forEach(event => {
                        const option = document.createElement('option');
                        option.value = event.event_id;
                        option.textContent = event.name;
                        select.appendChild(option);
                    });
                } else {
                    // Nếu không có events available
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có sự kiện khả dụng';
                    option.disabled = true;
                    select.appendChild(option);
                }
            } catch (error) {
                console.error('Error loading events:', error);
                showError('Không thể tải danh sách sự kiện');
            }
        }

        // Load holidays
        async function loadHolidays() {
            try {
                const response = await fetch('{{ route('admin.holidays.data') }}');
                const data = await response.json();
                console.log('Loaded holidays:', data);

                const select = document.getElementById('holidayId');
                select.innerHTML = '<option value="">Chọn lễ hội</option>';

                if (data.data && data.data.length > 0) {
                    data.data.forEach(holiday => {
                        const option = document.createElement('option');
                        option.value = holiday.holiday_id;
                        option.textContent = holiday.name;
                        select.appendChild(option);
                    });
                } else {
                    // Nếu không có holidays available
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có lễ hội khả dụng';
                    option.disabled = true;
                    select.appendChild(option);
                }
            } catch (error) {
                console.error('Error loading holidays:', error);
                showError('Không thể tải danh sách lễ hội');
            }
        }

        // Load events for edit mode
        async function loadEventsForEdit(currentEventId = null) {
            try {
                const url = currentEventId ?
                    `{{ url('admin/events/data-for-edit') }}/${currentEventId}` :
                    '{{ route('admin.events.data') }}';

                const response = await fetch(url);
                const data = await response.json();

                const select = document.getElementById('eventId');
                select.innerHTML = '<option value="">Chọn sự kiện</option>';

                if (data.data && data.data.length > 0) {
                    data.data.forEach(event => {
                        const option = document.createElement('option');
                        option.value = event.event_id;
                        option.textContent = event.name;
                        select.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có sự kiện khả dụng';
                    option.disabled = true;
                    select.appendChild(option);
                }

                // Set selected value if provided
                if (currentEventId) {
                    select.value = currentEventId;
                }

                return Promise.resolve();
            } catch (error) {
                console.error('Error loading events for edit:', error);
                return Promise.reject(error);
            }
        }

        // Load holidays for edit mode
        async function loadHolidaysForEdit(currentHolidayId = null) {
            try {
                const url = currentHolidayId ?
                    `{{ url('admin/holidays/data-for-edit') }}/${currentHolidayId}` :
                    '{{ route('admin.holidays.data') }}';

                const response = await fetch(url);
                const data = await response.json();

                const select = document.getElementById('holidayId');
                select.innerHTML = '<option value="">Chọn lễ hội</option>';

                if (data.data && data.data.length > 0) {
                    data.data.forEach(holiday => {
                        const option = document.createElement('option');
                        option.value = holiday.holiday_id;
                        option.textContent = holiday.name;
                        if (currentHolidayId && holiday.holiday_id == currentHolidayId) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading holidays for edit:', error);
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
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng quy tắc giá</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.total_pricing_rules || 0}</div>
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
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
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
                            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
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
                    new Date(event.end_date).toLocaleDateString('vi-VN') : null;
                const timeDisplay = endDate ? `${startDate} - ${endDate}` : startDate;

                return `
                    <div class="flex items-center justify-between p-3 mb-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${typeClass}">
                                ${typeName}
                            </span>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">${event.name}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${timeDisplay}</div>
                            </div>
                        </div>
                        <div class="text-sm bg-green-500 text-white p-2 rounded-xl">
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

                const response = await fetch(`{{ route('admin.flexible-pricing.data') }}?${params}`);
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
                const ruleTypeClass = {
                    'weekend': 'bg-blue-100 text-blue-800',
                    'event': 'bg-green-100 text-green-800',
                    'holiday': 'bg-purple-100 text-purple-800',
                    'season': 'bg-orange-100 text-orange-800'
                };

                const ruleTypeName = {
                    'weekend': 'Cuối tuần',
                    'event': 'Sự kiện',
                    'holiday': 'Lễ hội',
                    'season': 'Mùa'
                };

                const statusClass = rule.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const statusText = rule.is_active ? 'Hoạt động' : 'Không hoạt động';

                // Format rule details based on type
                let ruleDetails = '';
                switch (rule.rule_type) {
                    case 'weekend':
                        if (rule.days_of_week) {
                            const days = JSON.parse(rule.days_of_week);
                            const dayNames = {
                                'Monday': 'T2',
                                'Tuesday': 'T3',
                                'Wednesday': 'T4',
                                'Thursday': 'T5',
                                'Friday': 'T6',
                                'Saturday': 'T7',
                                'Sunday': 'CN'
                            };
                            ruleDetails = days.map(day => dayNames[day] || day).join(', ');
                        }
                        break;
                    case 'event':
                        ruleDetails = rule.event_name || 'Sự kiện không xác định';
                        break;
                    case 'holiday':
                        ruleDetails = rule.holiday_name || 'Lễ hội không xác định';
                        break;
                    case 'season':
                        ruleDetails = rule.season_name || 'Mùa không xác định';
                        break;
                }

                // Format time period
                let timePeriod = '';
                if (rule.start_date || rule.end_date) {
                    const startDate = rule.start_date ? new Date(rule.start_date).toLocaleDateString('vi-VN') : '';
                    const endDate = rule.end_date ? new Date(rule.end_date).toLocaleDateString('vi-VN') : '';

                    if (startDate && endDate) {
                        timePeriod = `${startDate} - ${endDate}`;
                    } else if (startDate) {
                        timePeriod = `Từ ${startDate}`;
                    } else if (endDate) {
                        timePeriod = `Đến ${endDate}`;
                    }
                } else {
                    timePeriod = 'Vô thời hạn';
                }

                return `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="p-2 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${rule.rule_id}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${ruleTypeClass[rule.rule_type] || 'bg-gray-100 text-gray-800'}">
                                ${ruleTypeName[rule.rule_type] || rule.rule_type}
                            </span>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">${rule.room_type_name || 'Tất cả loại phòng'}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate" title="${ruleDetails}">
                                ${ruleDetails || '-'}
                            </div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">${timePeriod}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="text-sm font-medium ${rule.price_adjustment >= 0 ? 'text-green-600' : 'text-red-600'}">
                                ${rule.price_adjustment >= 0 ? '+' : ''}${rule.price_adjustment}%
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
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
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
                                        <button onclick="editPricingRule(${rule.rule_id}); closeDropdown(${rule.rule_id})"
                                            class="flex items-center w-full cursor-pointer px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                            role="menuitem">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Chỉnh sửa
                                        </button>

                                        <!-- Toggle Status -->
                                        <button onclick="togglePricingRuleStatus(${rule.rule_id}, ${!rule.is_active}); closeDropdown(${rule.rule_id})"
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
                                        <button onclick="deletePricingRule(${rule.rule_id}); closeDropdown(${rule.rule_id})"
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

        // Apply filters
        function applyFilters() {
            currentFilters = {
                rule_type: document.getElementById('filterRuleType').value,
                start_date: document.getElementById('filterStartDate').value,
                end_date: document.getElementById('filterEndDate').value,
                is_active: document.getElementById('filterStatus').value
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
            document.getElementById('filterRuleType').value = '';
            document.getElementById('filterStartDate').value = '';
            document.getElementById('filterEndDate').value = '';
            document.getElementById('filterStatus').value = '';
            currentFilters = {};
            loadData(1);
        }

        // Show pricing rule modal
        function showPricingRuleModal(id = null) {
            // Reset form
            elements.pricingRuleForm.reset();
            document.getElementById('pricingRuleId').value = '';

            if (id) {
                // Edit mode
                isPricingRuleEditMode = true;
                editingPricingRuleId = id;
                document.getElementById('pricingRuleModalTitle').textContent = 'Chỉnh sửa quy tắc giá';
                document.getElementById('pricingRuleSubmitText').textContent = 'Cập nhật';
                loadPricingRuleData(id);
            } else {
                // Add mode
                isPricingRuleEditMode = false;
                editingPricingRuleId = null;
                document.getElementById('pricingRuleModalTitle').textContent = 'Thêm quy tắc giá';
                document.getElementById('pricingRuleSubmitText').textContent = 'Thêm mới';
                handleRuleTypeChange(); // Initialize form state
            }

            elements.pricingRuleModal.classList.remove('hidden');
        }

        // Close pricing rule modal
        function closePricingRuleModal() {
            elements.pricingRuleModal.classList.add('hidden');
            elements.pricingRuleForm.reset();
        }

        // Show event/holiday modal
        function showEventHolidayModal(type = null, id = null) {
            // Reset form
            elements.eventHolidayForm.reset();
            document.getElementById('eventHolidayId').value = '';
            document.getElementById('eventHolidayType').value = '';

            if (id && type) {
                // Edit mode
                isEventHolidayEditMode = true;
                editingEventHolidayId = id;
                editingEventHolidayType = type;
                document.getElementById('eventHolidayModalTitle').textContent = 'Chỉnh sửa ' + (type === 'event' ?
                    'sự kiện' : 'lễ hội');
                document.getElementById('eventHolidaySubmitText').textContent = 'Cập nhật';
                document.getElementById('eventHolidayId').value = id;
                document.getElementById('eventHolidayType').value = type;
                loadEventHolidayData(type, id);
            } else {
                // Add mode
                isEventHolidayEditMode = false;
                editingEventHolidayId = null;
                editingEventHolidayType = null;
                document.getElementById('eventHolidayModalTitle').textContent = 'Thêm sự kiện/lễ hội';
                document.getElementById('eventHolidaySubmitText').textContent = 'Thêm mới';
            }

            elements.eventHolidayModal.classList.remove('hidden');
        }

        // Close event/holiday modal
        function closeEventHolidayModal() {
            elements.eventHolidayModal.classList.add('hidden');
            elements.eventHolidayForm.reset();
        }

        // Handle rule type change
        function handleRuleTypeChange() {
            const ruleType = elements.ruleType.value;

            // Hide all containers first
            elements.weekendDaysContainer.classList.add('hidden');
            elements.eventContainer.classList.add('hidden');
            elements.holidayContainer.classList.add('hidden');
            elements.seasonContainer.classList.add('hidden');
            elements.dateRangeContainer.classList.add('hidden');

            // Clear required attributes
            document.getElementById('eventId').required = false;
            document.getElementById('holidayId').required = false;
            document.getElementById('seasonName').required = false;
            document.getElementById('startDate').required = false;
            document.getElementById('endDate').required = false;

            // Show relevant containers based on rule type
            switch (ruleType) {
                case 'weekend':
                    elements.weekendDaysContainer.classList.remove('hidden');
                    elements.dateRangeContainer.classList.remove('hidden');
                    break;
                case 'event':
                    elements.eventContainer.classList.remove('hidden');
                    document.getElementById('eventId').required = true;
                    break;
                case 'holiday':
                    elements.holidayContainer.classList.remove('hidden');
                    document.getElementById('holidayId').required = true;
                    break;
                case 'season':
                    elements.seasonContainer.classList.remove('hidden');
                    elements.dateRangeContainer.classList.remove('hidden');
                    document.getElementById('seasonName').required = true;
                    document.getElementById('startDate').required = true;
                    document.getElementById('endDate').required = true;
                    break;
            }
        }

        // Load pricing rule data for editing
        async function loadPricingRuleData(id) {
            try {
                const response = await fetch(`{{ route('admin.flexible-pricing.show', ':id') }}`.replace(':id', id));
                const result = await response.json();

                if (response.ok && result.success) {
                    const rule = result.data;

                    // Fill form
                    document.getElementById('pricingRuleId').value = rule.rule_id;
                    document.getElementById('ruleType').value = rule.rule_type;
                    document.getElementById('roomTypeId').value = rule.room_type_id || '';
                    document.getElementById('priceAdjustment').value = rule.price_adjustment;
                    document.getElementById('startDate').value = rule.start_date || '';
                    document.getElementById('endDate').value = rule.end_date || '';
                    document.getElementById('isActive').checked = rule.is_active;

                    // Trigger rule type change first để show/hide các containers
                    handleRuleTypeChange();

                    // Wait một chút để DOM update xong
                    setTimeout(() => {
                        // Handle specific rule type data
                        switch (rule.rule_type) {
                            case 'weekend':
                                if (rule.days_of_week) {
                                    const selectedDays = JSON.parse(rule.days_of_week);
                                    selectedDays.forEach(day => {
                                        const checkbox = document.querySelector(
                                            `input[name="days_of_week[]"][value="${day}"]`);
                                        if (checkbox) checkbox.checked = true;
                                    });
                                }
                                break;
                            case 'event':
                                // Load events for edit mode với current event
                                loadEventsForEdit(rule.event_id).then(() => {
                                    document.getElementById('eventId').value = rule.event_id || '';
                                });
                                break;
                            case 'holiday':
                                // Load holidays for edit mode với current holiday
                                loadHolidaysForEdit(rule.holiday_id).then(() => {
                                    document.getElementById('holidayId').value = rule.holiday_id || '';
                                });
                                break;
                            case 'season':
                                document.getElementById('seasonName').value = rule.season_name || '';
                                break;
                        }
                    }, 100);

                } else {
                    showError('Không thể tải dữ liệu: ' + (result.message || 'Lỗi không xác định'));
                }
            } catch (error) {
                console.error('Error loading pricing rule data:', error);
                showError('Có lỗi xảy ra khi tải dữ liệu');
            }
        }

        // Load event/holiday data for editing
        async function loadEventHolidayData(type, id) {
            try {
                const response = await fetch(`{{ url('admin/event-festival-management') }}/${type}/${id}`);
                const result = await response.json();

                if (response.ok && result.success) {
                    const item = result.data;

                    // Fill form
                    document.getElementById('type').value = type;
                    document.getElementById('eventHolidayName').value = item.name || '';
                    document.getElementById('eventHolidayDescription').value = item.description || '';
                    document.getElementById('eventHolidayIsActive').value = item.is_active ? '1' : '0';
                    document.getElementById('eventHolidayStartDate').value = item.start_date || '';
                    document.getElementById('eventHolidayEndDate').value = item.end_date || '';
                } else {
                    showError('Không thể tải dữ liệu: ' + (result.message || 'Lỗi không xác định'));
                }
            } catch (error) {
                console.error('Error loading event/holiday data:', error);
                showError('Có lỗi xảy ra khi tải dữ liệu');
            }
        }

        // Handle pricing rule form submit
        async function handlePricingRuleSubmit(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('pricingRuleSubmitBtn');
            const submitText = document.getElementById('pricingRuleSubmitText');
            const submitLoading = document.getElementById('pricingRuleSubmitLoading');

            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');

            try {
                const formData = new FormData(elements.pricingRuleForm);
                const ruleType = formData.get('rule_type');

                // Build request data
                const requestData = {
                    rule_type: ruleType,
                    room_type_id: formData.get('room_type_id') || null,
                    price_adjustment: parseFloat(formData.get('price_adjustment')),
                    start_date: formData.get('start_date') || null,
                    end_date: formData.get('end_date') || null,
                    is_active: document.getElementById('isActive').checked
                };

                // Add rule-specific data
                switch (ruleType) {
                    case 'weekend':
                        const selectedDays = [];
                        const dayCheckboxes = document.querySelectorAll('input[name="days_of_week[]"]:checked');
                        dayCheckboxes.forEach(checkbox => selectedDays.push(checkbox.value));

                        if (selectedDays.length === 0) {
                            throw new Error('Vui lòng chọn ít nhất một ngày cuối tuần');
                        }
                        requestData.days_of_week = selectedDays;
                        break;
                    case 'event':
                        requestData.event_id = formData.get('event_id');
                        if (!requestData.event_id) {
                            throw new Error('Vui lòng chọn sự kiện');
                        }
                        break;
                    case 'holiday':
                        requestData.holiday_id = formData.get('holiday_id');
                        if (!requestData.holiday_id) {
                            throw new Error('Vui lòng chọn lễ hội');
                        }
                        break;
                    case 'season':
                        requestData.season_name = formData.get('season_name');
                        if (!requestData.season_name) {
                            throw new Error('Vui lòng nhập tên mùa');
                        }
                        if (!requestData.start_date || !requestData.end_date) {
                            throw new Error('Vui lòng chọn ngày bắt đầu và kết thúc cho mùa');
                        }
                        break;
                }

                const url = isPricingRuleEditMode ?
                    `{{ route('admin.flexible-pricing.update', ':id') }}`.replace(':id', editingPricingRuleId) :
                    '{{ route('admin.flexible-pricing.store') }}';

                const method = isPricingRuleEditMode ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    closePricingRuleModal();
                    showSuccess(result.message || 'Thao tác thành công!');
                    loadData(currentPage);
                    loadStatistics();
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
                console.error('Error submitting pricing rule form:', error);
                showError('Có lỗi xảy ra khi gửi dữ liệu: ' + error.message);
            } finally {
                // Hide loading
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        // Handle event/holiday form submit
        async function handleEventHolidaySubmit(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('eventHolidaySubmitBtn');
            const submitText = document.getElementById('eventHolidaySubmitText');
            const submitLoading = document.getElementById('eventHolidaySubmitLoading');

            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');

            try {
                const formData = new FormData(elements.eventHolidayForm);

                let url, method;
                if (isEventHolidayEditMode && editingEventHolidayId && editingEventHolidayType) {
                    // Update
                    url =
                        `{{ url('admin/event-festival-management') }}/${editingEventHolidayType}/${editingEventHolidayId}`;
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
                    closeEventHolidayModal();
                    showSuccess(result.message || 'Thao tác thành công!');
                    loadEvents();
                    loadHolidays();
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
                console.error('Error submitting event/holiday form:', error);
                showError('Có lỗi xảy ra khi gửi dữ liệu');
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

        // Edit pricing rule
        function editPricingRule(id) {
            showPricingRuleModal(id);
        }

        // Toggle pricing rule status
        async function togglePricingRuleStatus(id, newStatus) {
            try {
                const response = await fetch(`{{ route('admin.flexible-pricing.toggle-status', ':id') }}`.replace(
                    ':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        is_active: newStatus
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess(result.message || 'Cập nhật trạng thái thành công!');
                    loadData(currentPage);
                    loadStatistics();
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi cập nhật trạng thái');
                }
            } catch (error) {
                console.error('Error toggling pricing rule status:', error);
                showError('Có lỗi xảy ra khi cập nhật trạng thái');
            }
        }

        // Delete pricing rule
        async function deletePricingRule(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa quy tắc giá này?')) {
                return;
            }

            try {
                const response = await fetch(`{{ route('admin.flexible-pricing.destroy', ':id') }}`.replace(':id',
                id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess(result.message || 'Xóa thành công!');
                    loadData(currentPage);
                    loadStatistics();
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi xóa');
                }
            } catch (error) {
                console.error('Error deleting pricing rule:', error);
                showError('Có lỗi xảy ra khi xóa dữ liệu');
            }
        }

        // Edit event/holiday
        function editEventHoliday(type, id) {
            showEventHolidayModal(type, id);
        }

        // Delete event/holiday
        async function deleteEventHoliday(type, id, name) {
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
                    loadEvents();
                    loadHolidays();
                    loadStatistics();
                    loadUpcomingEvents();
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi xóa');
                }
            } catch (error) {
                console.error('Error deleting event/holiday:', error);
                showError('Có lỗi xảy ra khi xóa dữ liệu');
            }
        }

        // Export data
        async function exportData() {
            try {
                const params = new URLSearchParams(currentFilters);
                window.open(`{{ route('admin.flexible-pricing.export') }}?${params}`, '_blank');
            } catch (error) {
                console.error('Error exporting data:', error);
                showError('Có lỗi xảy ra khi xuất dữ liệu');
            }
        }

        // Show success notification
        function showSuccess(message) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.dynamic-notification');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className =
                'dynamic-notification fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md z-50';
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
            notification.className =
                'dynamic-notification fixed top-4 right-4 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md z-50';
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
                closePricingRuleModal();
                closeEventHolidayModal();
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
    </style>

</x-app-layout>
