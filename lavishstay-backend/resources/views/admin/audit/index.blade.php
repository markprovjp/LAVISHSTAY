<x-app-layout>
    <style>
        #modalPanel{
            z-index: 90;
        }
        .modal-overlay {
            /* z-index: 50; */
            background-color: rgba(0, 0, 0, 0.621);
        }
    </style>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Lịch sử thay đổi</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Theo dõi tất cả hoạt động và thay đổi dữ liệu trong hệ thống</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Export button -->
                <button onclick="showExportModal()" 
                    class="btn bg-green-500 hover:bg-green-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0v6L6 4 5 5l3 3 3-3-1-1-2 2V0H8zM1 14h14v2H1v-2z"/>
                    </svg>
                    <span class="max-xs:sr-only">Xuất dữ liệu</span>
                </button>
                
                <!-- Cleanup button -->
                <button onclick="showCleanupModal()"
                    class="btn bg-red-500 hover:bg-red-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M5 7h6v6H5V7zm1-4V1h4v2h4v2H0V3h4zM6 2v1h4V2H6z"/>
                    </svg>
                    <span class="max-xs:sr-only">Dọn dẹp</span>
                </button>

                <!-- Refresh button -->
                <button onclick="window.location.reload()"
                    class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 8 8 8.009 8.009 0 0 0-8-8zM8 14a6 6 0 1 1 6-6 6.007 6.007 0 0 1-6 6z"/>
                        <path d="M8 4a4 4 0 0 0-4 4h1a3 3 0 0 1 3-3V4z"/>
                    </svg>
                    <span class="max-xs:sr-only">Làm mới</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Logs -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-violet-100 dark:bg-violet-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng bản ghi</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($statistics['total_logs']) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Today's Logs -->
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
                            {{ number_format($statistics['today_logs']) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Unique Users -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Người dùng hoạt động</p>
                        <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($statistics['unique_users']) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Critical Actions -->
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
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Hành động quan trọng (24h)</p>
                        <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                            {{ number_format($statistics['recent_critical']) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Bộ lọc</h3>
            </div>
            
            <form method="GET" action="{{ route('admin.audit.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                            placeholder="Tìm kiếm...">
                    </div>

                    <!-- User Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Người dùng</label>
                        <select name="user_id"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <option value="">Tất cả người dùng</option>
                            @foreach($filterOptions['users'] as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Model Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Model</label>
                        <select name="model"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <option value="">Tất cả model</option>
                            @foreach($filterOptions['models'] as $model)
                                <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                                    {{ $model }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hành động</label>
                        <select name="action"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <option value="">Tất cả hành động</option>
                            @foreach($filterOptions['actions'] as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Khoảng thời gian</label>
                        <select name="date_range"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <option value="">Tất cả thời gian</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                            <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Hôm qua</option>
                            <option value="last_7_days" {{ request('date_range') == 'last_7_days' ? 'selected' : '' }}>7 ngày qua</option>
                            <option value="last_30_days" {{ request('date_range') == 'last_30_days' ? 'selected' : '' }}>30 ngày qua</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>Tháng này</option>
                            <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Tháng trước</option>
                            <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>Năm nay</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.audit.index') }}"
                        class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                        <span>Xóa bộ lọc</span>
                    </a>
                    
                    <button type="submit"
                        class="btn bg-violet-500 hover:bg-violet-600 text-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M7 14c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zM7 2C4.243 2 2 4.243 2 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5z"/>
                            <path d="m10.4 8.8-1.1-1.1c.4-.7.4-1.6 0-2.3-.4-.7-1.1-1.1-1.9-1.1s-1.5.4-1.9 1.1c-.4.7-.4 1.6 0 2.3l-1.1 1.1c-.8-1.1-.8-2.6 0-3.7.8-1.1 2.1-1.8 3.5-1.8s2.7.7 3.5 1.8c.8 1.1.8 2.6 0 3.7z"/>
                        </svg>
                        <span>Áp dụng</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Audit Logs Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Lịch sử hoạt động ({{ number_format($auditLogs->total()) }} bản ghi)
                    </h3>
                    
                    <!-- Dynamic Model Tabs from Config -->
                    <div class="flex flex-wrap gap-1">
                        <a href="{{ route('admin.audit.index') }}" 
                            class="px-3 py-1 text-sm rounded-lg transition-colors duration-200 {{ !request('model') ? 'bg-violet-100 text-violet-700 dark:bg-violet-400/30 dark:text-violet-300' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            Tất cả
                        </a>
                        @foreach($modelTabs as $modelName => $displayName)
                            <a href="{{ route('admin.audit.index', ['model' => $modelName]) }}" 
                                class="px-3 py-1 text-sm rounded-lg transition-colors duration-200 {{ request('model') == $modelName ? 'bg-violet-100 text-violet-700 dark:bg-violet-400/30 dark:text-violet-300' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                {{ $displayName }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Hành động
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Người dùng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Model
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Mô tả
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Thời gian
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <!-- Action -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-{{ $log->getActionColor() }}-100 dark:bg-{{ $log->getActionColor() }}-400/30 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-{{ $log->getActionColor() }}-600 dark:text-{{ $log->getActionColor() }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($log->action === 'create')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                @elseif($log->action === 'update')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                @elseif($log->action === 'delete')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @endif
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $log->getFormattedAction() }}
                                            </div>
                                            @if($log->is_sensitive)
                                                <div class="text-xs text-orange-600 dark:text-orange-400 flex items-center mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Nhạy cảm
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- User -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->user)
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-violet-400 to-purple-500 rounded-full flex items-center justify-center mr-3 text-white font-medium text-xs">
                                                {{ substr($log->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $log->user->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $log->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Hệ thống</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Model -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $log->getFormattedModel() }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        ID: {{ $log->model_id }}
                                    </div>
                                </td>

                                <!-- Description -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </div>
                                    @if($log->changes_summary)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs truncate" title="{{ $log->changes_summary }}">
                                            {{ $log->changes_summary }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $log->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $log->created_at->diffForHumans() }}
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- View Details Button -->
                                        <button onclick="showAuditDetail({{ $log->audit_id }})" 
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-violet-600 bg-violet-100 hover:bg-violet-200 dark:text-violet-400 dark:bg-violet-400/10 dark:hover:bg-violet-400/20 rounded-lg transition-colors duration-200"
                                            title="Xem chi tiết">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Chi tiết
                                        </button>

                                        <!-- Restore (only for delete actions) -->
                                        @if($log->action === 'delete')
                                            <button onclick="restoreRecord({{ $log->audit_id }})" 
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-600 bg-green-100 hover:bg-green-200 dark:text-green-400 dark:bg-green-400/10 dark:hover:bg-green-400/20 rounded-lg transition-colors duration-200"
                                                title="Khôi phục">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                Khôi phục
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Không có dữ liệu</h3>
                                        <p class="text-gray-500 dark:text-gray-400 max-w-sm">Không tìm thấy bản ghi audit log nào với bộ lọc hiện tại. Hãy thử điều chỉnh bộ lọc hoặc tạo một số hoạt động mới.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($auditLogs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $auditLogs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Audit Detail Modal -->
    <div id="auditDetailModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="modalBackdrop" class="fixed modal-overlay inset-0 backdrop-blur-sm transition-opacity duration-300 ease-out opacity-0" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div id="modalPanel" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out translate-y-4 opacity-0 sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full sm:p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100" id="modal-title">Chi tiết Audit Log</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Thông tin chi tiết về hoạt động này</p>
                        </div>
                    </div>
                    <button onclick="hideAuditDetailModal()" class="rounded-lg p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Loading State -->
                <div id="auditDetailLoading" class="flex flex-col items-center justify-center py-12">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-violet-200 dark:border-violet-800 rounded-full animate-spin"></div>
                        <div class="w-12 h-12 border-4 border-violet-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                    </div>
                    <p class="mt-4 text-gray-600 dark:text-gray-400 font-medium">Đang tải chi tiết...</p>
                </div>
                
                <!-- Modal Content -->
                <div id="auditDetailContent" class="hidden">
                    <!-- Basic Info Cards -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Basic Information Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Thông tin cơ bản</h4>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">ID:</span>
                                    <span id="detail-id" class="font-mono bg-white dark:bg-gray-800 px-2 py-1 rounded text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Hành động:</span>
                                    <span id="detail-action" class="font-medium px-2 py-1 rounded"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Model:</span>
                                    <span id="detail-model" class="font-medium text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Model ID:</span>
                                    <span id="detail-model-id" class="font-mono bg-white dark:bg-gray-800 px-2 py-1 rounded text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Thời gian:</span>
                                    <span id="detail-time" class="font-medium text-gray-900 dark:text-gray-100"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Information Card -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Người thực hiện</h4>
                            </div>
                            <div id="detail-user-info" class="space-y-3 text-sm">
                                <!-- User info will be populated here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description Card -->
                    <div class="mb-6">
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Mô tả hoạt động</h4>
                            </div>
                            <div id="detail-description" class="bg-white dark:bg-gray-800 rounded-lg p-4 text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700"></div>
                        </div>
                    </div>
                    
                    <!-- Changes Comparison -->
                    <div id="detail-changes" class="mb-6">
                        <div class="bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl p-6 border border-orange-200 dark:border-orange-800">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Thay đổi dữ liệu</h4>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <!-- Old Values -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                        Dữ liệu cũ
                                    </h5>
                                    <div id="detail-old-values" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 max-h-64 overflow-y-auto">
                                        <pre class="text-xs text-red-800 dark:text-red-200 whitespace-pre-wrap font-mono"></pre>
                                    </div>
                                </div>
                                
                                <!-- New Values -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                        Dữ liệu mới
                                    </h5>
                                    <div id="detail-new-values" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 max-h-64 overflow-y-auto">
                                        <pre class="text-xs text-green-800 dark:text-green-200 whitespace-pre-wrap font-mono"></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Technical Details -->
                    <div class="mb-6">
                        <div class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-900/20 dark:to-slate-900/20 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Thông tin kỹ thuật</h4>
                            </div>
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400 font-medium">IP Address:</span>
                                        <span id="detail-ip" class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-900 dark:text-gray-100"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400 font-medium">Session ID:</span>
                                        <span id="detail-session" class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-900 dark:text-gray-100 truncate max-w-32" title=""></span>
                                    </div>
                                    <div class="lg:col-span-2">
                                        <div class="flex justify-between items-start">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">URL:</span>
                                            <span id="detail-url" class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-900 dark:text-gray-100 break-all text-right max-w-md"></span>
                                        </div>
                                    </div>
                                    <div class="lg:col-span-2">
                                        <div class="flex justify-between items-start">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">User Agent:</span>
                                            <span id="detail-user-agent" class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-900 dark:text-gray-100 break-all text-right max-w-md text-xs"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Related Logs -->
                    <div id="detail-related" class="mb-6">
                        <div class="bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 rounded-xl p-6 border border-teal-200 dark:border-teal-800">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">Lịch sử liên quan</h4>
                            </div>
                            <div id="detail-related-content" class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <!-- Related logs will be populated here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button id="detail-restore-btn" onclick="restoreFromDetail()" class="hidden inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Khôi phục bản ghi
                        </button>
                        <button onclick="hideAuditDetailModal()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal" class="fixed inset-0 modal-overlay overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Xuất dữ liệu</h3>
                    <button onclick="hideExportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('admin.audit.export') }}" method="POST">
                    @csrf
                    
                    <!-- Include current filters -->
                    @foreach(request()->query() as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Định dạng</label>
                        <select name="format" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideExportModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-lg">
                            Xuất dữ liệu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cleanup Modal -->
    <div id="cleanupModal" class="fixed inset-0 modal-overlay overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Dọn dẹp dữ liệu cũ</h3>
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
                                Thao tác này sẽ xóa vĩnh viễn các bản ghi audit log cũ và không thể hoàn tác.
                            </div>
                        </div>
                    </div>
                    
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Xóa các bản ghi cũ hơn (ngày)
                    </label>
                    <input type="number" id="cleanupDays" min="30" max="3650" value="365" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tối thiểu 30 ngày, tối đa 10 năm</p>
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
        // Global variable to store current audit ID for restore
        let currentAuditId = null;

        // Add CSRF token to meta tag if not exists
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.getElementsByTagName('head')[0].appendChild(meta);
        }

        // Enhanced Modal Animation Functions
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            const backdrop = modal.querySelector('#modalBackdrop') || modal.querySelector('.fixed.inset-0');
            const panel = modal.querySelector('#modalPanel') || modal.querySelector('.inline-block');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Animate in
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
            const backdrop = modal.querySelector('#modalBackdrop') || modal.querySelector('.fixed.inset-0');
            const panel = modal.querySelector('#modalPanel') || modal.querySelector('.inline-block');
            
            // Animate out
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

        // Audit Detail Modal Functions
        async function showAuditDetail(auditId) {
            currentAuditId = auditId;
            showModal('auditDetailModal');
            document.getElementById('auditDetailLoading').classList.remove('hidden');
            document.getElementById('auditDetailContent').classList.add('hidden');
            
            try {
                const response = await fetch(`{{ route("admin.audit.show", ":id") }}`.replace(':id', auditId), {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch audit details');
                }
                
                const data = await response.json();
                populateAuditDetail(data);
                
            } catch (error) {
                console.error('Error fetching audit details:', error);
                showNotification('error', 'Không thể tải chi tiết audit log');
                hideAuditDetailModal();
            }
        }

        function populateAuditDetail(data) {
            const audit = data.audit;
            
            // Basic info
            document.getElementById('detail-id').textContent = audit.audit_id;
            document.getElementById('detail-action').textContent = audit.formatted_action;
            document.getElementById('detail-action').className = `font-medium px-2 py-1 rounded bg-${audit.action_color}-100 text-${audit.action_color}-800 dark:bg-${audit.action_color}-400/20 dark:text-${audit.action_color}-300`;
            document.getElementById('detail-model').textContent = audit.formatted_model;
            document.getElementById('detail-model-id').textContent = audit.model_id;
            document.getElementById('detail-time').textContent = new Date(audit.created_at).toLocaleString('vi-VN');
            
            // User info
            const userInfoDiv = document.getElementById('detail-user-info');
            if (audit.user) {
                userInfoDiv.innerHTML = `
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">Tên:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${audit.user.name}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">Email:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${audit.user.email}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">ID:</span>
                        <span class="font-mono bg-white dark:bg-gray-800 px-2 py-1 rounded text-gray-900 dark:text-gray-100">${audit.user.id}</span>
                    </div>
                `;
            } else {
                userInfoDiv.innerHTML = `
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="font-medium">Hệ thống</p>
                        <p class="text-xs">Thao tác tự động</p>
                    </div>
                `;
            }
            
            // Description
            document.getElementById('detail-description').textContent = audit.description || 'Không có mô tả chi tiết';
            
            // Changes
            const oldValuesDiv = document.getElementById('detail-old-values').querySelector('pre');
            const newValuesDiv = document.getElementById('detail-new-values').querySelector('pre');
            
            if (audit.old_values && Object.keys(audit.old_values).length > 0) {
                oldValuesDiv.textContent = JSON.stringify(audit.old_values, null, 2);
            } else {
                oldValuesDiv.textContent = 'Không có dữ liệu cũ';
                oldValuesDiv.parentElement.classList.add('opacity-50');
            }
            
            if (audit.new_values && Object.keys(audit.new_values).length > 0) {
                newValuesDiv.textContent = JSON.stringify(audit.new_values, null, 2);
            } else {
                newValuesDiv.textContent = 'Không có dữ liệu mới';
                newValuesDiv.parentElement.classList.add('opacity-50');
            }
            
            // Technical details
            document.getElementById('detail-ip').textContent = audit.ip_address || 'N/A';
            const sessionElement = document.getElementById('detail-session');
            sessionElement.textContent = audit.session_id ? audit.session_id.substring(0, 16) + '...' : 'N/A';
            sessionElement.title = audit.session_id || '';
            document.getElementById('detail-url').textContent = audit.url || 'N/A';
            document.getElementById('detail-user-agent').textContent = audit.user_agent || 'N/A';
            
            // Related logs
            const relatedDiv = document.getElementById('detail-related-content');
            if (data.related_logs && data.related_logs.length > 0) {
                let relatedHtml = '<div class="space-y-2">';
                data.related_logs.forEach(log => {
                    relatedHtml += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-${log.action_color}-500 rounded-full"></div>
                                <div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">${log.formatted_action}</span>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">${new Date(log.created_at).toLocaleString('vi-VN')} • ${log.user}</div>
                                </div>
                            </div>
                            <button onclick="showAuditDetail(${log.audit_id})" class="text-violet-600 hover:text-violet-800 dark:text-violet-400 dark:hover:text-violet-300 text-xs font-medium px-2 py-1 rounded hover:bg-violet-100 dark:hover:bg-violet-400/20 transition-colors duration-200">
                                Xem chi tiết
                            </button>
                        </div>
                    `;
                });
                relatedHtml += '</div>';
                relatedDiv.innerHTML = relatedHtml;
            } else {
                relatedDiv.innerHTML = `
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <p class="font-medium">Không có lịch sử liên quan</p>
                        <p class="text-sm">Chưa có hoạt động nào khác trên bản ghi này</p>
                    </div>
                `;
            }
            
            // Show/hide restore button
            const restoreBtn = document.getElementById('detail-restore-btn');
            if (audit.action === 'delete') {
                restoreBtn.classList.remove('hidden');
            } else {
                restoreBtn.classList.add('hidden');
            }
            
            // Show content, hide loading
            document.getElementById('auditDetailLoading').classList.add('hidden');
            document.getElementById('auditDetailContent').classList.remove('hidden');
        }

        function hideAuditDetailModal() {
            hideModal('auditDetailModal');
            currentAuditId = null;
        }

        function restoreFromDetail() {
            if (currentAuditId) {
                restoreRecord(currentAuditId);
            }
        }

        // Export Modal Functions
        function showExportModal() {
            showModal('exportModal');
        }

        function hideExportModal() {
            hideModal('exportModal');
        }

        // Cleanup Modal Functions
        function showCleanupModal() {
            showModal('cleanupModal');
        }

        function hideCleanupModal() {
            hideModal('cleanupModal');
        }

        // Perform Cleanup
        async function performCleanup() {
            const days = document.getElementById('cleanupDays').value;
            
            if (!confirm(`Bạn có chắc chắn muốn xóa tất cả audit logs cũ hơn ${days} ngày? Thao tác này không thể hoàn tác.`)) {
                return;
            }

            try {
                const response = await fetch('{{ route("admin.audit.cleanup") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ days: parseInt(days) })
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

        // Restore Record
        async function restoreRecord(auditId) {
            if (!confirm('Bạn có chắc chắn muốn khôi phục bản ghi này?')) {
                return;
            }

            try {
                const response = await fetch(`{{ route("admin.audit.restore", ":id") }}`.replace(':id', auditId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('success', result.message);
                    hideAuditDetailModal();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showNotification('error', result.message);
                }
            } catch (error) {
                console.error('Restore error:', error);
                showNotification('error', 'Có lỗi xảy ra khi khôi phục bản ghi');
            }
        }

        // Enhanced Notification system
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
            // Close audit detail modal
            const auditModal = document.getElementById('auditDetailModal');
            const auditBackdrop = document.getElementById('modalBackdrop');
            if (event.target === auditBackdrop && !auditModal.classList.contains('hidden')) {
                hideAuditDetailModal();
            }
            
            // Close export modal
            const exportModal = document.getElementById('exportModal');
            if (event.target === exportModal && !exportModal.classList.contains('hidden')) {
                hideExportModal();
            }
            
            // Close cleanup modal
            const cleanupModal = document.getElementById('cleanupModal');
            if (event.target === cleanupModal && !cleanupModal.classList.contains('hidden')) {
                hideCleanupModal();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            // ESC key to close modals
            if (event.key === 'Escape') {
                const auditModal = document.getElementById('auditDetailModal');
                const exportModal = document.getElementById('exportModal');
                const cleanupModal = document.getElementById('cleanupModal');
                
                if (!auditModal.classList.contains('hidden')) {
                    hideAuditDetailModal();
                } else if (!exportModal.classList.contains('hidden')) {
                    hideExportModal();
                } else if (!cleanupModal.classList.contains('hidden')) {
                    hideCleanupModal();
                }
            }
            
            // Ctrl/Cmd + E for export
            if ((event.ctrlKey || event.metaKey) && event.key === 'e') {
                event.preventDefault();
                showExportModal();
            }
            
            // Ctrl/Cmd + R for refresh (prevent default and use our refresh)
            if ((event.ctrlKey || event.metaKey) && event.key === 'r') {
                event.preventDefault();
                window.location.reload();
            }
        });

        // Auto-refresh functionality (optional)
        let autoRefreshInterval = null;
        
        function startAutoRefresh(intervalMinutes = 5) {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
            
            autoRefreshInterval = setInterval(() => {
                // Only refresh if no modals are open
                const auditModal = document.getElementById('auditDetailModal');
                const exportModal = document.getElementById('exportModal');
                const cleanupModal = document.getElementById('cleanupModal');
                
                if (auditModal.classList.contains('hidden') && 
                    exportModal.classList.contains('hidden') && 
                    cleanupModal.classList.contains('hidden')) {
                    
                    // Show a subtle notification before refresh
                    showNotification('info', 'Đang cập nhật dữ liệu mới...');
                    setTimeout(() => window.location.reload(), 1000);
                }
            }, intervalMinutes * 60 * 1000);
        }
        
        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
        }

        // Initialize tooltips for truncated text
        function initializeTooltips() {
            const truncatedElements = document.querySelectorAll('.truncate[title]');
            truncatedElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    if (this.scrollWidth > this.clientWidth) {
                        // Element is actually truncated, show tooltip
                        this.setAttribute('data-tooltip', this.getAttribute('title'));
                    }
                });
            });
        }

        // Enhanced search functionality
        function initializeSearch() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                let searchTimeout;
                
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    
                    if (query.length >= 3) {
                        searchTimeout = setTimeout(() => {
                            // Auto-submit form after 1 second of no typing
                            this.form.submit();
                        }, 1000);
                    }
                });
                
                // Clear search on Escape
                searchInput.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        this.value = '';
                        this.form.submit();
                    }
                });
            }
        }

        // Table row selection functionality
        function initializeRowSelection() {
            const tableRows = document.querySelectorAll('tbody tr[data-audit-id]');
            let selectedRows = new Set();
            
            tableRows.forEach(row => {
                row.addEventListener('click', function(event) {
                    // Don't select if clicking on buttons or links
                    if (event.target.closest('button') || event.target.closest('a')) {
                        return;
                    }
                    
                    const auditId = this.getAttribute('data-audit-id');
                    
                    if (event.ctrlKey || event.metaKey) {
                        // Multi-select with Ctrl/Cmd
                        if (selectedRows.has(auditId)) {
                            selectedRows.delete(auditId);
                            this.classList.remove('bg-violet-50', 'dark:bg-violet-900/20');
                        } else {
                            selectedRows.add(auditId);
                            this.classList.add('bg-violet-50', 'dark:bg-violet-900/20');
                        }
                    } else {
                        // Single select
                        tableRows.forEach(r => r.classList.remove('bg-violet-50', 'dark:bg-violet-900/20'));
                        selectedRows.clear();
                        selectedRows.add(auditId);
                        this.classList.add('bg-violet-50', 'dark:bg-violet-900/20');
                    }
                    
                    updateBulkActions();
                });
            });
            
            function updateBulkActions() {
                // Show/hide bulk action buttons based on selection
                const bulkActionsDiv = document.getElementById('bulkActions');
                if (bulkActionsDiv) {
                    if (selectedRows.size > 0) {
                        bulkActionsDiv.classList.remove('hidden');
                        bulkActionsDiv.querySelector('.selected-count').textContent = selectedRows.size;
                    } else {
                        bulkActionsDiv.classList.add('hidden');
                    }
                }
            }
        }

        // Performance monitoring
        function initializePerformanceMonitoring() {
            // Monitor page load time
            window.addEventListener('load', function() {
                const loadTime = performance.now();
                if (loadTime > 3000) { // If page takes more than 3 seconds
                    console.warn('Audit page loaded slowly:', loadTime + 'ms');
                }
            });
            
            // Monitor AJAX request performance
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                const startTime = performance.now();
                return originalFetch.apply(this, args).then(response => {
                    const endTime = performance.now();
                    const duration = endTime - startTime;
                    
                    if (duration > 2000) { // If request takes more than 2 seconds
                        console.warn('Slow AJAX request:', args[0], duration + 'ms');
                    }
                    
                    return response;
                });
            };
        }

        // Initialize all functionality when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeTooltips();
            initializeSearch();
            initializeRowSelection();
            initializePerformanceMonitoring();
            
            // Start auto-refresh if enabled (uncomment to enable)
            // startAutoRefresh(5); // Refresh every 5 minutes
            
            // Show welcome message for first-time visitors
            if (!localStorage.getItem('audit_visited')) {
                setTimeout(() => {
                    showNotification('info', 'Chào mừng đến với trang Audit Log! Sử dụng các bộ lọc để tìm kiếm thông tin cần thiết.');
                    localStorage.setItem('audit_visited', 'true');
                }, 1000);
            }
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
        });

        // Export functions to global scope for inline event handlers
        window.showAuditDetail = showAuditDetail;
        window.hideAuditDetailModal = hideAuditDetailModal;
        window.restoreRecord = restoreRecord;
        window.restoreFromDetail = restoreFromDetail;
        window.showExportModal = showExportModal;
        window.hideExportModal = hideExportModal;
        window.showCleanupModal = showCleanupModal;
        window.hideCleanupModal = hideCleanupModal;
        window.performCleanup = performCleanup;
        window.showNotification = showNotification;
    </script>
</x-app-layout>