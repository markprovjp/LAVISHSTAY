<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Yêu cầu đặc biệt</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý và duyệt các yêu cầu đặc biệt từ lễ tân</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Refresh button -->
                <button onclick="window.location.reload()"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
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
            <!-- Total Requests -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng yêu cầu</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $statistics['total_requests'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Chờ duyệt</p>
                        <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">
                            {{ $statistics['pending_requests'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Approved Requests -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Đã duyệt</p>
                        <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                            {{ $statistics['approved_requests'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Today Requests -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Hôm nay</p>
                        <p class="text-2xl font-semibold text-purple-600 dark:text-purple-400">
                            {{ $statistics['today_requests'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="py-5">
            <form method="GET" action="{{ route('admin.special-requests') }}" class="flex flex-wrap gap-4">
                <!-- Booking Code Search -->
                <div class="flex-1 min-w-64">
                    <input type="text" name="booking_code" value="{{ request('booking_code') }}"
                        placeholder="Tìm kiếm theo mã đặt phòng..."
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Request Type Filter -->
                <div>
                    <select name="request_type"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả loại yêu cầu</option>
                        @foreach($requestTypes as $type => $label)
                            <option value="{{ $type }}" {{ request('request_type') === $type ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả trạng thái</option>
                        @foreach($statuses as $status => $label)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <button type="submit"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <span>Lọc</span>
                </button>

                <!-- Clear Filters -->
                @if (request()->hasAny(['booking_code', 'request_type', 'status']))
                    <a href="{{ route('admin.special-requests') }}"
                        class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                        Xóa bộ lọc
                    </a>
                @endif
            </form>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 flex items-center p-4 rounded-lg bg-green-50 border-l-4 border-green-500">
                <div class="ml-3">
                    <h3 class="font-semibold text-green-700">Thành công!</h3>
                    <div class="text-sm text-green-600">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-4 flex items-center p-4 rounded-lg bg-red-50 border-l-4 border-red-500">
                <div class="ml-3">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="">
                <table class="table-auto w-full dark:text-gray-300">
                    <!-- Table header -->
                    <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">Loại yêu cầu</th>
                            <th class="px-6 py-4 text-left">Mã đặt phòng</th>
                            <th class="px-6 py-4 text-left">Khách hàng</th>
                            <th class="px-6 py-4 text-left">Lý do</th>
                            <th class="px-6 py-4 text-left">Số tiền</th>
                            <th class="px-6 py-4 text-left">Người yêu cầu</th>
                            <th class="px-6 py-4 text-left">Trạng thái</th>
                            <th class="px-6 py-4 text-left">Thời gian</th>
                            <th class="px-6 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($paginatedRequests as $request)
                            @php
                                $displayData = $request->getDisplayData();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <!-- Request Type -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $displayData['type_label'] }}
                                            </div>
                                            @if($displayData['has_attachments'])
                                                <div class="text-xs text-blue-600 dark:text-blue-400">
                                                    {{ $displayData['attachments_count'] }} file
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Booking Code -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                        {{ $displayData['booking_code'] }}
                                    </div>
                                </td>

                                <!-- Guest Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $displayData['guest_name'] }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Reason -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $displayData['reason'] }}">
                                        {{ $displayData['reason'] }}
                                    </div>
                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($displayData['requested_amount'])
                                        <div class="text-sm font-semibold text-red-600 dark:text-red-400">
                                            {{ $displayData['formatted_requested_amount'] }}
                                        </div>
                                        @if($displayData['approved_amount'] && $displayData['status'] === 'approved')
                                            <div class="text-xs text-green-600 dark:text-green-400">
                                                Duyệt: {{ $displayData['formatted_approved_amount'] }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">N/A</span>
                                    @endif
                                </td>

                                <!-- Requested By -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $displayData['requested_by'] }}
                                    </div>
                                    @if($displayData['approved_by'])
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Duyệt: {{ $displayData['approved_by'] }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($displayData['status'] === 'pending')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-400/30 text-yellow-800 dark:text-yellow-400">
                                            Chờ duyệt
                                        </span>
                                    @elseif($displayData['status'] === 'approved')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400">
                                            Đã duyệt
                                        </span>
                                    @elseif($displayData['status'] === 'rejected')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-400/30 text-red-800 dark:text-red-400">
                                            Từ chối
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-400/30 text-gray-800 dark:text-gray-400">
                                            {{ $displayData['status_label'] }}
                                        </span>
                                    @endif
                                    
                                    @if($displayData['priority'] <= 2)
                                        <div class="mt-1">
                                            <span class="inline-flex px-1 py-0.5 text-xs font-medium rounded bg-red-100 dark:bg-red-400/30 text-red-800 dark:text-red-400">
                                                Ưu tiên
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $displayData['created_at']->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $displayData['created_at']->format('H:i') }}
                                    </div>
                                    @if($displayData['approved_at'])
                                        <div class="text-xs text-green-600 dark:text-green-400">
                                            Duyệt: {{ $displayData['approved_at']->format('d/m H:i') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500"
                                            onclick="viewRequestDetail('{{ $displayData['type'] }}', {{ $displayData['id'] }})">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <div class="text-lg text-gray-500 dark:text-gray-400 mb-2">Không có yêu cầu đặc biệt nào</div>
                                        <div class="text-sm text-gray-400 dark:text-gray-500">Chưa có yêu cầu đặc biệt nào được tìm thấy</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($pagination['has_pages'])
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Hiển thị {{ $pagination['from'] }}-{{ $pagination['to'] }} của {{ $pagination['total'] }} yêu cầu
                        </div>
                        <div class="flex space-x-1">
                            @if ($pagination['current_page'] > 1)
                                <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}" 
                                   class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Trước
                                </a>
                            @endif
                            
                            @for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++)
                                <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                                   class="px-3 py-2 text-sm {{ $i == $pagination['current_page'] ? 'text-white bg-violet-600' : 'text-gray-500 bg-white hover:bg-gray-50' }} border border-gray-300 rounded-md">
                                    {{ $i }}
                                </a>
                            @endfor
                            
                            @if ($pagination['current_page'] < $pagination['last_page'])
                                <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}" 
                                   class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Sau
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Request Detail Modal -->
    <div id="request-detail-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-6xl w-full max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-container">
                
                <!-- Modal Header -->
                <div class="relative bg-gradient-to-r from-violet-600 to-purple-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Chi tiết yêu cầu đặc biệt</h3>
                                <p class="text-violet-100 text-sm" id="modal-subtitle">Thông tin chi tiết và lịch sử xử lý</p>
                            </div>
                        </div>
                        <button onclick="closeModal()" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Status Indicator -->
                    <div class="absolute top-8 right-32">
                        <div id="modal-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white">
                            <!-- Status will be inserted here -->
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="flex flex-col lg:flex-row h-full max-h-[calc(95vh-120px)]">
                    
                    <!-- Left Panel - Main Content -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="p-8">
                            
                            <!-- Loading State -->
                            <div id="modal-loading" class="flex items-center justify-center py-12">
                                <div class="flex items-center space-x-3">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-violet-600"></div>
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Đang tải thông tin...</span>
                                </div>
                            </div>

                            <!-- Content Container -->
                            <div id="modal-content" class="hidden space-y-8">
                                
                                <!-- Request Overview Card -->
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div id="request-type-icon" class="w-10 h-10 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center">
                                                <!-- Icon will be inserted here -->
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100" id="request-type-label">
                                                    <!-- Type label will be inserted here -->
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400" id="request-created-time">
                                                    <!-- Created time will be inserted here -->
                                                </p>
                                            </div>
                                        </div>
                                        <div id="priority-badge" class="hidden px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Ưu tiên cao
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="bg-white dark:bg-gray-600 rounded-lg p-4">
                                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Mã đặt phòng</div>
                                            <div class="text-lg font-semibold text-blue-600 dark:text-blue-400 mt-1" id="booking-code">
                                                <!-- Booking code will be inserted here -->
                                            </div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-600 rounded-lg p-4">
                                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Khách hàng</div>
                                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1" id="guest-name">
                                                <!-- Guest name will be inserted here -->
                                            </div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-600 rounded-lg p-4" id="amount-card">
                                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Số tiền</div>
                                            <div class="text-lg font-semibold text-red-600 dark:text-red-400 mt-1" id="requested-amount">
                                                <!-- Amount will be inserted here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Details Card -->
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Thông tin đặt phòng
                                    </h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="booking-details">
                                        <!-- Booking details will be inserted here -->
                                    </div>
                                </div>

                                <!-- Request Details Card -->
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Chi tiết yêu cầu
                                    </h5>
                                    <div id="request-details">
                                        <!-- Request details will be inserted here -->
                                    </div>
                                </div>

                                <!-- Staff Information Card -->
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Thông tin nhân viên
                                    </h5>
                                    <div id="staff-details">
                                        <!-- Staff details will be inserted here -->
                                    </div>
                                </div>

                                <!-- Attachments Card (if any) -->
                                <div id="attachments-card" class="hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        Tệp đính kèm
                                    </h5>
                                    <div id="attachments-list">
                                        <!-- Attachments will be inserted here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Timeline & Actions -->
                    <div class="w-full lg:w-80 bg-gray-50 dark:bg-gray-900 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700">
                        <div class="p-6 h-full flex flex-col">
                            
                            <!-- Timeline Section -->
                            <div class="flex-1">
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Lịch sử xử lý
                                </h5>
                                <div id="timeline-container" class="space-y-4">
                                    <!-- Timeline will be inserted here -->
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                                <div class="space-y-3" id="modal-actions">
                                    <!-- Action buttons will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approval-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-60 modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Duyệt yêu cầu</h3>
                        <button onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form id="approval-form" class="space-y-4">
                        <div id="amount-input-container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Số tiền duyệt <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="approved-amount" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập số tiền duyệt">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ghi chú quản lý
                            </label>
                            <textarea id="approval-note" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập ghi chú (tùy chọn)"></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeApprovalModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Hủy
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
                                Duyệt yêu cầu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejection-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-60 modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Từ chối yêu cầu</h3>
                        <button onclick="closeRejectionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form id="rejection-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Lý do từ chối <span class="text-red-500">*</span>
                            </label>
                            <textarea id="rejection-note" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Nhập lý do từ chối yêu cầu"></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeRejectionModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Hủy
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                                Từ chối yêu cầu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        let currentRequestType = '';
        let currentRequestId = 0;

        // View request detail with enhanced modal
        async function viewRequestDetail(type, id) {
            currentRequestType = type;
            currentRequestId = id;

            const modal = document.getElementById('request-detail-modal');
            const modalContainer = document.getElementById('modal-container');
            const modalLoading = document.getElementById('modal-loading');
            const modalContent = document.getElementById('modal-content');

            // Show modal with animation
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContainer.classList.remove('scale-95', 'opacity-0');
                modalContainer.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Show loading state
            modalLoading.classList.remove('hidden');
            modalContent.classList.add('hidden');

            try {
                const response = await fetch(`{{ route('admin.special-requests.show', ['type' => '__TYPE__', 'id' => '__ID__']) }}`.replace('__TYPE__', type).replace('__ID__', id));
                const result = await response.json();

                if (result.success) {
                    renderEnhancedRequestDetail(result.data);
                } else {
                    showError('Lỗi: ' + result.message);
                    closeModal();
                }
            } catch (error) {
                console.error('Error loading request detail:', error);
                showError('Có lỗi xảy ra khi tải chi tiết yêu cầu');
                closeModal();
            }
        }

        // Render enhanced request detail
        function renderEnhancedRequestDetail(data) {
            const modalLoading = document.getElementById('modal-loading');
            const modalContent = document.getElementById('modal-content');

            // Hide loading, show content
            modalLoading.classList.add('hidden');
            modalContent.classList.remove('hidden');

            // Update modal subtitle
            document.getElementById('modal-subtitle').textContent = `${data.type_label} - ${data.booking_details.booking_code}`;

            // Update status badge
            const statusBadge = document.getElementById('modal-status-badge');
            const statusClasses = {
                'pending': 'bg-yellow-500/20 text-yellow-100',
                'approved': 'bg-green-500/20 text-green-100',
                'rejected': 'bg-red-500/20 text-red-100',
                'applied': 'bg-blue-500/20 text-blue-100'
            };
            statusBadge.className = `px-3 py-1 rounded-full text-xs font-semibold ${statusClasses[data.status] || 'bg-white/20 text-white'}`;
            statusBadge.textContent = data.status_label;

            // Update request overview
            document.getElementById('request-type-label').textContent = data.type_label;
            document.getElementById('request-created-time').textContent = `Tạo lúc: ${formatDateTime(data.created_at)}`;
            document.getElementById('booking-code').textContent = data.booking_details.booking_code;
            document.getElementById('guest-name').textContent = data.booking_details.guest_name;

            // Update request type icon
            const typeIcon = document.getElementById('request-type-icon');
            const iconClasses = {
                'checkout_compensation': 'bg-red-100 dark:bg-red-400/30',
                'booking_reschedule': 'bg-blue-100 dark:bg-blue-400/30',
                'checkin_special': 'bg-green-100 dark:bg-green-400/30',
                'cancellation_special': 'bg-red-100 dark:bg-red-400/30',
                'extension_special': 'bg-purple-100 dark:bg-purple-400/30',
                'room_transfer': 'bg-orange-100 dark:bg-orange-400/30'
            };
            typeIcon.className = `w-10 h-10 ${iconClasses[data.type] || 'bg-gray-100 dark:bg-gray-400/30'} rounded-lg flex items-center justify-center`;

            // Update amount
            const amountCard = document.getElementById('amount-card');
            const requestedAmount = document.getElementById('requested-amount');
            if (data.requested_amount) {
                requestedAmount.innerHTML = `
                    <div>${data.formatted_requested_amount}</div>
                    ${data.approved_amount && data.status === 'approved' ? 
                        `<div class="text-sm text-green-600 dark:text-green-400 mt-1">Duyệt: ${data.formatted_approved_amount}</div>` : 
                        ''
                    }
                `;
                amountCard.classList.remove('hidden');
            } else {
                amountCard.classList.add('hidden');
            }

            // Update priority badge
            const priorityBadge = document.getElementById('priority-badge');
            if (data.priority <= 2) {
                priorityBadge.classList.remove('hidden');
            } else {
                priorityBadge.classList.add('hidden');
            }

            // Update booking details
            const bookingDetails = document.getElementById('booking-details');
            bookingDetails.innerHTML = `
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                        <span class="text-sm text-gray-900 dark:text-gray-100">${data.booking_details.guest_email || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-in:</span>
                        <span class="text-sm text-gray-900 dark:text-gray-100">${formatDate(data.booking_details.check_in_date)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-out:</span>
                        <span class="text-sm text-gray-900 dark:text-gray-100">${formatDate(data.booking_details.check_out_date)}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Trạng thái booking:</span>
                        <span class="text-sm text-gray-900 dark:text-gray-100">${data.booking_details.status}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng tiền:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">${formatCurrency(data.booking_details.total_price_vnd)}</span>
                    </div>
                </div>
            `;

            // Update request details
            const requestDetails = document.getElementById('request-details');
            let detailsHtml = `
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    <h6 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Lý do yêu cầu:</h6>
                    <p class="text-gray-700 dark:text-gray-300">${data.reason || 'Không có lý do cụ thể'}</p>
                </div>
            `;

            if (data.admin_note) {
                detailsHtml += `
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <h6 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Ghi chú quản lý:</h6>
                        <p class="text-blue-800 dark:text-blue-200">${data.admin_note}</p>
                    </div>
                `;
            }

            requestDetails.innerHTML = detailsHtml;

            // Update staff details
            const staffDetails = document.getElementById('staff-details');
            staffDetails.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h6 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Người yêu cầu:</h6>
                        <p class="text-gray-700 dark:text-gray-300">${data.staff_details.requested_by?.name || 'N/A'}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${data.staff_details.requested_by?.email || ''}</p>
                    </div>
                    ${data.staff_details.approved_by ? `
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <h6 class="font-medium text-green-900 dark:text-green-100 mb-2">Người duyệt:</h6>
                            <p class="text-green-800 dark:text-green-200">${data.staff_details.approved_by.name}</p>
                            <p class="text-xs text-green-600 dark:text-green-400 mt-1">${data.staff_details.approved_by.email}</p>
                        </div>
                    ` : ''}
                </div>
            `;

            // Update timeline
            renderTimeline(data);

            // Update action buttons
            renderActionButtons(data);
        }

        // Render timeline
        function renderTimeline(data) {
            const timelineContainer = document.getElementById('timeline-container');
            let timelineHtml = '';

            // Created event
            timelineHtml += `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-400/30 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Yêu cầu được tạo</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${formatDateTime(data.created_at)}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">Bởi: ${data.staff_details.requested_by?.name || 'N/A'}</div>
                    </div>
                </div>
            `;

            // Status change events
            if (data.status === 'approved' && data.approved_at) {
                timelineHtml += `
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-400/30 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-green-900 dark:text-green-100">Yêu cầu được duyệt</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">${formatDateTime(data.approved_at)}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">Bởi: ${data.staff_details.approved_by?.name || 'N/A'}</div>
                        </div>
                    </div>
                `;
            } else if (data.status === 'rejected' && data.approved_at) {
                timelineHtml += `
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-400/30 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-red-900 dark:text-red-100">Yêu cầu bị từ chối</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">${formatDateTime(data.approved_at)}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">Bởi: ${data.staff_details.approved_by?.name || 'N/A'}</div>
                        </div>
                    </div>
                `;
            }

            timelineContainer.innerHTML = timelineHtml;
        }

        // Render action buttons
        function renderActionButtons(data) {
            const modalActions = document.getElementById('modal-actions');
            let actionsHtml = `
                <button onclick="closeModal()" 
                    class="w-full px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Đóng
                </button>
            `;

            if (data.can_reject) {
                actionsHtml += `
                    <button onclick="showRejectionModal()" 
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Từ chối
                    </button>
                `;
            }

            if (data.can_approve) {
                actionsHtml += `
                    <button onclick="showApprovalModal()" 
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Duyệt yêu cầu
                    </button>
                `;
            }

            modalActions.innerHTML = actionsHtml;
        }

        // Show approval modal
        function showApprovalModal() {
            const approvalModal = document.getElementById('approval-modal');
            const amountContainer = document.getElementById('amount-input-container');
            
            // Show amount input for compensation requests
            if (currentRequestType === 'checkout_compensation') {
                amountContainer.classList.remove('hidden');
                document.getElementById('approved-amount').required = true;
            } else {
                amountContainer.classList.add('hidden');
                document.getElementById('approved-amount').required = false;
            }
            
            approvalModal.classList.remove('hidden');
        }

        // Close approval modal
        function closeApprovalModal() {
            document.getElementById('approval-modal').classList.add('hidden');
            document.getElementById('approval-form').reset();
        }

        // Show rejection modal
        function showRejectionModal() {
            document.getElementById('rejection-modal').classList.remove('hidden');
        }

        // Close rejection modal
        function closeRejectionModal() {
            document.getElementById('rejection-modal').classList.add('hidden');
            document.getElementById('rejection-form').reset();
        }

        // Handle approval form submit
        document.getElementById('approval-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const approvedAmount = document.getElementById('approved-amount').value;
            const note = document.getElementById('approval-note').value;
            
            if (currentRequestType === 'checkout_compensation' && !approvedAmount) {
                showError('Vui lòng nhập số tiền duyệt');
                return;
            }

            try {
                const data = { admin_note: note };
                if (approvedAmount) data.approved_amount = parseFloat(approvedAmount);

                const response = await fetch(`{{ route('admin.special-requests.approve', ['type' => '__TYPE__', 'id' => '__ID__']) }}`.replace('__TYPE__', currentRequestType).replace('__ID__', currentRequestId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess('Đã duyệt yêu cầu thành công!');
                    closeApprovalModal();
                    closeModal();
                    window.location.reload();
                } else {
                    showError('Lỗi: ' + result.message);
                }
            } catch (error) {
                console.error('Error approving request:', error);
                showError('Có lỗi xảy ra khi duyệt yêu cầu');
            }
        });

        // Handle rejection form submit
        document.getElementById('rejection-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const note = document.getElementById('rejection-note').value;
            
            if (!note.trim()) {
                showError('Vui lòng nhập lý do từ chối');
                return;
            }

            try {
                const response = await fetch(`{{ route('admin.special-requests.reject', ['type' => '__TYPE__', 'id' => '__ID__']) }}`.replace('__TYPE__', currentRequestType).replace('__ID__', currentRequestId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ admin_note: note })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess('Đã từ chối yêu cầu!');
                    closeRejectionModal();
                    closeModal();
                    window.location.reload();
                } else {
                    showError('Lỗi: ' + result.message);
                }
            } catch (error) {
                console.error('Error rejecting request:', error);
                showError('Có lỗi xảy ra khi từ chối yêu cầu');
            }
        });

        // Close modal with animation
        function closeModal() {
            const modal = document.getElementById('request-detail-modal');
            const modalContainer = document.getElementById('modal-container');
            
            modalContainer.classList.remove('scale-100', 'opacity-100');
            modalContainer.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Utility functions
        function formatDateTime(dateTime) {
            if (!dateTime) return 'N/A';
            return new Date(dateTime).toLocaleString('vi-VN');
        }

        function formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('vi-VN');
        }

        function formatCurrency(amount) {
            if (!amount) return 'N/A';
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        }

        // Show success/error notifications
        function showSuccess(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-50 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-lg max-w-sm';
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
            setTimeout(() => notification.remove(), 5000);
        }

        function showError(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-50 transform transition-all duration-300 ease-out flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-lg max-w-sm';
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
            setTimeout(() => notification.remove(), 5000);
        }

        // Handle ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeApprovalModal();
                closeRejectionModal();
            }
        });

        // Close modals when clicking outside
        document.getElementById('request-detail-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        document.getElementById('approval-modal').addEventListener('click', function(e) {
            if (e.target === this) closeApprovalModal();
        });

        document.getElementById('rejection-modal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectionModal();
        });
    </script>

    <style>
        .modal-overlay {
                z-index: 50;
                background-color: rgba(0, 0, 0, 0.621);
        }
    </style>

    <!-- Enhanced JavaScript for better UX -->
    <script>
        // Enhanced notification system
        function showEnhancedSuccess(message) {
            const notification = createNotification('success', 'Thành công!', message);
            document.body.appendChild(notification);
            
            // Trigger animation
            setTimeout(() => {
                notification.classList.add('notification-enter');
            }, 10);
            
            // Auto remove
            setTimeout(() => {
                removeNotification(notification);
            }, 5000);
        }

        function showEnhancedError(message) {
            const notification = createNotification('error', 'Lỗi!', message);
            document.body.appendChild(notification);
            
            // Trigger animation
            setTimeout(() => {
                notification.classList.add('notification-enter');
            }, 10);
            
            // Auto remove
            setTimeout(() => {
                removeNotification(notification);
            }, 7000);
        }

        function createNotification(type, title, message) {
            const notification = document.createElement('div');
            const isError = type === 'error';
            
            notification.className = `fixed top-4 right-4 z-50 transform transition-all duration-300 ease-out flex items-start p-4 rounded-xl shadow-2xl max-w-sm ${
                isError 
                    ? 'bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500' 
                    : 'bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center justify-center w-10 h-10 ${isError ? 'text-red-500' : 'text-green-500'} bg-white rounded-lg shadow-sm">
                    ${isError ? `
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    ` : `
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    `}
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="font-semibold ${isError ? 'text-red-700' : 'text-green-700'}">${title}</h3>
                    <div class="text-sm ${isError ? 'text-red-600' : 'text-green-600'} mt-1">${message}</div>
                </div>
                <button onclick="removeNotification(this.parentElement)" class="ml-4 ${isError ? 'text-red-400 hover:text-red-600' : 'text-green-400 hover:text-green-600'} transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            
            return notification;
        }

        function removeNotification(notification) {
            notification.classList.add('notification-exit');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }

        // Enhanced modal animations
        function showModalWithAnimation(modalId) {
            const modal = document.getElementById(modalId);
            const container = modal.querySelector('.bg-white, .bg-gray-800');
            
            modal.classList.remove('hidden');
            modal.style.opacity = '0';
            container.style.transform = 'scale(0.9) translateY(-20px)';
            
            // Trigger animation
            requestAnimationFrame(() => {
                modal.style.transition = 'opacity 0.3s ease-out';
                container.style.transition = 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
                
                modal.style.opacity = '1';
                container.style.transform = 'scale(1) translateY(0)';
            });
        }

        function hideModalWithAnimation(modalId, callback) {
            const modal = document.getElementById(modalId);
            const container = modal.querySelector('.bg-white, .bg-gray-800');
            
            modal.style.transition = 'opacity 0.2s ease-in';
            container.style.transition = 'transform 0.2s ease-in';
            
            modal.style.opacity = '0';
            container.style.transform = 'scale(0.95) translateY(-10px)';
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.opacity = '';
                container.style.transform = '';
                if (callback) callback();
            }, 200);
        }

        // Enhanced form validation
        function validateForm(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                const value = input.value.trim();
                const errorElement = input.parentNode.querySelector('.error-message');
                
                // Remove existing error
                if (errorElement) {
                    errorElement.remove();
                }
                
                if (!value) {
                    isValid = false;
                    showFieldError(input, 'Trường này là bắt buộc');
                } else {
                    clearFieldError(input);
                }
            });
            
            return isValid;
        }

        function showFieldError(input, message) {
            input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            
            const errorElement = document.createElement('div');
            errorElement.className = 'error-message text-red-500 text-xs mt-1';
            errorElement.textContent = message;
            
            input.parentNode.appendChild(errorElement);
        }

        function clearFieldError(input) {
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            
            const errorElement = input.parentNode.querySelector('.error-message');
            if (errorElement) {
                errorElement.remove();
            }
        }

        // Enhanced loading states
        function showButtonLoading(buttonId, loadingText = 'Đang xử lý...') {
            const button = document.getElementById(buttonId);
            const originalText = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                ${loadingText}
            `;
            
            // Store original text for restoration
            button.dataset.originalText = originalText;
        }

        function hideButtonLoading(buttonId) {
            const button = document.getElementById(buttonId);
            const originalText = button.dataset.originalText;
            
            button.disabled = false;
            button.innerHTML = originalText;
            delete button.dataset.originalText;
        }

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Close modals with Escape
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal-overlay:not(.hidden)');
                openModals.forEach(modal => {
                    const modalId = modal.id;
                    if (modalId === 'request-detail-modal') {
                        closeModal();
                    } else if (modalId === 'approval-modal') {
                        closeApprovalModal();
                    } else if (modalId === 'rejection-modal') {
                        closeRejectionModal();
                    }
                });
            }
            
            // Submit forms with Ctrl+Enter
            if (e.ctrlKey && e.key === 'Enter') {
                const activeModal = document.querySelector('.modal-overlay:not(.hidden)');
                if (activeModal) {
                    const submitButton = activeModal.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        submitButton.click();
                    }
                }
            }
        });

        // Enhanced accessibility
        function trapFocus(element) {
            const focusableElements = element.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            element.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstElement) {
                            lastElement.focus();
                            e.preventDefault();
                        }
                    } else {
                        if (document.activeElement === lastElement) {
                            firstElement.focus();
                            e.preventDefault();
                        }
                    }
                }
            });
            
            // Focus first element
            firstElement.focus();
        }

        // Update existing functions to use enhanced versions
        const originalShowSuccess = showSuccess;
        const originalShowError = showError;
        
        showSuccess = showEnhancedSuccess;
        showError = showEnhancedError;

        // Enhanced modal opening
        const originalViewRequestDetail = viewRequestDetail;
        viewRequestDetail = function(type, id) {
            showModalWithAnimation('request-detail-modal');
            return originalViewRequestDetail(type, id);
        };

        const originalCloseModal = closeModal;
        closeModal = function() {
            hideModalWithAnimation('request-detail-modal', originalCloseModal);
        };

        // Initialize enhanced features when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Add focus trap to modals when they open
            const modals = document.querySelectorAll('.modal-overlay');
            modals.forEach(modal => {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            if (!modal.classList.contains('hidden')) {
                                trapFocus(modal);
                            }
                        }
                    });
                });
                
                observer.observe(modal, { attributes: true });
            });
            
            // Add smooth scrolling to page
            document.documentElement.style.scrollBehavior = 'smooth';
            
            // Add loading states to all forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        showButtonLoading(submitButton.id || 'submit-btn');
                    }
                });
            });
        });
    </script>
</x-app-layout>