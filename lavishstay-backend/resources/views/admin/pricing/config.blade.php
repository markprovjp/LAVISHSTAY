<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Cấu hình Hệ thống Giá</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Thiết lập các thông số cho hệ thống định giá động</p>
            </div>
            
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.pricing.index') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M7.3 8.7c-.4-.4-.4-1 0-1.4l7-7c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4L9.4 8l6.3 6.3c.4.4.4 1 0 1.4-.4.4-1 .4-1.4 0l-7-7z"/>
                    </svg>
                    <span class="max-xs:sr-only">Quay lại</span>
                </a>
                
                <button id="testConfigBtn" class="btn bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L3 5h3v6h4V5h3L8 0zM1 14h14v2H1v-2z" />
                    </svg>
                    <span class="max-xs:sr-only">Test cấu hình</span>
                </button>
            </div>
        </div>

        <!-- Configuration Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="p-6">
                <form id="configForm" class="space-y-6">
                    @csrf
                    
                    <!-- Price Limits Section -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Giới hạn giá</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Giới hạn tăng giá tối đa (%)
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="maxPriceIncreasePercentage" name="max_price_increase_percentage" 
                                    step="0.01" min="0" max="100" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="40.00">
                                <p class="text-xs text-gray-500 mt-1">Giới hạn tỷ lệ tăng giá tối đa so với giá gốc</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Giá trần tuyệt đối (VND)
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="maxAbsolutePriceVnd" name="max_absolute_price_vnd" 
                                    step="1000" min="0" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="3000000">
                                <p class="text-xs text-gray-500 mt-1">Giá tối đa tuyệt đối không được vượt quá</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule Priority Section -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Chế độ áp dụng quy tắc</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="radio" id="stackingMode" name="rule_mode" value="stacking" 
                                    class="form-radio h-4 w-4 text-violet-600">
                                <label for="stackingMode" class="ml-3">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Chế độ cộng dồn (Stacking)</div>
                                    <div class="text-xs text-gray-500">Áp dụng tất cả quy tắc phù hợp và cộng dồn tỷ lệ điều chỉnh</div>
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="radio" id="priorityMode" name="rule_mode" value="priority" 
                                    class="form-radio h-4 w-4 text-violet-600">
                                <label for="priorityMode" class="ml-3">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Chế độ ưu tiên (Priority)</div>
                                    <div class="text-xs text-gray-500">Chỉ áp dụng quy tắc có độ ưu tiên cao nhất</div>
                                </label>
                            </div>
                        </div>
                        
                        <div id="exclusiveRuleSection" class="mt-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Loại quy tắc ưu tiên
                            </label>
                            <select id="exclusiveRuleType" name="exclusive_rule_type" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Tự động (theo priority)</option>
                                <option value="event">Sự kiện</option>
                                <option value="holiday">Lễ hội</option>
                                <option value="season">Mùa</option>
                                <option value="weekend">Cuối tuần</option>
                                <option value="occupancy">Tỷ lệ lấp đầy</option>
                            </select>
                        </div>
                    </div>

                    <!-- Cache Settings -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Cài đặt Cache</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Thời gian cache (phút)
                                </label>
                                <input type="number" id="cacheTimeout" name="cache_timeout" 
                                    min="1" max="1440" value="60"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <p class="text-xs text-gray-500 mt-1">Thời gian lưu cache giá tính toán</p>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tự động xóa cache
                                    </label>
                                    <button type="button" id="clearCacheBtn" class="btn bg-red-500 hover:bg-red-600 text-white text-sm">
                                        Xóa cache ngay
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <input type="checkbox" id="autoClearCache" name="auto_clear_cache" 
                                        class="form-checkbox h-4 w-4 text-violet-600">
                                    <label for="autoClearCache" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        Tự động xóa cache khi cập nhật quy tắc
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Settings -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Cài đặt nâng cao</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="enableLogging" name="enable_logging" 
                                    class="form-checkbox h-4 w-4 text-violet-600">
                                <label for="enableLogging" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                    Bật ghi log chi tiết cho hệ thống định giá
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="enableNotifications" name="enable_notifications" 
                                    class="form-checkbox h-4 w-4 text-violet-600">
                                <label for="enableNotifications" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                    Gửi thông báo khi giá thay đổi đáng kể (>20%)
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="enableApiAccess" name="enable_api_access" 
                                    class="form-checkbox h-4 w-4 text-violet-600">
                                <label for="enableApiAccess" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                    Cho phép truy cập API tính giá từ bên ngoài
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" id="resetConfigBtn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">
                            Khôi phục mặc định
                        </button>
                        <button type="submit" id="saveConfigBtn"
                            class="px-4 py-2 text-sm font-medium btn bg-indigo-500 hover:bg-indigo-600 text-white disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="saveConfigText">Lưu cấu hình</span>
                            <svg id="saveConfigLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden"
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

        <!-- Test Results Modal -->
        <div id="testResultsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Kết quả test cấu hình</h3>
                            <button onclick="closeTestModal()"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div id="testResultsContent">
                            <!-- Dynamic content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadCurrentConfig();
            bindEvents();
        });

        function bindEvents() {
            document.getElementById('configForm').addEventListener('submit', handleConfigSubmit);
            document.getElementById('testConfigBtn').addEventListener('click', testConfiguration);
            document.getElementById('clearCacheBtn').addEventListener('click', clearCache);
            document.getElementById('resetConfigBtn').addEventListener('click', resetConfig);
            
            // Toggle exclusive rule section
            document.querySelectorAll('input[name="rule_mode"]').forEach(radio => {
                radio.addEventListener('change', toggleExclusiveSection);
            });
        }

        function toggleExclusiveSection() {
            const priorityMode = document.getElementById('priorityMode').checked;
            const exclusiveSection = document.getElementById('exclusiveRuleSection');
            
            if (priorityMode) {
                exclusiveSection.classList.remove('hidden');
            } else {
                exclusiveSection.classList.add('hidden');
            }
        }

        async function loadCurrentConfig() {
            try {
                const response = await fetch('{{ route("admin.pricing.config") }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    populateForm(result.data);
                }
            } catch (error) {
                console.error('Error loading config:', error);
            }
        }

        function populateForm(config) {
            document.getElementById('maxPriceIncreasePercentage').value = config.max_price_increase_percentage || 40;
            document.getElementById('maxAbsolutePriceVnd').value = config.max_absolute_price_vnd || 3000000;
            
            if (config.use_exclusive_rule) {
                document.getElementById('priorityMode').checked = true;
                document.getElementById('exclusiveRuleType').value = config.exclusive_rule_type || '';
            } else {
                document.getElementById('stackingMode').checked = true;
            }
            
            toggleExclusiveSection();
            
            // Set other checkboxes based on config
            document.getElementById('autoClearCache').checked = config.auto_clear_cache || false;
            document.getElementById('enableLogging').checked = config.enable_logging || false;
            document.getElementById('enableNotifications').checked = config.enable_notifications || false;
            document.getElementById('enableApiAccess').checked = config.enable_api_access || false;
            document.getElementById('cacheTimeout').value = config.cache_timeout || 60;
        }

        async function handleConfigSubmit(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('saveConfigBtn');
            const submitText = document.getElementById('saveConfigText');
            const submitLoading = document.getElementById('saveConfigLoading');
            
            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                const formData = new FormData(e.target);
                const data = {
                    max_price_increase_percentage: parseFloat(formData.get('max_price_increase_percentage')),
                    max_absolute_price_vnd: parseFloat(formData.get('max_absolute_price_vnd')),
                    use_exclusive_rule: formData.get('rule_mode') === 'priority',
                    exclusive_rule_type: formData.get('exclusive_rule_type') || null,
                    cache_timeout: parseInt(formData.get('cache_timeout')) || 60,
                    auto_clear_cache: formData.has('auto_clear_cache'),
                    enable_logging: formData.has('enable_logging'),
                    enable_notifications: formData.has('enable_notifications'),
                    enable_api_access: formData.has('enable_api_access')
                };
                
                const response = await fetch('{{ route("admin.pricing.config.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccess('Cấu hình đã được lưu thành công!');
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi lưu cấu hình');
                }
            } catch (error) {
                console.error('Error saving config:', error);
                showError('Có lỗi xảy ra khi lưu cấu hình');
            } finally {
                // Hide loading
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        async function testConfiguration() {
            try {
                const response = await fetch('{{ route("admin.pricing.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    displayTestResults(result.data);
                    document.getElementById('testResultsModal').classList.remove('hidden');
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi test cấu hình');
                }
            } catch (error) {
                console.error('Error testing config:', error);
                showError('Có lỗi xảy ra khi test cấu hình');
            }
        }

        function displayTestResults(data) {
            const container = document.getElementById('testResultsContent');
            
            let html = '<div class="space-y-6">';
            
            // Test summary
            html += `
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Tóm tắt test</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>Số room types test: <span class="font-medium">${data.room_types_tested}</span></div>
                        <div>Số ngày test: <span class="font-medium">${data.days_tested}</span></div>
                        <div>Quy tắc áp dụng: <span class="font-medium">${data.rules_applied}</span></div>
                        <div>Thời gian thực hiện: <span class="font-medium">${data.execution_time}ms</span></div>
                    </div>
                </div>
            `;
            
            // Test results table
            if (data.results && data.results.length > 0) {
                html += `
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Kết quả chi tiết</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercasetracking-wider">Loại phòng</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giá gốc</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giá điều chỉnh</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thay đổi</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quy tắc</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                `;
                
                data.results.forEach(result => {
                    const priceChange = result.adjusted_price - result.base_price;
                    const changePercent = ((priceChange / result.base_price) * 100).toFixed(1);
                    const changeClass = priceChange > 0 ? 'text-green-600' : priceChange < 0 ? 'text-red-600' : 'text-gray-600';
                    
                    html += `
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">${result.room_type_name}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">${new Date(result.date).toLocaleDateString('vi-VN')}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(result.base_price)}</td>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(result.adjusted_price)}</td>
                            <td class="px-4 py-2 text-sm ${changeClass}">
                                ${priceChange >= 0 ? '+' : ''}${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(priceChange)}
                                (${changePercent}%)
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                ${result.applied_rules.length > 0 ? result.applied_rules.map(rule => `<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">${rule.rule_type}</span>`).join('') : 'Không có'}
                            </td>
                        </tr>
                    `;
                });
                
                html += '</tbody></table></div></div>';
            }
            
            html += '</div>';
            container.innerHTML = html;
        }

        function closeTestModal() {
            document.getElementById('testResultsModal').classList.add('hidden');
        }

        async function clearCache() {
            try {
                const response = await fetch('{{ route("admin.pricing.clear-cache") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccess('Cache đã được xóa thành công!');
                } else {
                    showError(result.message || 'Có lỗi xảy ra khi xóa cache');
                }
            } catch (error) {
                console.error('Error clearing cache:', error);
                showError('Có lỗi xảy ra khi xóa cache');
            }
        }

        function resetConfig() {
            if (confirm('Bạn có chắc chắn muốn khôi phục cấu hình mặc định?')) {
                // Reset to default values
                document.getElementById('maxPriceIncreasePercentage').value = 40;
                document.getElementById('maxAbsolutePriceVnd').value = 3000000;
                document.getElementById('stackingMode').checked = true;
                document.getElementById('priorityMode').checked = false;
                document.getElementById('exclusiveRuleType').value = '';
                document.getElementById('cacheTimeout').value = 60;
                document.getElementById('autoClearCache').checked = false;
                document.getElementById('enableLogging').checked = false;
                document.getElementById('enableNotifications').checked = false;
                document.getElementById('enableApiAccess').checked = false;
                
                toggleExclusiveSection();
            }
        }

        function showSuccess(message) {
            // Create and show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    ${message}
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function showError(message) {
            // Create and show error notification
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


