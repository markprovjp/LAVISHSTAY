<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Máy tính Giá Phòng</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Tính toán giá phòng theo các quy tắc định giá</p>
            </div>
            
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.pricing.index') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M7.3 8.7c-.4-.4-.4-1 0-1.4l7-7c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4L9.4 8l6.3 6.3c.4.4.4 1 0 1.4-.4.4-1 .4-1.4 0l-7-7z"/>
                    </svg>
                    <span class="max-xs:sr-only">Quay lại</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Calculator Form -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Thông tin tính toán</h3>
                    
                    <form id="calculatorForm" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Loại phòng <span class="text-red-500">*</span>
                            </label>
                            <select id="roomTypeId" name="room_type_id" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Chọn loại phòng</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ngày <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="calculationDate" name="date" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Giá cơ bản (VND)
                            </label>
                            <input type="number" id="basePrice" name="base_price" step="1000" min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Để trống để sử dụng giá mặc định">
                            <p class="text-xs text-gray-500 mt-1">Nếu để trống, sẽ sử dụng giá cơ bản của loại phòng</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tỷ lệ lấp đầy giả định (%)
                            </label>
                            <input type="number" id="occupancyRate" name="occupancy_rate" step="0.01" min="0" max="100"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Để trống để sử dụng tỷ lệ thực tế">
                            <p class="text-xs text-gray-500 mt-1">Nếu để trống, sẽ sử dụng tỷ lệ lấp đầy thực tế</p>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="showDetails" name="show_details" checked
                                class="form-checkbox h-4 w-4 text-violet-600">
                            <label for="showDetails" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Hiển thị chi tiết quy tắc áp dụng
                            </label>
                        </div>

                        <button type="submit" id="calculateBtn"
                            class="w-full btn bg-indigo-500 hover:bg-indigo-600 text-white disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="calculateText">Tính toán giá</span>
                            {{-- <svg id="calculateLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7
.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg> --}}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Results Panel -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Kết quả tính toán</h3>
                    
                    <div id="calculationResults">
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <p>Nhập thông tin và nhấn "Tính toán giá" để xem kết quả</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batch Calculator -->
        <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tính toán hàng loạt</h3>
                    <button id="toggleBatchCalculator" class="btn bg-blue-500 hover:bg-blue-600 text-white text-sm">
                        Mở rộng
                    </button>
                </div>
                
                <div id="batchCalculatorSection" class="hidden">
                    <form id="batchCalculatorForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Từ ngày <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="batchStartDate" name="start_date" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Đến ngày <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="batchEndDate" name="end_date" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Loại phòng
                                </label>
                                <select id="batchRoomTypeId" name="room_type_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">Tất cả loại phòng</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" id="batchCalculateBtn"
                                class="btn bg-green-500 hover:bg-green-600 text-white disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="batchCalculateText">Tính toán hàng loạt</span>
                                <svg id="batchCalculateLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden"
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
                    
                    <div id="batchResults" class="mt-6 hidden">
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-4">Kết quả tính toán hàng loạt</h4>
                        <div id="batchResultsContent">
                            <!-- Dynamic content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadRoomTypes();
            setDefaultDate();
            bindEvents();
        });

        function bindEvents() {
            document.getElementById('calculatorForm').addEventListener('submit', handleCalculation);
            document.getElementById('batchCalculatorForm').addEventListener('submit', handleBatchCalculation);
            document.getElementById('toggleBatchCalculator').addEventListener('click', toggleBatchCalculator);
        }

        function setDefaultDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('calculationDate').value = today;
            document.getElementById('batchStartDate').value = today;
            
            const nextWeek = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            document.getElementById('batchEndDate').value = nextWeek;
        }

        async function loadRoomTypes() {
            try {
                const response = await fetch('{{ route("admin.flexible-pricing.room-types") }}');
                const roomTypes = await response.json();
                
                const selects = ['roomTypeId', 'batchRoomTypeId'];
                selects.forEach(selectId => {
                    const select = document.getElementById(selectId);
                    const defaultOption = selectId === 'roomTypeId' ? 'Chọn loại phòng' : 'Tất cả loại phòng';
                    select.innerHTML = `<option value="">${defaultOption}</option>`;
                    
                    roomTypes.forEach(roomType => {
                        const option = document.createElement('option');
                        option.value = roomType.room_type_id;
                        option.textContent = roomType.name;
                        select.appendChild(option);
                    });
                });
            } catch (error) {
                console.error('Error loading room types:', error);
            }
        }

        async function handleCalculation(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('calculateBtn');
            const submitText = document.getElementById('calculateText');
            const submitLoading = document.getElementById('calculateLoading');
            
            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                const formData = new FormData(e.target);
                const params = new URLSearchParams();
                
                for (const [key, value] of formData.entries()) {
                    if (value) {
                        params.append(key, value);
                    }
                }
                
                const response = await fetch(`{{ route("admin.pricing.preview") }}?${params}`);
                const result = await response.json();
                
                if (result.success) {
                    displayCalculationResults(result.data);
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi tính toán');
                }
            } catch (error) {
                console.error('Error calculating price:', error);
                showError('Có lỗi xảy ra khi tính toán giá');
            } finally {
                // Hide loading
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        function displayCalculationResults(data) {
            const container = document.getElementById('calculationResults');
            
            if (!data || data.length === 0) {
                container.innerHTML = '<div class="text-center py-8 text-red-500">Không có dữ liệu</div>';
                return;
            }
            
            const result = data[0]; // Single day calculation
            const priceChange = result.adjusted_price - result.base_price;
            const changePercent = ((priceChange / result.base_price) * 100).toFixed(1);
            const changeClass = priceChange > 0 ? 'text-green-600' : priceChange < 0 ? 'text-red-600' : 'text-gray-600';
            
            let html = `
                <div class="space-y-6">
                    <!-- Price Summary -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400">Giá cơ bản</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(result.base_price)}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400">Giá sau điều chỉnh</div>
                                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                    ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(result.adjusted_price)}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400">Thay đổi</div>
                                <div class="text-2xl font-bold ${changeClass}">
                                    ${priceChange >= 0 ? '+' : ''}${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(priceChange)}
                                </div>
                                <div class="text-sm ${changeClass}">
                                    (${changePercent}%)
                                </div>
                            </div>
                        </div>
                    </div>
            `;
            
            // Applied Rules Details
            if (document.getElementById('showDetails').checked && result.applied_rules.length > 0) {
                html += `
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Quy tắc áp dụng</h4>
                        <div class="space-y-3">
                `;
                
                result.applied_rules.forEach(rule => {
                    const ruleTypeNames = {
                        'weekend': 'Cuối tuần',
                        'event': 'Sự kiện',
                        'holiday': 'Lễ hội',
                        'season': 'Mùa',
                        'occupancy': 'Tỷ lệ lấp đầy'
                    };
                    
                    const ruleTypeColors = {
                        'weekend': 'bg-blue-100 text-blue-800',
                        'event': 'bg-green-100 text-green-800',
                        'holiday': 'bg-purple-100 text-purple-800',
                        'season': 'bg-orange-100 text-orange-800',
                        'occupancy': 'bg-yellow-100 text-yellow-800'
                    };
                    
                    html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${ruleTypeColors[rule.rule_type] || 'bg-gray-100 text-gray-800'}">
                                    ${ruleTypeNames[rule.rule_type] || rule.rule_type}
                                </span>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">${rule.rule_name || 'Quy tắc ' + rule.rule_type}</div>
                                    ${rule.description ? `<div class="text-sm text-gray-500">${rule.description}</div>` : ''}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium ${rule.price_adjustment >= 0 ? 'text-green-600' : 'text-red-600'}">
                                    ${rule.price_adjustment >= 0 ? '+' : ''}${rule.price_adjustment}%
                                </div>
                                <div class="text-sm text-gray-500">
                                    Ưu tiên: ${rule.priority || 5}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div></div>';
            }
            
            // Additional Information
            html += `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Thông tin bổ sung</h5>
                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Ngày:</span>
                                    <span class="text-gray-900 dark:text-gray-100">${new Date(result.date).toLocaleDateString('vi-VN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Loại phòng:</span>
                                    <span class="text-gray-900 dark:text-gray-100">${result.room_type_name}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tỷ lệ lấp đầy:</span>
                                    <span class="text-gray-900 dark:text-gray-100">${result.occupancy_rate}%</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Phân tích</h5>
                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Số quy tắc áp dụng:</span>
                                    <span class="text-gray-900 dark:text-gray-100">${result.applied_rules.length}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tổng điều chỉnh:</span>
                                    <span class="text-gray-900 dark:text-gray-100">${result.total_adjustment || 0}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Trạng thái:</span>
                                    <span class="text-gray-900 dark:text-gray-100">
                                        ${result.is_capped ? 'Đã áp dụng giới hạn' : 'Bình thường'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.innerHTML = html;
        }

        async function handleBatchCalculation(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('batchCalculateBtn');
            const submitText = document.getElementById('batchCalculateText');
            const submitLoading = document.getElementById('batchCalculateLoading');
            
            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                const formData = new FormData(e.target);
                const params = new URLSearchParams();
                
                for (const [key, value] of formData.entries()) {
                    if (value) {
                        params.append(key, value);
                    }
                }
                
                const response = await fetch(`{{ route("admin.pricing.preview") }}?${params}`);
                const result = await response.json();
                
                if (result.success) {
                    displayBatchResults(result.data);
                    document.getElementById('batchResults').classList.remove('hidden');
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi tính toán hàng loạt');
                }
            } catch (error) {
                console.error('Error batch calculating:', error);
                showError('Có lỗi xảy ra khi tính toán hàng loạt');
            } finally {
                // Hide loading
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        function displayBatchResults(data) {
            const container = document.getElementById('batchResultsContent');
            
            if (!data || data.length === 0) {
                container.innerHTML = '<div class="text-center py-8 text-red-500">Không có dữ liệu</div>';
                return;
            }
            
            // Calculate summary statistics
            const totalDays = data.length;
            const avgPriceChange = data.reduce((sum, item) => sum + (item.adjusted_price - item.base_price), 0) / totalDays;
            const maxPriceChange = Math.max(...data.map(item => item.adjusted_price - item.base_price));
            const minPriceChange = Math.min(...data.map(item => item.adjusted_price - item.base_price));
            
            let html = `
                <!-- Summary Statistics -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                    <h5 class="font-medium text-blue-900 dark:text-blue-100 mb-3">Tóm tắt kết quả</h5>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div class="text-center">
                            <div class="text-blue-600 dark:text-blue-400 font-medium">Tổng số ngày</div>
                            <div class="text-lg font-bold text-blue-900 dark:text-blue-100">${totalDays}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-blue-600 dark:text-blue-400 font-medium">TB thay đổi</div>
                            <div class="text-lg font-bold text-blue-900 dark:text-blue-100">
                                ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(avgPriceChange)}
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-blue-600 dark:text-blue-400 font-medium">Tăng cao nhất</div>
                            <div class="text-lg font-bold text-green-600">
                                ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(maxPriceChange)}
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-blue-600 dark:text-blue-400 font-medium">Giảm nhiều nhất</div>
                            <div class="text-lg font-bold text-red-600">
                                ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(minPriceChange)}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Detailed Results Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại phòng</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giá gốc</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giá điều chỉnh</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thay đổi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quy tắc</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            `;
            
            data.forEach(item => {
                const priceChange = item.adjusted_price - item.base_price;
                const changePercent = ((priceChange / item.base_price) * 100).toFixed(1);
                const changeClass = priceChange > 0 ? 'text-green-600' : priceChange < 0 ? 'text-red-600' : 'text-gray-600';
                
                html += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                            ${new Date(item.date).toLocaleDateString('vi-VN', { weekday: 'short', day: '2-digit', month: '2-digit' })}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${item.room_type_name}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                            ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.base_price)}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                            ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.adjusted_price)}
                        </td>
                        <td class="px-4 py-3 text-sm ${changeClass}">
                            ${priceChange >= 0 ? '+' : ''}${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(priceChange)}
                            <div class="text-xs">(${changePercent}%)</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                            ${item.applied_rules.length > 0 ? 
                                item.applied_rules.map(rule => `<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">${rule.rule_type}</span>`).join('') : 
                                '<span class="text-gray-500">Không có</span>'
                            }
                        </td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
                
                <!-- Export Options -->
                <div class="mt-6 flex justify-end space-x-2">
                    <button onclick="exportBatchResults('csv')" class="btn bg-green-500 hover:bg-green-600 text-white text-sm">
                        Xuất CSV
                    </button>
                    <button onclick="exportBatchResults('excel')" class="btn bg-blue-500 hover:bg-blue-600 text-white text-sm">
                        Xuất Excel
                    </button>
                </div>
            `;
            
            container.innerHTML = html;
        }

        function toggleBatchCalculator() {
            const section = document.getElementById('batchCalculatorSection');
            const button = document.getElementById('toggleBatchCalculator');
            
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                button.textContent = 'Thu gọn';
            } else {
                section.classList.add('hidden');
                button.textContent = 'Mở rộng';
            }
        }

        function exportBatchResults(format) {
            const formData = new FormData(document.getElementById('batchCalculatorForm'));
            const params = new URLSearchParams();
            
            for (const [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            params.append('format', format);
            params.append('export', '1');
            
            window.open(`{{ route("admin.pricing.preview") }}?${params}`, '_blank');
        }

        function showError(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    ${message}
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>
</x-app-layout>
