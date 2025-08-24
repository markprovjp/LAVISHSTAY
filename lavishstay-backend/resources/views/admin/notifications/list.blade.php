<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Danh sách thông báo</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý và theo dõi tất cả thông báo trong hệ thống</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.notifications.index') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M6.5 0C2.91 0 0 2.91 0 6.5S2.91 13 6.5 13c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L17.49 16l-4.99-5v-.79l-.28-.27C13.41 8.81 14 7.33 14 5.72 14 2.91 11.09 0 7.5 0zm0 2C9.99 2 12 4.01 12 6.5S9.99 11 7.5 11 3 8.99 3 6.5 5.01 2 7.5 2z"/>
                    </svg>
                    <span class="max-xs:sr-only">Quay lại Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Bộ lọc</h3>
            </div>
            
            <div class="p-6">
                <form method="GET" action="{{ route('admin.notifications.list') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Priority Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Độ ưu tiên</label>
                            <select name="priority" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <option value="">Tất cả</option>
                                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Cao</option>
                                <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Bình thường</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Thấp</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trạng thái</label>
                            <select name="status" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <option value="">Tất cả</option>
                                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Đã gửi</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Đang chờ</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Thất bại</option>
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại thông báo</label>
                            <select name="type" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <option value="">Tất cả</option>
                                @foreach($notificationTypes as $type)
                                    <option value="{{ $type->name }}" {{ request('type') === $type->name ? 'selected' : '' }}>
                                        {{ $type->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Read Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trạng thái đọc</label>
                            <select name="read_status" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <option value="">Tất cả</option>
                                <option value="read" {{ request('read_status') === 'read' ? 'selected' : '' }}>Đã đọc</option>
                                <option value="unread" {{ request('read_status') === 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Từ ngày</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Đến ngày</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        </div>

                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tìm kiếm</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề hoặc nội dung..."
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.notifications.list') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg transition-colors duration-200">
                            Xóa bộ lọc
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Áp dụng
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div id="bulkActions" class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6 hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            Đã chọn <span id="selectedCount">0</span> thông báo
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="bulkAction('mark_read')" class="btn-sm bg-blue-500 hover:bg-blue-600 text-white">
                            Đánh dấu đã đọc
                        </button>
                        <button onclick="bulkAction('mark_unread')" class="btn-sm bg-yellow-500 hover:bg-yellow-600 text-white">
                            Đánh dấu chưa đọc
                        </button>
                        <button onclick="bulkAction('delete')" class="btn-sm bg-red-500 hover:bg-red-600 text-white">
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Thông báo ({{ $notifications->total() }})
                    </h3>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                        <label for="selectAll" class="text-sm text-gray-700 dark:text-gray-300">Chọn tất cả</label>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <input type="checkbox" class="rounded border-gray-300 text-violet-600">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Thông báo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Loại
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Độ ưu tiên
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Thời gian
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($notifications as $notification)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="notification_ids[]" value="{{ $notification->id }}" 
                                        onchange="updateBulkActions()" 
                                        class="notification-checkbox rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $notification->color }}20;">
                                            <span style="color: {{ $notification->color }}">{{ $notification->icon }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 {{ $notification->read_at ? '' : 'font-semibold' }}">
                                                {{ $notification->title }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-md">
                                                {{ $notification->message }}
                                            </div>
                                            @if($notification->data && is_array($notification->data))
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                    @if(isset($notification->data['booking_id']))
                                                        Booking: #{{ $notification->data['booking_id'] }}
                                                    @endif
                                                    @if(isset($notification->data['user_id']))
                                                        User: {{ $notification->data['user_id'] }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($notification->notificationType)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $notification->notificationType->title }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300">
                                            Tùy chỉnh
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notification->priority_badge }}">
                                        {{ ucfirst($notification->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $notification->status === 'sent' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 
                                               ($notification->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' : 
                                               'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300') }}">
                                            {{ $notification->status === 'sent' ? 'Đã gửi' : ($notification->status === 'pending' ? 'Đang chờ' : 'Thất bại') }}
                                        </span>
                                        @if(!$notification->read_at)
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div>{{ $notification->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs">{{ $notification->created_at->diffForHumans() }}</div>
                                    @if($notification->read_at)
                                        <div class="text-xs text-green-600 dark:text-green-400">
                                            Đã đọc: {{ $notification->read_at->format('d/m H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($notification->url && $notification->url !== '#')
                                            <a href="{{ $notification->url }}" target="_blank" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        @if(!$notification->read_at)
                                            <button onclick="markAsRead('{{ $notification->id }}')" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Đánh dấu đã đọc">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <button onclick="markAsUnread('{{ $notification->id }}')" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Đánh dấu chưa đọc">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        
                                        <button onclick="deleteNotification('{{ $notification->id }}')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Xóa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.81 7.81 0 01-.5-2.8A7.81 7.81 0 0115 12v5z"></path>
                                        </svg>
                                        <p class="font-medium">Không có thông báo nào</p>
                                        <p class="text-sm">Thử thay đổi bộ lọc để xem thêm kết quả</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div id="toast-icon" class="flex-shrink-0 w-6 h-6 mr-3"></div>
                <div id="toast-message" class="text-sm font-medium text-gray-900 dark:text-gray-100"></div>
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

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.notification-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateBulkActions();
        }

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.notification-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (checkboxes.length > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = checkboxes.length;
            } else {
                bulkActions.classList.add('hidden');
            }
        }

        async function bulkAction(action) {
            const checkboxes = document.querySelectorAll('.notification-checkbox:checked');
            const notificationIds = Array.from(checkboxes).map(cb => cb.value);
            
            if (notificationIds.length === 0) {
                alert('Vui lòng chọn ít nhất một thông báo');
                return;
            }

            const actionNames = {
                'mark_read': 'đánh dấu đã đọc',
                'mark_unread': 'đánh dấu chưa đọc',
                'delete': 'xóa'
            };

            if (!confirm(`Bạn có chắc chắn muốn ${actionNames[action]} ${notificationIds.length} thông báo đã chọn?`)) {
                return;
            }

            try {
                const response = await fetch('{{ route("admin.notifications.bulk.action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        action: action,
                        notification_ids: notificationIds
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('success', result.message);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Bulk action error:', error);
                showNotification('error', 'Có lỗi xảy ra khi thực hiện thao tác');
            }
        }

        async function markAsRead(notificationId) {
            try {
                const response = await fetch('{{ route("admin.notifications.bulk.action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        action: 'mark_read',
                        notification_ids: [notificationId]
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('success', 'Đã đánh dấu thông báo là đã đọc');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Mark as read error:', error);
                showNotification('error', 'Có lỗi xảy ra');
            }
        }

        async function markAsUnread(notificationId) {
            try {
                const response = await fetch('{{ route("admin.notifications.bulk.action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        action: 'mark_unread',
                        notification_ids: [notificationId]
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('success', 'Đã đánh dấu thông báo là chưa đọc');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Mark as unread error:', error);
                showNotification('error', 'Có lỗi xảy ra');
            }
        }

        async function deleteNotification(notificationId) {
            if (!confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
                return;
            }

            try {
                const response = await fetch('{{ route("admin.notifications.bulk.action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        action: 'delete',
                        notification_ids: [notificationId]
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('success', 'Đã xóa thông báo');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Delete notification error:', error);
                showNotification('error', 'Có lỗi xảy ra');
            }
        }

        function showNotification(type, message) {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toast-icon');
            const toastMessage = document.getElementById('toast-message');

            // Set icon based on type
            if (type === 'success') {
                toastIcon.innerHTML = '<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else {
                toastIcon.innerHTML = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            }

            toastMessage.textContent = message;
            toast.classList.remove('hidden');

            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    </script>
</x-app-layout>