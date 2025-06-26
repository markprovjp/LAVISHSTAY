<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý giá cuối tuần</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Cấu hình ngày cuối tuần và quản lý giá phòng cuối tuần</p>
            </div>
        </div>

        <!-- Weekend Days Configuration -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                                       <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Cấu hình ngày cuối tuần</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Chọn những ngày nào được coi là cuối tuần</p>
                </div>
                <button onclick="saveWeekendDays()" 
                    class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    <span id="saveWeekendText">Lưu cấu hình</span>
                    <div id="saveWeekendLoading" class="hidden">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
            
            <div id="weekendDaysContainer" class="flex justify-between items-center gap-4">
                <!-- Weekend days will be loaded here -->
            </div>
        </div>

        <!-- Weekend Pricing Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Giá phòng cuối tuần</h2>
                    <button onclick="showModal()" 
                        class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                            <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="ml-2">Thêm giá cuối tuần</span>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full dark:text-gray-300">
                    <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Phòng</th>
                            <th class="px-6 py-4 text-left">Ngày bắt đầu</th>
                            <th class="px-6 py-4 text-left">Ngày kết thúc</th>
                            <th class="px-6 py-4 text-left">Giá (VND)</th>
                            <th class="px-6 py-4 text-left">Lý do</th>
                            <th class="px-6 py-4 text-left">Trạng thái</th>
                            <th class="px-6 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="weekendPricingTableBody" class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="weekendPricingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 dark:text-gray-100">Thêm giá cuối tuần</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="weekendPricingForm" onsubmit="handleSubmit(event)">
                <input type="hidden" id="pricingId" name="pricing_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phòng <span class="text-red-500">*</span>
                        </label>
                        <select id="roomId" name="room_id" required 
                            class="form-select w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Chọn phòng</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Giá cuối tuần (VND) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="priceVnd" name="price_vnd" required min="0" step="1000"
                            class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Nhập giá phòng cuối tuần">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ngày bắt đầu <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="startDate" name="start_date" required
                            class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ngày kết thúc <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="endDate" name="end_date" required
                            class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do
                    </label>
                    <textarea id="reason" name="reason" rows="3"
                        class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Nhập lý do áp dụng giá cuối tuần (tùy chọn)"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Hủy
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">Thêm mới</span>
                        <div id="submitLoading" class="hidden flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
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

    <script>
        let currentPage = 1;
        let isEditMode = false;
        let editingId = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadWeekendDays();
            loadRooms();
            loadData(1);
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDate').min = today;
            document.getElementById('endDate').min = today;
            
            // Update end date minimum when start date changes
            document.getElementById('startDate').addEventListener('change', function() {
                document.getElementById('endDate').min = this.value;
            });
        });

        // Load weekend days configuration
        async function loadWeekendDays() {
            try {
                const response = await fetch('{{ route("admin.weekend-price.weekend-days") }}');
                const weekendDays = await response.json();
                
                const container = document.getElementById('weekendDaysContainer');
                container.innerHTML = '';
                
                const dayNames = {
                    'Monday': 'Thứ Hai',
                    'Tuesday': 'Thứ Ba', 
                    'Wednesday': 'Thứ Tư',
                    'Thursday': 'Thứ Năm',
                    'Friday': 'Thứ Sáu',
                    'Saturday': 'Thứ Bảy',
                    'Sunday': 'Chủ Nhật'
                };
                
                weekendDays.forEach(day => {
                    const dayCard = document.createElement('div');
                    dayCard.className = `p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 ${
                        day.is_active 
                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' 
                            : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'
                    }`;
                    
                    dayCard.innerHTML = `
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-800 dark:text-gray-100 mb-2">
                                ${dayNames[day.day_of_week]}
                            </div>
                            <div class="flex justify-center">
                                <input type="checkbox" 
                                    id="day_${day.id}" 
                                    value="${day.day_of_week}"
                                    ${day.is_active ? 'checked' : ''}
                                                                        class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                        </div>
                    `;
                    
                    // Add click handler for the card
                    dayCard.addEventListener('click', function(e) {
                        if (e.target.type !== 'checkbox') {
                            const checkbox = dayCard.querySelector('input[type="checkbox"]');
                            checkbox.checked = !checkbox.checked;
                            updateDayCardStyle(dayCard, checkbox.checked);
                        } else {
                            updateDayCardStyle(dayCard, e.target.checked);
                        }
                    });
                    
                    container.appendChild(dayCard);
                });
                
            } catch (error) {
                console.error('Error loading weekend days:', error);
                showNotification('Có lỗi xảy ra khi tải cấu hình ngày cuối tuần', 'error');
            }
        }

        // Update day card style
        function updateDayCardStyle(card, isChecked) {
            if (isChecked) {
                card.className = card.className.replace(
                    'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500',
                    'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                );
            } else {
                card.className = card.className.replace(
                    'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20',
                    'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'
                );
            }
        }

        // Save weekend days configuration
        async function saveWeekendDays() {
            const saveBtn = document.getElementById('saveWeekendText');
            const loading = document.getElementById('saveWeekendLoading');
            
            // Show loading
            saveBtn.classList.add('hidden');
            loading.classList.remove('hidden');
            
            try {
                const checkboxes = document.querySelectorAll('#weekendDaysContainer input[type="checkbox"]:checked');
                const selectedDays = Array.from(checkboxes).map(cb => cb.value);
                
                if (selectedDays.length === 0) {
                    showNotification('Vui lòng chọn ít nhất một ngày cuối tuần', 'error');
                    return;
                }
                
                const response = await fetch('{{ route("admin.weekend-price.update-weekend-days") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        weekend_days: selectedDays
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message, 'success');
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra', 'error');
                }
                
            } catch (error) {
                console.error('Error saving weekend days:', error);
                showNotification('Có lỗi xảy ra khi lưu cấu hình', 'error');
            } finally {
                // Hide loading
                saveBtn.classList.remove('hidden');
                loading.classList.add('hidden');
            }
        }

        // Load rooms for dropdown
        async function loadRooms() {
            try {
                const response = await fetch('{{ route("admin.weekend-price.rooms") }}');
                const rooms = await response.json();
                
                const select = document.getElementById('roomId');
                select.innerHTML = '<option value="">Chọn phòng</option>';
                
                rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.room_id;
                    option.textContent = room.name;
                    select.appendChild(option);
                });
                
            } catch (error) {
                console.error('Error loading rooms:', error);
                showNotification('Có lỗi xảy ra khi tải danh sách phòng', 'error');
            }
        }

        // Load weekend pricing data
        async function loadData(page = 1) {
            try {
                const response = await fetch(`{{ route("admin.weekend-price.data") }}?page=${page}`);
                const data = await response.json();
                
                renderTable(data.data);
                renderPagination(data);
                
            } catch (error) {
                console.error('Error loading data:', error);
                showNotification('Có lỗi xảy ra khi tải dữ liệu', 'error');
            }
        }

        // Render table
        function renderTable(pricings) {
            const tbody = document.getElementById('weekendPricingTableBody');
            
            if (pricings.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-lg font-medium">Chưa có giá cuối tuần nào</p>
                                <p class="text-sm">Nhấn "Thêm giá cuối tuần" để bắt đầu</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = pricings.map(pricing => {
                const now = new Date().toISOString().split('T')[0];
                let status = '';
                let statusClass = '';
                
                if (now < pricing.start_date) {
                    status = 'Sắp áp dụng';
                    statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400';
                } else if (now >= pricing.start_date && now <= pricing.end_date) {
                    status = 'Đang áp dụng';
                    statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
                } else {
                    status = 'Đã hết hạn';
                    statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                }
                
                return `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${pricing.pricing_id}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                            ${pricing.room_name || 'N/A'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${formatDate(pricing.start_date)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${formatDate(pricing.end_date)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                            ${formatCurrency(pricing.price_vnd)}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                            ${pricing.reason || '-'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                ${status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="editPricing(${pricing.pricing_id})" 
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="deletePricing(${pricing.pricing_id})" 
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
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
            
            let paginationHTML = '<div class="flex items-center justify-between">';
            
            // Info
            paginationHTML += `
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Hiển thị ${data.from || 0} đến ${data.to || 0} trong tổng số ${data.total} kết quả
                </div>
            `;
            
            // Pagination buttons
            paginationHTML += '<div class="flex items-center space-x-2">';
            
            // Previous button
            if (data.current_page > 1) {
                paginationHTML += `
                    <button onclick="loadData(${data.current_page - 1})" 
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700">
                        Trước
                    </button>
                `;
            }
            
            // Page numbers
            for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
                const isActive = i === data.current_page;
                paginationHTML += `
                    <button onclick="loadData(${i})" 
                        class="px-3 py-2 text-sm font-medium ${isActive 
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
                    <button onclick="loadData(${data.current_page + 1})" 
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700">
                        Sau
                    </button>
                `;
            }
            
            paginationHTML += '</div></div>';
            container.innerHTML = paginationHTML;
        }

        // Show modal
        function showModal() {
            isEditMode = false;
            editingId = null;
            document.getElementById('modalTitle').textContent = 'Thêm giá cuối tuần';
            document.getElementById('submitText').textContent = 'Thêm mới';
            document.getElementById('weekendPricingForm').reset();
            document.getElementById('weekendPricingModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('weekendPricingModal').classList.add('hidden');
            document.getElementById('weekendPricingForm').reset();
        }

        // Edit pricing
        async function editPricing(id) {
            try {
                const response = await fetch(`{{ route("admin.weekend-price.show", ":id") }}`.replace(':id', id));
                const result = await response.json();
                
                if (result.success) {
                    const pricing = result.data;
                                        isEditMode = true;
                    editingId = id;
                    
                    document.getElementById('modalTitle').textContent = 'Cập nhật giá cuối tuần';
                    document.getElementById('submitText').textContent = 'Cập nhật';
                    document.getElementById('pricingId').value = pricing.pricing_id;
                    document.getElementById('roomId').value = pricing.room_id;
                    document.getElementById('startDate').value = pricing.start_date;
                    document.getElementById('endDate').value = pricing.end_date;
                    document.getElementById('priceVnd').value = pricing.price_vnd;
                    document.getElementById('reason').value = pricing.reason || '';
                    
                    document.getElementById('weekendPricingModal').classList.remove('hidden');
                } else {
                    showNotification(result.message || 'Không thể tải dữ liệu', 'error');
                }
                
            } catch (error) {
                console.error('Error loading pricing data:', error);
                showNotification('Có lỗi xảy ra khi tải dữ liệu', 'error');
            }
        }

        // Delete pricing
        async function deletePricing(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa giá cuối tuần này?')) {
                return;
            }
            
            try {
                const response = await fetch(`{{ route("admin.weekend-price.destroy", ":id") }}`.replace(':id', id), {
                    method: 'DELETE',
                    headers: {
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
                console.error('Error deleting pricing:', error);
                showNotification('Có lỗi xảy ra khi xóa dữ liệu', 'error');
            }
        }

        // Handle form submit
        async function handleSubmit(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            
            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                const formData = new FormData(event.target);
                const data = Object.fromEntries(formData.entries());
                
                const url = isEditMode 
                    ? `{{ route("admin.weekend-price.update", ":id") }}`.replace(':id', editingId)
                    : '{{ route("admin.weekend-price.store") }}';
                
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
                } else {
                    if (result.errors) {
                        // Show validation errors
                        let errorMessage = 'Vui lòng kiểm tra lại thông tin:\n';
                        Object.values(result.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += '• ' + error + '\n';
                            });
                        });
                        showNotification(errorMessage, 'error');
                    } else {
                        showNotification(result.message || 'Có lỗi xảy ra', 'error');
                    }
                }
                
            } catch (error) {
                console.error('Error submitting form:', error);
                showNotification('Có lỗi xảy ra khi gửi dữ liệu', 'error');
            } finally {
                // Hide loading
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        // Utility functions
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN');
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());
            
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-out ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${type === 'success' ? 
                            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
                            type === 'error' ?
                            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
                            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                        }
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium whitespace-pre-line">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Close modal when clicking outside
        document.getElementById('weekendPricingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</x-app-layout>

                    

