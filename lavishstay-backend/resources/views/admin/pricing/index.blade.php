<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Dashboard Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý Giá Phòng</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Tổng quan hệ thống định giá động</p>
            </div>

            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.pricing.config') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L3 5h3v6h4V5h3L8 0zM1 14h14v2H1v-2z" />
                    </svg>
                    <span class="max-xs:sr-only">Cấu hình</span>
                </a>

                <a href="{{ route('admin.pricing.calculator') }}"
                    class="btn bg-green-500 hover:bg-green-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L3 5h3v6h4V5h3L8 0zM1 14h14v2H1v-2z" />
                    </svg>
                    <span class="max-xs:sr-only">Máy tính giá</span>
                </a>
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

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Flexible Pricing Rules -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Quy tắc giá linh hoạt</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        <span id="flexibleRulesCount">-</span> quy tắc
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Quản lý giá theo sự kiện, lễ hội, cuối tuần</p>
                <a href="{{ route('admin.room-prices.event_festival') }}"
                    class="btn bg-green-500 hover:bg-green-600 text-white w-full">
                    Quản lý quy tắc
                </a>
            </div>

            <!-- Dynamic Pricing Rules -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Giá động theo lấp đầy</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        <span id="dynamicRulesCount">-</span> quy tắc
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Điều chỉnh giá theo tỷ lệ lấp đầy phòng</p>
                <a href="{{ route('admin.dynamic-pricing.index') }}"
                    class="btn bg-green-500 hover:bg-green-600 text-white w-full">
                    Quản lý quy tắc
                </a>
            </div>

            <!-- Price History -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Lịch sử giá</h3>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        Theo dõi
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Xem lịch sử thay đổi giá phòng</p>
                <a href="{{ route('admin.pricing.history') }}"
                    class="btn bg-green-500 hover:bg-green-600 text-white w-full">
                    Xem lịch sử
                </a>
            </div>
        </div>

        <!-- Pricing Preview Chart -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Xem trước giá 7 ngày tới</h3>
                <div class="flex space-x-2">
                    <select id="roomTypeSelect" class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 
 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Chọn loại phòng</option>
                    </select>
                    <button id="refreshPreview" class="btn bg-gray-500 hover:bg-gray-600 text-white ml-3 text-sm">
                        Làm mới
                    </button>
                </div>
            </div>

            <div id="pricingPreviewContainer">
                <div class="text-center py-8">
                    <p class="text-gray-500">Chọn loại phòng để xem preview giá</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
            loadRoomTypes();
            bindEvents();
        });

        function bindEvents() {
            document.getElementById('refreshPreview').addEventListener('click', loadPricingPreview);
            document.getElementById('roomTypeSelect').addEventListener('change', loadPricingPreview);
        }

        async function loadStatistics() {
            try {
                const response = await fetch('{{ route('admin.pricing.statistics') }}');
                const data = await response.json();

                if (data.success) {
                    updateStatistics(data.data);
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        async function loadRoomTypes() {
            try {
                const response = await fetch('{{ route('admin.flexible-pricing.room-types') }}');
                const roomTypes = await response.json();

                const select = document.getElementById('roomTypeSelect');
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

        async function loadPricingPreview() {
            const roomTypeId = document.getElementById('roomTypeSelect').value;
            if (!roomTypeId) return;

            const container = document.getElementById('pricingPreviewContainer');
            container.innerHTML =
                '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500 mx-auto"></div></div>';

            try {
                const startDate = new Date().toISOString().split('T')[0];
                const endDate = new Date(Date.now() + 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

                const response = await fetch(
                    `{{ route('admin.pricing.preview') }}?room_type_id=${roomTypeId}&start_date=${startDate}&end_date=${endDate}`
                    );
                const data = await response.json();

                if (data.success) {
                    displayPricingPreview(data.data);
                } else {
                    container.innerHTML =
                        '<div class="text-center py-8 text-red-500">Có lỗi xảy ra khi tải dữ liệu</div>';
                }
            } catch (error) {
                console.error('Error loading pricing preview:', error);
                container.innerHTML = '<div class="text-center py-8 text-red-500">Có lỗi xảy ra khi tải dữ liệu</div>';
            }
        }

        function updateStatistics(stats) {
            const statisticsHtml = `
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Quy tắc linh hoạt</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.flexible_rules_count || 0}</div>
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
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Quy tắc động</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.dynamic_rules_count || 0}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tỷ lệ lấp đầy TB</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.avg_occupancy_rate || 0}%</div>
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
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Quy tắc hoạt động</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${stats.active_rules_count || 0}</div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('statisticsContainer').innerHTML = statisticsHtml;

            // Update quick action counts
            document.getElementById('flexibleRulesCount').textContent = stats.flexible_rules_count || 0;
            document.getElementById('dynamicRulesCount').textContent = stats.dynamic_rules_count || 0;
        }

                function displayPricingPreview(data) {
            const container = document.getElementById('pricingPreviewContainer');
            
            if (!data || data.length === 0) {
                container.innerHTML = '<div class="text-center py-8 text-gray-500">Không có dữ liệu</div>';
                return;
            }

            let html = '<div class="overflow-x-auto"><table class="w-full divide-y divide-gray-200 dark:divide-gray-700">';
            html += `
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giá gốc</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giá điều chỉnh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thay đổi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quy tắc áp dụng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            `;

			            data.forEach(item => {
                const priceChange = item.adjusted_price - item.base_price;
                const changeClass = priceChange > 0 ? 'text-green-600' : priceChange < 0 ? 'text-red-600' : 'text-gray-600';
                const changeSymbol = priceChange > 0 ? '+' : '';
                const changePercentage = item.price_adjustment_total || 0;
                
                // Format applied rules
                let rulesText = 'Không có';
                let rulesDetail = '';
                if (item.applied_rules && item.applied_rules.length > 0) {
                    const rulesList = item.applied_rules.map(rule => {
                        const adjustment = rule.adjustment_value || rule.price_adjustment || 0;
                        return `${rule.rule_type} (${adjustment > 0 ? '+' : ''}${adjustment}%)`;
                    });
                    rulesText = rulesList.join(', ');
                    
                    // Show total adjustment
                    rulesDetail = `<small class="block text-gray-500">Tổng: ${changePercentage > 0 ? '+' : ''}${changePercentage}%</small>`;
                }
                
                // Status indicators
                let statusBadges = '';
                if (item.capped_by_percentage) {
                    statusBadges += '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-1">Giới hạn %</span>';
                }
                if (item.capped_by_absolute) {
                    statusBadges += '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-1">Giới hạn tuyệt đối</span>';
                }
                if (!item.capped_by_percentage && !item.capped_by_absolute && item.applied_rules && item.applied_rules.length > 0) {
                    statusBadges += '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">Bình thường</span>';
                }
                
                html += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                            ${new Date(item.date).toLocaleDateString('vi-VN', { weekday: 'short', day: '2-digit', month: '2-digit' })}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.base_price)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                            ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.adjusted_price)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm ${changeClass}">
                            ${changeSymbol}${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(priceChange)}
                            <small class="block text-gray-500">(${changeSymbol}${changePercentage}%)</small>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                            ${rulesText}
                            ${rulesDetail}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            ${statusBadges || '<span class="text-gray-500">-</span>'}
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table></div>';
            
            // Add summary information
            const totalDays = data.length;
            const avgPrice = data.reduce((sum, item) => sum + item.adjusted_price, 0) / totalDays;
            const maxPrice = Math.max(...data.map(item => item.adjusted_price));
            const minPrice = Math.min(...data.map(item => item.adjusted_price));
            const daysWithRules = data.filter(item => item.applied_rules && item.applied_rules.length > 0).length;
            const cappedDays = data.filter(item => item.capped_by_percentage || item.capped_by_absolute).length;
            
            
            
            container.innerHTML = html;
        }


    </script>
</x-app-layout>
