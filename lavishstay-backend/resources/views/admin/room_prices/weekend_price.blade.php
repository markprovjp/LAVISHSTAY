<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý giá cuối tuần</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Cấu hình ngày cuối tuần và quản lý quy tắc giá cuối tuần</p>
            </div>
        </div>

       

        <!-- Weekend Pricing Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Quy tắc giá cuối tuần</h2>
                    <button onclick="showModal()" 
                        class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 w-4 h-4" viewBox="0 0 16 16" width="16" height="16">
                            <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        <span class="ml-2">Thêm quy tắc cuối tuần</span>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full dark:text-gray-300">
                    <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Loại phòng</th>
                            <th class="px-6 py-4 text-left">Ngày cuối tuần</th>
                            <th class="px-6 py-4 text-left">Ngày bắt đầu</th>
                            <th class="px-6 py-4 text-left">Ngày kết thúc</th>
                            <th class="px-6 py-4 text-left">Điều chỉnh giá (%)</th>
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
        <div id="weekendRuleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 dark:text-gray-100">Thêm quy tắc cuối tuần</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="weekendRuleForm" onsubmit="handleSubmit(event)">
                    <input type="hidden" id="ruleId" name="rule_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Loại phòng
                            </label>
                            <select id="roomTypeId" name="room_type_id" 
                                class="form-select w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Áp dụng cho tất cả loại phòng</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Điều chỉnh giá (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="priceAdjustment" name="price_adjustment" required step="0.01" min="-100" max="1000"
                                class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Nhập tỷ lệ điều chỉnh giá (VD: 20 = tăng 20%, -10 = giảm 10%)">
                            <p class="text-xs text-gray-500 mt-1">Số dương để tăng giá, số âm để giảm giá</p>
                            <div id="pricePreview" class="text-xs text-blue-600 mt-1"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ngày cuối tuần áp dụng <span class="text-red-500">*</span>
                        </label>
                        <div id="weekendDaysSelection" class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <!-- Weekend days checkboxes will be loaded here -->
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ngày bắt đầu
                            </label>
                            <input type="date" id="startDate" name="start_date"
                                class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">Để trống nếu áp dụng vô thời hạn</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ngày kết thúc
                            </label>
                            <input type="date" id="endDate" name="end_date"
                                class="form-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">Để trống nếu áp dụng vô thời hạn</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="isActive" name="is_active" checked
                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="isActive" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Kích hoạt quy tắc
                            </label>
                        </div>
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

        // Debug function
        function debugLog(message, data = null) {
            console.log(`[Weekend Price Debug] ${message}`, data);
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            debugLog('Initializing page...');
            loadWeekendDays();
            loadRoomTypes();
            loadWeekendDaysForModal();
            loadData(1);
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDate').min = today;
            document.getElementById('endDate').min = today;
            
            // Update end date minimum when start date changes
            document.getElementById('startDate').addEventListener('change', function() {
                if (this.value) {
                    document.getElementById('endDate').min = this.value;
                }
            });
        });

        // Load weekend days configuration
        async function loadWeekendDays() {
            try {
                debugLog('Loading weekend days...');
                const response = await fetch('{{ route("admin.weekend-price.weekend-days") }}');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const weekendDays = await response.json();
                debugLog('Weekend days loaded:', weekendDays);
                
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
                debugLog('Error loading weekend days:', error);
                showNotification('Có lỗi xảy ra khi tải cấu hình ngày cuối tuần: ' + error.message, 'error');
            }
        }

        // Load room types for dropdown
        async function loadRoomTypes() {
            try {
                debugLog('Loading room types...');
                const response = await fetch('{{ route("admin.weekend-price.room-types") }}');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const roomTypes = await response.json();
                debugLog('Room types loaded:', roomTypes);
                
                const select = document.getElementById('roomTypeId');
                select.innerHTML = '<option value="">Áp dụng cho tất cả loại phòng</option>';
                
                roomTypes.forEach(roomType => {
                    const option = document.createElement('option');
                    option.value = roomType.room_type_id;
                    option.textContent = roomType.name;
                    select.appendChild(option);
                });
                
            } catch (error) {
                debugLog('Error loading room types:', error);
                showNotification('Có lỗi xảy ra khi tải danh sách loại phòng: ' + error.message, 'error');
            }
        }

        // Load weekend days for modal selection
        async function loadWeekendDaysForModal() {
            try {
                debugLog('Loading weekend days for modal...');
                const container = document.getElementById('weekendDaysSelection');
                container.innerHTML = '';
                
                const dayNames = [
                    { key: 'Monday', name: 'Thứ Hai' },
                    { key: 'Tuesday', name: 'Thứ Ba' },
                    { key: 'Wednesday', name: 'Thứ Tư' },
                    { key: 'Thursday', name: 'Thứ Năm' },
                    { key: 'Friday', name: 'Thứ Sáu' },
                    { key: 'Saturday', name: 'Thứ Bảy' },
                    { key: 'Sunday', name: 'Chủ Nhật' }
                ];
                
                dayNames.forEach(day => {
                    const dayDiv = document.createElement('div');
                    dayDiv.className = 'flex items-center p-2 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700';
                    
                    dayDiv.innerHTML = `
                        <input type="checkbox" 
                            id="modal_day_${day.key}" 
                            name="days_of_week[]"
                            value="${day.key}"
                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="modal_day_${day.key}" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                            ${day.name}
                        </label>
                    `;
                    
                    container.appendChild(dayDiv);
                });
                
            } catch (error) {
                debugLog('Error loading weekend days for modal:', error);
            }
        }

        // Load weekend pricing rules data
        async function loadData(page = 1) {
            try {
                debugLog('Loading data for page:', page);
                const response = await fetch(`{{ route("admin.weekend-price.data") }}?page=${page}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                debugLog('Data loaded:', data);
                
                renderTable(data.data);
                renderPagination(data);
                
            } catch (error) {
                debugLog('Error loading data:', error);
                showNotification('Có lỗi xảy ra khi tải dữ liệu: ' + error.message, 'error');
            }
        }

        // Show modal
        function showModal() {
            debugLog('Showing modal...');
            isEditMode = false;
            editingId = null;
            document.getElementById('modalTitle').textContent = 'Thêm quy tắc cuối tuần';
            document.getElementById('submitText').textContent = 'Thêm mới';
            document.getElementById('weekendRuleForm').reset();
            
            // Uncheck all days
            const dayCheckboxes = document.querySelectorAll('#weekendDaysSelection input[type="checkbox"]');
            dayCheckboxes.forEach(checkbox => checkbox.checked = false);
            
            document.getElementById('weekendRuleModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            debugLog('Closing modal...');
            document.getElementById('weekendRuleModal').classList.add('hidden');
            document.getElementById('weekendRuleForm').reset();
        }

        // Handle form submit
        async function handleSubmit(event) {
            event.preventDefault();
            debugLog('Form submitted');
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            
            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                // Get form data
                const formData = new FormData(event.target);
                
                // Get selected days of week
                const selectedDays = [];
                const dayCheckboxes = document.querySelectorAll('#weekendDaysSelection input[type="checkbox"]:checked');
                dayCheckboxes.forEach(checkbox => {
                    selectedDays.push(checkbox.value);
                });
                
                if (selectedDays.length === 0) {
                    throw new Error('Vui lòng chọn ít nhất một ngày cuối tuần');
                }
                
                // Build request data
                const requestData = {
                    room_type_id: formData.get('room_type_id') || null,
                    price_adjustment: parseFloat(formData.get('price_adjustment')),
                    days_of_week: selectedDays,
                    start_date: formData.get('start_date') || null,
                    end_date: formData.get('end_date') || null,
                    is_active: document.getElementById('isActive').checked
                };
                
                debugLog('Request data:', requestData);
                
                const url = isEditMode 
                    ? `{{ route("admin.weekend-price.update", ":id") }}`.replace(':id', editingId)
                    : '{{ route("admin.weekend-price.store") }}';
                
                const method = isEditMode ? 'PUT' : 'POST';
                
                debugLog('Making request to:', url, 'with method:', method);
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });
                
                debugLog('Response status:', response.status);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    debugLog('Error response text:', errorText);
                    throw new Error(`HTTP error! status: ${response.status}, response: ${errorText}`);
                }
                
                const result = await response.json();
                debugLog('Response result:', result);
                
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
                debugLog('Error submitting form:', error);
                showNotification('Có lỗi xảy ra khi gửi dữ liệu: ' + error.message, 'error');
            } finally {
                // Hide loading
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                                submitLoading.classList.add('hidden');
            }
        }

                // Render table
        function renderTable(items) {
            const tbody = document.getElementById('weekendPricingTableBody');
            
            console.log('Rendering items:', items); // Debug log
            
            if (!items || items.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-lg font-medium">Chưa có dữ liệu</p>
                                <p class="text-sm">Nhấn "Thêm giá cuối tuần" để bắt đầu</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = items.map(rule => {  // Đổi từ item thành rule
                console.log('Processing rule:', rule); // Debug log
                
                const now = new Date().toISOString().split('T')[0];
                let status = '';
                let statusClass = '';
                
                if (!rule.is_active) {
                    status = 'Tạm dừng';
                    statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                } else if (rule.start_date && now < rule.start_date) {
                    status = 'Sắp áp dụng';
                    statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400';
                } else if (rule.end_date && now > rule.end_date) {
                    status = 'Đã hết hạn';
                    statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400';
                } else {
                    status = 'Đang áp dụng';
                    statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
                }
                
                // Format days of week
                let daysOfWeek = '';
                if (rule.days_of_week) {
                    const days = Array.isArray(rule.days_of_week) ? 
                        rule.days_of_week : 
                        (typeof rule.days_of_week === 'string' ? JSON.parse(rule.days_of_week) : []);
                    
                    const dayNames = {
                        'Monday': 'T2',
                        'Tuesday': 'T3',
                        'Wednesday': 'T4',
                        'Thursday': 'T5',
                        'Friday': 'T6',
                        'Saturday': 'T7',
                        'Sunday': 'CN'
                    };
                    
                    daysOfWeek = days.map(day => dayNames[day] || day).join(', ');
                }
                
                return `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${rule.rule_id || rule.pricing_id}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                            ${rule.room_type_name || rule.room_name || 'Tất cả loại phòng'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${daysOfWeek || 'N/A'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${rule.start_date ? formatDate(rule.start_date) : 'Không giới hạn'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${rule.end_date ? formatDate(rule.end_date) : 'Không giới hạn'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                            ${rule.price_adjustment !== undefined ? 
                                `<span class="${rule.price_adjustment >= 0 ? 'text-green-600' : 'text-red-600'}">
                                    ${rule.price_adjustment >= 0 ? '+' : ''}${rule.price_adjustment}%
                                </span>` :
                                (rule.price_vnd ? formatCurrency(rule.price_vnd) : 'N/A')
                            }
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                ${status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                
                                <button onclick="deleteRule(${rule.rule_id})" 
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"  width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Xóa
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }


        // Edit rule
        async function editRule(id) {
            try {
                debugLog('Editing rule:', id);
                const response = await fetch(`{{ route("admin.weekend-price.show", ":id") }}`.replace(':id', id));
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                debugLog('Edit rule response:', result);
                
                if (result.success) {
                    const rule = result.data;
                    isEditMode = true;
                    editingId = id;
                    
                    document.getElementById('modalTitle').textContent = 'Cập nhật quy tắc cuối tuần';
                    document.getElementById('submitText').textContent = 'Cập nhật';
                    document.getElementById('ruleId').value = rule.rule_id;
                    document.getElementById('roomTypeId').value = rule.room_type_id || '';
                    document.getElementById('priceAdjustment').value = rule.price_adjustment;
                    document.getElementById('startDate').value = rule.start_date || '';
                    document.getElementById('endDate').value = rule.end_date || '';
                    document.getElementById('isActive').checked = rule.is_active;
                    
                    // Set selected days
                    const dayCheckboxes = document.querySelectorAll('#weekendDaysSelection input[type="checkbox"]');
                    dayCheckboxes.forEach(checkbox => checkbox.checked = false);
                    
                    if (rule.days_of_week) {
                        const selectedDays = typeof rule.days_of_week === 'string' ? 
                            JSON.parse(rule.days_of_week) : rule.days_of_week;
                        
                        selectedDays.forEach(day => {
                            const checkbox = document.querySelector(`#weekendDaysSelection input[value="${day}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }
                    
                    document.getElementById('weekendRuleModal').classList.remove('hidden');
                } else {
                    showNotification(result.message || 'Không thể tải dữ liệu', 'error');
                }
                
            } catch (error) {
                debugLog('Error loading rule data:', error);
                showNotification('Có lỗi xảy ra khi tải dữ liệu: ' + error.message, 'error');
            }
        }

        // Toggle rule status
        async function toggleRuleStatus(id, newStatus) {
            try {
                debugLog('Toggling rule status:', id, newStatus);
                const response = await fetch(`{{ route("admin.weekend-price.toggle-status", ":id") }}`.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ is_active: newStatus })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message, 'success');
                    loadData(currentPage);
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra', 'error');
                }
                
            } catch (error) {
                debugLog('Error toggling rule status:', error);
                showNotification('Có lỗi xảy ra khi thay đổi trạng thái: ' + error.message, 'error');
            }
        }

        // Delete rule
        async function deleteRule(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa quy tắc cuối tuần này?')) {
                return;
            }
            
            try {
                debugLog('Deleting rule:', id);
                const response = await fetch(`{{ route("admin.weekend-price.destroy", ":id") }}`.replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message, 'success');
                    loadData(currentPage);
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra', 'error');
                }
                
            } catch (error) {
                debugLog('Error deleting rule:', error);
                showNotification('Có lỗi xảy ra khi xóa dữ liệu: ' + error.message, 'error');
            }
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
        document.getElementById('weekendRuleModal').addEventListener('click', function(e) {
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
