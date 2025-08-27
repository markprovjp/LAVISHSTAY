<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Lịch sử sử dụng Coupon</h1>
                <div class="flex items-center mt-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Mã:</span>
                    <span class="ml-2 px-3 py-1 bg-violet-100 dark:bg-violet-900/30 text-violet-800 dark:text-violet-200 font-mono font-bold text-sm rounded-full">
                        {{ $coupon->code }}
                    </span>
                    @if($coupon->description)
                        <span class="ml-3 text-sm text-gray-600 dark:text-gray-400">{{ $coupon->description }}</span>
                    @endif
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                    class="btn bg-violet-600 text-white hover:bg-violet-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="max-xs:sr-only">Chỉnh sửa</span>
                </a>
                <a href="{{ route('admin.coupons.index') }}"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z"/>
                    </svg>
                    <span class="max-xs:sr-only">Quay lại danh sách</span>
                </a>
            </div>
        </div>

        <!-- Coupon Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Total Usage -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $redemptions->total() }}</div>
                        <div class="text-sm text-blue-700 dark:text-blue-300">Tổng lượt sử dụng</div>
                    </div>
                </div>
            </div>

            <!-- Total Savings -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                            {{ number_format($redemptions->sum('amount_saved_vnd'), 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-green-700 dark:text-green-300">VND đã tiết kiệm</div>
                    </div>
                </div>
            </div>

            <!-- Discount Value -->
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                            @if($coupon->type === 'percent')
                                {{ $coupon->value }}%
                            @else
                                {{ number_format($coupon->value, 0, ',', '.') }}
                            @endif
                        </div>
                        <div class="text-sm text-purple-700 dark:text-purple-300">
                            Giá trị giảm {{ $coupon->type === 'percent' ? '' : '(VND)' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-gradient-to-r from-{{ $coupon->active ? 'green' : 'red' }}-50 to-{{ $coupon->active ? 'green' : 'red' }}-100 dark:from-{{ $coupon->active ? 'green' : 'red' }}-900/20 dark:to-{{ $coupon->active ? 'green' : 'red' }}-800/20 rounded-xl p-6 border border-{{ $coupon->active ? 'green' : 'red' }}-200 dark:border-{{ $coupon->active ? 'green' : 'red' }}-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-{{ $coupon->active ? 'green' : 'red' }}-600 rounded-lg flex items-center justify-center">
                            @if($coupon->active)
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-{{ $coupon->active ? 'green' : 'red' }}-900 dark:text-{{ $coupon->active ? 'green' : 'red' }}-100">
                            {{ $coupon->active ? 'Hoạt động' : 'Tạm dừng' }}
                        </div>
                        <div class="text-sm text-{{ $coupon->active ? 'green' : 'red' }}-700 dark:text-{{ $coupon->active ? 'green' : 'red' }}-300">Trạng thái hiện tại</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
            <div class="px-6 py-4">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-64">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tìm theo tên khách hàng, email, booking code..."
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Từ ngày</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Đến ngày</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    <button type="submit" class="btn bg-violet-600 text-white hover:bg-violet-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lọc
                    </button>

                    @if(request()->hasAny(['search', 'date_from', 'date_to']))
                        <a href="{{ route('admin.coupons.redemptions', $coupon->id) }}" class="btn bg-gray-500 text-white hover:bg-gray-600">
                            Xóa bộ lọc
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Redemptions Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Chi tiết sử dụng
                    </h2>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Hiển thị {{ $redemptions->count() }} / {{ $redemptions->total() }} kết quả
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table-auto w-full dark:text-gray-300">
                    <!-- Table header -->
                    <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Khách hàng</th>
                            <th class="px-6 py-4 text-left">Booking</th>
                            <th class="px-6 py-4 text-left">Số tiền áp dụng</th>
                            <th class="px-6 py-4 text-left">Số tiền tiết kiệm</th>
                            <th class="px-6 py-4 text-left">Ngày sử dụng</th>
                            <th class="px-6 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($redemptions as $redemption)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="font-mono text-violet-600 dark:text-violet-400">#{{ $redemption->id }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    @if($redemption->user)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-violet-400 to-purple-500 flex items-center justify-center shadow-lg">
                                                    <span class="text-sm font-bold text-white">
                                                        {{ strtoupper(substr($redemption->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $redemption->user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $redemption->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <span class="text-gray-500 dark:text-gray-400">Không xác định</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    @if($redemption->booking)
                                        <div>
                                            <div class="font-medium">{{ $redemption->booking->booking_code ?? '#'.$redemption->booking_id }}</div>
                                            <div class="text-xs">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    {{ $redemption->booking->status === 'Confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                                       ($redemption->booking->status === 'Pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                                        'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400') }}">
                                                    {{ $redemption->booking->status ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Booking không tồn tại</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="font-semibold">
                                        {{ number_format($redemption->applied_amount_vnd, 0, ',', '.') }} VND
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="font-semibold text-green-600 dark:text-green-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        -{{ number_format($redemption->amount_saved_vnd, 0, ',', '.') }} VND
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div class="font-medium">{{ $redemption->created_at }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $redemption->created_at }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($redemption->booking)
                                        <a href="#" onclick="viewBookingDetails({{ $redemption->booking_id }})"
                                            class="inline-flex items-center px-3 py-1 bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 text-sm font-medium rounded-md hover:bg-violet-200 dark:hover:bg-violet-900/50 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Xem
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-sm">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chưa có lịch sử sử dụng</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Coupon này chưa được sử dụng bởi khách hàng nào.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($redemptions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $redemptions->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function viewBookingDetails(bookingId) {
            // Implement booking details modal or redirect
            // For now, just show an alert
            alert('Xem chi tiết booking ID: ' + bookingId);
            // You can implement this to open a modal or redirect to booking details page
            // window.location.href = '/admin/bookings/' + bookingId;
        }
    </script>
</x-app-layout>