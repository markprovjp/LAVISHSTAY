<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard Thông báo</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý và theo dõi thông báo hệ thống</p>
            </div>

            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button onclick="refreshNotifications()" class="btn bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Làm mới
                </button>
                <a href="{{ route('admin.notifications.list') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    Xem tất cả
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.81 7.81 0 01-.5-2.8A7.81 7.81 0 0115 12v5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tổng thông báo</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="total-notifications">{{ $statistics['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Chưa đọc</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="unread-notifications">{{ $statistics['unread'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hôm nay</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="today-notifications">{{ $statistics['today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Khẩn cấp</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="urgent-notifications">{{ $statistics['urgent'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thông báo gần đây</h3>
            </div>
            
            <div class="p-6">
                <div id="recent-notifications" class="space-y-4">
                    @forelse($recentNotifications ?? [] as $notification)
                        <div class="flex items-start space-x-3 p-4 rounded-lg {{ $notification->read_at ? 'bg-gray-50 dark:bg-gray-700/50' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $notification->color }}20;">
                                <span style="color: {{ $notification->color }}">{{ $notification->icon }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 {{ $notification->read_at ? '' : 'font-semibold' }}">
                                    {{ $notification->title }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $notification->message }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                    @if($notification->notificationType)
                                        • {{ $notification->notificationType->title }}
                                    @endif
                                </div>
                            </div>
                            @if(!$notification->read_at)
                                <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"></div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.81 7.81 0 01-.5-2.8A7.81 7.81 0 0115 12v5z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Chưa có thông báo nào</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Thông báo sẽ xuất hiện ở đây khi có hoạt động mới</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Test Actions -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Test Thông báo</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button onclick="testDirectNotification()" class="btn bg-blue-500 hover:bg-blue-600 text-white">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Test Direct
                    </button>
                    
                    <button onclick="testEventNotification()" class="btn bg-green-500 hover:bg-green-600 text-white">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Test Event
                    </button>
                    
                    <button onclick="testBookingNotification()" class="btn bg-purple-500 hover:bg-purple-600 text-white">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Test Booking
                    </button>
                </div>
                
                <div id="test-results" class="mt-4 hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Kết quả test:</h4>
                        <div id="test-output" class="text-sm text-gray-600 dark:text-gray-400"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add CSRF token
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.getElementsByTagName('head')[0].appendChild(meta);
        }

        async function refreshNotifications() {
            try {
                const response = await fetch('{{ route("admin.notifications.statistics") }}');
                const data = await response.json();
                
                if (data.success) {
                    const stats = data.data;
                    document.getElementById('total-notifications').textContent = stats.total || 0;
                    document.getElementById('unread-notifications').textContent = stats.unread || 0;
                    document.getElementById('today-notifications').textContent = stats.today || 0;
                    document.getElementById('urgent-notifications').textContent = stats.urgent || 0;
                }
                
                // Reload page to get fresh notifications
                setTimeout(() => window.location.reload(), 1000);
                
            } catch (error) {
                console.error('Error refreshing notifications:', error);
            }
        }

        async function testDirectNotification() {
            showTestResult('Đang test direct notification...');
            
            try {
                const response = await fetch('{{ route("admin.notifications.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        test_type: 'single',
                        user_id: 1
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showTestResult('✅ Direct notification test thành công!', 'success');
                    setTimeout(refreshNotifications, 2000);
                } else {
                    showTestResult('❌ Direct notification test thất bại: ' + result.message, 'error');
                }
                
            } catch (error) {
                showTestResult('❌ Lỗi: ' + error.message, 'error');
            }
        }

        async function testEventNotification() {
            showTestResult('Đang test event notification...');
            
            try {
                const response = await fetch('{{ route("admin.notifications.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        test_type: 'multiple'
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showTestResult('✅ Event notification test thành công!', 'success');
                    setTimeout(refreshNotifications, 2000);
                } else {
                    showTestResult('❌ Event notification test thất bại: ' + result.message, 'error');
                }
                
            } catch (error) {
                showTestResult('❌ Lỗi: ' + error.message, 'error');
            }
        }

        async function testBookingNotification() {
            showTestResult('Đang test booking notification...');
            
            try {
                const response = await fetch('{{ route("admin.notifications.send.custom") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        title: 'Test Booking Notification',
                        message: 'Đây là test thông báo booking được tạo lúc ' + new Date().toLocaleTimeString(),
                        priority: 'normal',
                        icon: '📅',
                        color: '#3B82F6',
                        target_type: 'all'
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showTestResult('✅ Booking notification test thành công!', 'success');
                    setTimeout(refreshNotifications, 2000);
                } else {
                    showTestResult('❌ Booking notification test thất bại: ' + result.message, 'error');
                }
                
            } catch (error) {
                showTestResult('❌ Lỗi: ' + error.message, 'error');
            }
        }

        function showTestResult(message, type = 'info') {
            const resultsDiv = document.getElementById('test-results');
            const outputDiv = document.getElementById('test-output');
            
            resultsDiv.classList.remove('hidden');
            outputDiv.textContent = message;
            
            // Add color based on type
            outputDiv.className = 'text-sm ' + (
                type === 'success' ? 'text-green-600 dark:text-green-400' :
                type === 'error' ? 'text-red-600 dark:text-red-400' :
                'text-gray-600 dark:text-gray-400'
            );
        }

        // Auto refresh every 30 seconds
        setInterval(refreshNotifications, 30000);
    </script>
</x-app-layout>
