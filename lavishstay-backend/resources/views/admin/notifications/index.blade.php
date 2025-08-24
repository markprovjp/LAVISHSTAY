<x-app-layout>
    <style>
        #modalPanel{
            z-index: 90;
        }
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.621);
        }
        .notification-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
    
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý thông báo</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý hệ thống thông báo, loại thông báo và gửi thông báo tùy chỉnh</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Send Custom Notification -->
                <button onclick="showSendNotificationModal()" 
                    class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                        <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                    <span class="max-xs:sr-only">Gửi thông báo</span>
                </button>
                
                <!-- Test Notification -->
                <button onclick="showTestModal()"
                    class="btn bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                    </svg>
                    <span class="max-xs:sr-only">Test hệ thống</span>
                </button>

                <!-- Cleanup -->
                <button onclick="showCleanupModal()"
                    class="btn bg-red-500 hover:bg-red-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                    <span class="max-xs:sr-only">Dọn dẹp</span>
                </button>

                <!-- Refresh -->
                <button onclick="window.location.reload()"
                    class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                    </svg>
                    <span class="max-xs:sr-only">Làm mới</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Notifications -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-violet-100 dark:bg-violet-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.81 7.81 0 01-.5-2.8A7.81 7.81 0 0115 12v5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7H4l5-5 5 5H9v5a7.81 7.81 0 00.5 2.8A7.81 7.81 0 009 12V7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng thông báo</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($statistics['total_notifications']) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Unread Notifications -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Chưa đọc</p>
                        <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                            {{ number_format($statistics['unread_notifications']) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Today's Notifications -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Hôm nay</p>
                        <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($statistics['today_notifications']) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Urgent Notifications -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-400/30 rounded-lg flex items-center justify-center">
                            <div class="notification-badge">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Khẩn cấp (chưa đọc)</p>
                        <p class="text-2xl font-semibold text-orange-600 dark:text-orange-400">
                            {{ number_format($statistics['urgent_notifications']) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Notification Types -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Notification Types Management -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Loại thông báo</h3>
                        <button onclick="showCreateTypeModal()" class="btn-sm bg-violet-500 hover:bg-violet-600 text-white">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Thêm mới
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse($notificationTypes as $type)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: {{ $type->color }}20;">
                                        <span style="color: {{ $type->color }}">{{ $type->icon }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $type->title }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $type->name }} • 
                                            <span class="px-1.5 py-0.5 text-xs rounded-full bg-{{ $type->priority === 'urgent' ? 'red' : ($type->priority === 'high' ? 'orange' : ($type->priority === 'normal' ? 'blue' : 'gray')) }}-100 text-{{ $type->priority === 'urgent' ? 'red' : ($type->priority === 'high' ? 'orange' : ($type->priority === 'normal' ? 'blue' : 'gray')) }}-800 dark:bg-{{ $type->priority === 'urgent' ? 'red' : ($type->priority === 'high' ? 'orange' : ($type->priority === 'normal' ? 'blue' : 'gray')) }}-400/20 dark:text-{{ $type->priority === 'urgent' ? 'red' : ($type->priority === 'high' ? 'orange' : ($type->priority === 'normal' ? 'blue' : 'gray')) }}-300">
                                                {{ ucfirst($type->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $type->notifications_count ?? 0 }}</span>
                                    <div class="flex space-x-1">
                                        <button onclick="editNotificationType({{ $type->id }})" class="p-1 text-gray-400 hover:text-violet-600 dark:hover:text-violet-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        @if($type->is_active)
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        @else
                                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.81 7.81 0 01-.5-2.8A7.81 7.81 0 0115 12v5z"></path>
                                </svg>
                                <p class="font-medium">Chưa có loại thông báo</p>
                                <p class="text-sm">Tạo loại thông báo đầu tiên</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thông báo gần đây</h3>
                        <a href="{{ route('admin.notifications.list') }}" class="text-sm text-violet-600 hover:text-violet-800 dark:text-violet-400 dark:hover:text-violet-300">
                            Xem tất cả →
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse($recentNotifications as $notification)
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg {{ $notification->read_at ? '' : 'border-l-4 border-violet-500' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $notification->color }}20;">
                                    <span style="color: {{ $notification->color }}">{{ $notification->icon }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $notification->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $notification->message }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                @if(!$notification->read_at)
                                    <div class="w-2 h-2 bg-violet-500 rounded-full flex-shrink-0 mt-2"></div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.81 7.81 0 01-.5-2.8A7.81 7.81 0 0115 12v5z"></path>
                                </svg>
                                <p class="font-medium">Chưa có thông báo</p>
                                <p class="text-sm">Thông báo sẽ hiển thị ở đây</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications by Priority Chart -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thống kê theo độ ưu tiên</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($notificationsByPriority as $priority => $count)
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center bg-{{ $priority === 'urgent' ? 'red' : ($priority === 'high' ? 'orange' : ($priority === 'normal' ? 'blue' : 'gray')) }}-100 dark:bg-{{ $priority === 'urgent' ? 'red' : ($priority === 'high' ? 'orange' : ($priority === 'normal' ? 'blue' : 'gray')) }}-400/30">
                                <svg class="w-6 h-6 text-{{ $priority === 'urgent' ? 'red' : ($priority === 'high' ? 'orange' : ($priority === 'normal' ? 'blue' : 'gray')) }}-600 dark:text-{{ $priority === 'urgent' ? 'red' : ($priority === 'high' ? 'orange' : ($priority === 'normal' ? 'blue' : 'gray')) }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($priority === 'urgent')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    @elseif($priority === 'high')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @elseif($priority === 'normal')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.81 7.81 0 01-.5-2.8A7.81 7.81 0 0115 12v5z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    @endif
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($count) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $priority }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- System Health Status -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Trạng thái hệ thống</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Notification Service Status -->
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Dịch vụ thông báo</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Hoạt động bình thường</div>
                        </div>
                    </div>

                    <!-- Queue Status -->
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Hàng đợi</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">0 công việc đang chờ</div>
                        </div>
                    </div>

                    <!-- Database Status -->
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Cơ sở dữ liệu</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Kết nối ổn định</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Custom Notification Modal -->
    <div id="sendNotificationModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="sendModalBackdrop" class="fixed modal-overlay inset-0 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div id="sendModalPanel" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out translate-y-4 opacity-0 sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.81 7.81 0 01-.5-2.8A7.81 7.81 0 0115 12v5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Gửi thông báo tùy chỉnh</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tạo và gửi thông báo đến người dùng</p>
                        </div>
                    </div>
                    <button onclick="hideSendNotificationModal()" class="rounded-lg p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="sendNotificationForm" onsubmit="sendCustomNotification(event)">
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tiêu đề *</label>
                            <input type="text" name="title" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Nhập tiêu đề thông báo...">
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nội dung *</label>
                            <textarea name="message" rows="4" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Nhập nội dung thông báo..."></textarea>
                        </div>

                        <!-- Priority and Icon -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Độ ưu tiên</label>
                                <select name="priority"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                    <option value="low">Thấp</option>
                                    <option value="normal" selected>Bình thường</option>
                                    <option value="high">Cao</option>
                                    <option value="urgent">Khẩn cấp</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon</label>
                                <input type="text" name="icon" value="📢"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                    placeholder="📢">
                            </div>
                        </div>

                        <!-- Target Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gửi đến</label>
                            <select name="target_type" onchange="toggleTargetOptions(this.value)"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <option value="all">Tất cả người dùng</option>
                                <option value="roles">Theo vai trò</option>
                                <option value="users">Người dùng cụ thể</option>
                            </select>
                        </div>

                        <!-- Target Roles (hidden by default) -->
                        <div id="targetRoles" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chọn vai trò</label>
                            <div class="space-y-2 max-h-32 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-3">
                                @foreach(['admin', 'hotel_manager', 'receptionist', 'housekeeping', 'finance', 'marketing'] as $role)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="target_roles[]" value="{{ $role }}" class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $role) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Target Users (hidden by default) -->
                        <div id="targetUsers" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chọn người dùng</label>
                            <select name="target_users[]" multiple
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <!-- Users will be loaded dynamically -->
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Giữ Ctrl để chọn nhiều người dùng</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="hideSendNotificationModal()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg transition-colors duration-200">
                            Hủy
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Gửi thông báo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create/Edit Notification Type Modal -->
    <div id="notificationTypeModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="typeModalBackdrop" class="fixed modal-overlay inset-0 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div id="typeModalPanel" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out translate-y-4 opacity-0 sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 id="typeModalTitle" class="text-xl font-semibold text-gray-900 dark:text-gray-100">Tạo loại thông báo</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Cấu hình loại thông báo mới</p>
                        </div>
                    </div>
                    <button onclick="hideNotificationTypeModal()" class="rounded-lg p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="notificationTypeForm" onsubmit="saveNotificationType(event)">
                    <input type="hidden" name="type_id" id="typeId">
                    
                    <div class="space-y-6">
                        <!-- Name and Title -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tên (key) *</label>
                                <input type="text" name="name" id="typeName" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                    placeholder="booking_new">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tiêu đề *</label>
                                <input type="text" name="title" id="typeTitle" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                    placeholder="Booking mới">
                            </div>
                        </div>

                        <!-- Message Template -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Template tin nhắn *</label>
                            <textarea name="message_template" id="typeMessageTemplate" rows="3" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Booking mới #{booking_id} đã được tạo cho phòng {room_number}"></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sử dụng {variable} để thay thế động</p>
                        </div>

                        <!-- Priority, Icon, Color -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Độ ưu tiên</label>
                                <select name="priority" id="typePriority"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                    <option value="low">Thấp</option>
                                    <option value="normal" selected>Bình thường</option>
                                    <option value="high">Cao</option>
                                    <option value="urgent">Khẩn cấp</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon</label>
                                <input type="text" name="icon" id="typeIcon" value="🔔"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Màu sắc</label>
                                <input type="color" name="color" id="typeColor" value="#3B82F6"
                                    class="block w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            </div>
                        </div>

                        <!-- Target Roles -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vai trò nhận thông báo</label>
                            <div class="space-y-2 max-h-32 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-3">
                                @foreach(['admin', 'hotel_manager', 'receptionist', 'housekeeping', 'finance', 'marketing'] as $role)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="target_roles[]" value="{{ $role }}" class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $role) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="typeIsActive" checked class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                            <label for="typeIsActive" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Kích hoạt</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="hideNotificationTypeModal()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg transition-colors duration-200">
                            Hủy
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Test Modal -->
    <div id="testModal" class="fixed inset-0 modal-overlay overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Test hệ thống thông báo</h3>
                    <button onclick="hideTestModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại test</label>
                    <select id="testType" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="single">Gửi cho 1 người dùng</option>
                        <option value="multiple">Gửi cho nhiều người dùng</option>
                        <option value="broadcast">Gửi cho tất cả</option>
                    </select>
                </div>
                
                <div id="testUserSelect" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chọn người dùng</label>
                    <select id="testUserId" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <!-- Users will be loaded dynamically -->
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideTestModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg">
                        Hủy
                    </button>
                    <button type="button" onclick="runTest()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Chạy test
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cleanup Modal -->
    <div id="cleanupModal" class="fixed inset-0 modal-overlay overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Dọn dẹp thông báo cũ</h3>
                    <button onclick="hideCleanupModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <div class="flex items-start p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 mb-4">
                        <div class="flex items-center justify-center w-8 h-8 text-yellow-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-yellow-700 dark:text-yellow-100">Cảnh báo</h3>
                            <div class="text-sm text-yellow-600 dark:text-yellow-200">
                                Thao tác này sẽ xóa vĩnh viễn các thông báo cũ và không thể hoàn tác.
                            </div>
                        </div>
                    </div>
                    
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Xóa các thông báo cũ hơn (ngày)
                    </label>
                    <input type="number" id="cleanupDays" min="7" max="365" value="30" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tối thiểu 7 ngày, tối đa 1 năm</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideCleanupModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg">
                        Hủy
                    </button>
                    <button type="button" onclick="performCleanup()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                        Xóa dữ liệu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add CSRF token to meta tag if not exists
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.getElementsByTagName('head')[0].appendChild(meta);
        }

        // Modal Animation Functions
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            const backdrop = modal.querySelector('[id$="Backdrop"]') || modal.querySelector('.fixed.inset-0');
            const panel = modal.querySelector('[id$="Panel"]') || modal.querySelector('.inline-block');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            requestAnimationFrame(() => {
                if (backdrop) {
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                }
                if (panel) {
                    panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                    panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
                }
            });
        }

        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            const backdrop = modal.querySelector('[id$="Backdrop"]') || modal.querySelector('.fixed.inset-0');
            const panel = modal.querySelector('[id$="Panel"]') || modal.querySelector('.inline-block');
            
            if (backdrop) {
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
            }
            if (panel) {
                panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
                panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            }
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Send Custom Notification Modal
        function showSendNotificationModal() {
            showModal('sendNotificationModal');
            loadUsers();
        }

        function hideSendNotificationModal() {
            hideModal('sendNotificationModal');
            document.getElementById('sendNotificationForm').reset();
        }

        function toggleTargetOptions(targetType) {
            const rolesDiv = document.getElementById('targetRoles');
            const usersDiv = document.getElementById('targetUsers');
            
            rolesDiv.classList.add('hidden');
            usersDiv.classList.add('hidden');
            
            if (targetType === 'roles') {
                rolesDiv.classList.remove('hidden');
            } else if (targetType === 'users') {
                usersDiv.classList.remove('hidden');
            }
        }

        async function loadUsers() {
            try {
                const response = await fetch('/api/users', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const users = await response.json();
                    const select = document.querySelector('select[name="target_users[]"]');
                    const testSelect = document.getElementById('testUserId');
                    
                    select.innerHTML = '';
                    testSelect.innerHTML = '';
                    
                    users.forEach(user => {
                        const option = new Option(`${user.name} (${user.email})`, user.id);
                        const testOption = new Option(`${user.name} (${user.email})`, user.id);
                        select.add(option);
                        testSelect.add(testOption);
                    });
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }

        async function sendCustomNotification(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            
            // Handle multiple selections
            data.target_roles = formData.getAll('target_roles[]');
            data.target_users = formData.getAll('target_users[]');
            
            try {
                const response = await fetch('{{ route("admin.notifications.send.custom") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('success', result.message);
                    hideSendNotificationModal();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Error sending notification:', error);
                showNotification('error', 'Có lỗi xảy ra khi gửi thông báo');
            }
        }

        // Notification Type Modal
        function showCreateTypeModal() {
            document.getElementById('typeModalTitle').textContent = 'Tạo loại thông báo';
            document.getElementById('notificationTypeForm').reset();
            document.getElementById('typeId').value = '';
            showModal('notificationTypeModal');
        }

        function hideNotificationTypeModal() {
            hideModal('notificationTypeModal');
        }

        async function editNotificationType(typeId) {
            try {
                const response = await fetch(`/admin/notifications/types/${typeId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const type = await response.json();
                    
                    document.getElementById('typeModalTitle').textContent = 'Chỉnh sửa loại thông báo';
                    document.getElementById('typeId').value = type.id;
                    document.getElementById('typeName').value = type.name;
                    document.getElementById('typeTitle').value = type.title;
                    document.getElementById('typeMessageTemplate').value = type.message_template;
                    document.getElementById('typePriority').value = type.priority;
                    document.getElementById('typeIcon').value = type.icon;
                    document.getElementById('typeColor').value = type.color;
                    document.getElementById('typeIsActive').checked = type.is_active;
                    
                    // Set target roles
                    const checkboxes = document.querySelectorAll('input[name="target_roles[]"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = type.target_roles && type.target_roles.includes(checkbox.value);
                    });
                    
                    showModal('notificationTypeModal');
                }
            } catch (error) {
                console.error('Error loading notification type:', error);
                showNotification('error', 'Không thể tải thông tin loại thông báo');
            }
        }

        async function saveNotificationType(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            
            // Handle multiple selections and boolean
            data.target_roles = formData.getAll('target_roles[]');
            data.is_active = formData.has('is_active');
            
            const typeId = data.type_id;
            const url = typeId ? 
                `{{ route("admin.notifications.types.update", ":id") }}`.replace(':id', typeId) :
                '{{ route("admin.notifications.types.create") }}';
            const method = typeId ? 'PUT' : 'POST';
            
            try {
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
                    showNotification('success', result.message);
                    hideNotificationTypeModal();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Error saving notification type:', error);
                showNotification('error', 'Có lỗi xảy ra khi lưu loại thông báo');
            }
        }

        // Test Modal
        function showTestModal() {
            showModal('testModal');
            loadUsers();
            
            document.getElementById('testType').addEventListener('change', function() {
                const userSelect = document.getElementById('testUserSelect');
                if (this.value === 'single') {
                    userSelect.classList.remove('hidden');
                } else {
                    userSelect.classList.add('hidden');
                }
            });
        }

        function hideTestModal() {
            hideModal('testModal');
        }

        async function runTest() {
            const testType = document.getElementById('testType').value;
            const userId = document.getElementById('testUserId').value;
            
            const data = {
                test_type: testType,
                user_id: testType === 'single' ? userId : null
            };
            
            try {
                const response = await fetch('{{ route("admin.notifications.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('success', result.message);
                    hideTestModal();
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Error running test:', error);
                showNotification('error', 'Có lỗi xảy ra khi chạy test');
            }
        }

        // Cleanup Modal
        function showCleanupModal() {
            showModal('cleanupModal');
        }

        function hideCleanupModal() {
            hideModal('cleanupModal');
        }

        async function performCleanup() {
            const days = document.getElementById('cleanupDays').value;
            
            if (!confirm(`Bạn có chắc chắn muốn xóa tất cả thông báo cũ hơn ${days} ngày? Thao tác này không thể hoàn tác.`)) {
                return;
            }

            try {
                const response = await fetch('{{ route("admin.notifications.cleanup") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        days: parseInt(days),
                        confirm: true
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('success', result.message);
                    hideCleanupModal();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Cleanup error:', error);
                showNotification('error', 'Có lỗi xảy ra khi dọn dẹp dữ liệu');
            }
        }

        // Notification system
        function showNotification(type, message) {
            const notification = document.createElement('div');
            const isError = type === 'error';
            
            notification.className = `fixed top-4 right-4 z-50 transform transition-all duration-300 ease-out flex items-start p-4 rounded-xl shadow-2xl max-w-sm ${
                isError 
                    ? 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500' 
                    : 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center justify-center w-8 h-8 ${isError ? 'text-red-500' : 'text-green-500'}">
                    ${isError ? `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    ` : `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    `}
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="font-semibold ${isError ? 'text-red-700 dark:text-red-100' : 'text-green-700 dark:text-green-100'}">${isError ? 'Lỗi!' : 'Thành công!'}</h3>
                    <div class="text-sm ${isError ? 'text-red-600 dark:text-red-200' : 'text-green-600 dark:text-green-200'}">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            requestAnimationFrame(() => {
                notification.style.transform = 'translateX(0)';
                notification.style.opacity = '1';
            });
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const modals = ['sendNotificationModal', 'notificationTypeModal', 'testModal', 'cleanupModal'];
            
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal && !modal.classList.contains('hidden')) {
                    hideModal(modalId);
                }
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = ['sendNotificationModal', 'notificationTypeModal', 'testModal', 'cleanupModal'];
                
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (!modal.classList.contains('hidden')) {
                        hideModal(modalId);
                    }
                });
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Show welcome message for first-time visitors
            if (!localStorage.getItem('notifications_visited')) {
                setTimeout(() => {
                    showNotification('info', 'Chào mừng đến với trang quản lý thông báo! Bạn có thể tạo, gửi và quản lý thông báo từ đây.');
                    localStorage.setItem('notifications_visited', 'true');
                }, 1000);
            }
        });

        // Export functions to global scope
        window.showSendNotificationModal = showSendNotificationModal;
        window.hideSendNotificationModal = hideSendNotificationModal;
        window.toggleTargetOptions = toggleTargetOptions;
        window.sendCustomNotification = sendCustomNotification;
        window.showCreateTypeModal = showCreateTypeModal;
        window.hideNotificationTypeModal = hideNotificationTypeModal;
        window.editNotificationType = editNotificationType;
        window.saveNotificationType = saveNotificationType;
        window.showTestModal = showTestModal;
        window.hideTestModal = hideTestModal;
        window.runTest = runTest;
        window.showCleanupModal = showCleanupModal;
        window.hideCleanupModal = hideCleanupModal;
        window.performCleanup = performCleanup;
        window.showNotification = showNotification;
    </script>
</x-app-layout>