<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Giao dịch hàng ngày</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý và theo dõi dòng tiền vào/ra theo ngày</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Analytics button -->
                <button onclick="showAnalytics()"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 24 24">
                        <path d="M3 3v18h18v-2H5V3H3zm4 14h2v-6H7v6zm4 0h2V9h-2v8zm4 0h2V7h-2v10z"/>
                    </svg>
                    <span class="max-xs:sr-only">Thống kê</span>
                </button>

                <!-- Export button -->
                <button onclick="exportTransactions()"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L4 4h2v8h4V4h2L8 0z" />
                    </svg>
                    <span class="max-xs:sr-only">Xuất báo cáo</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Today Income -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Thu nhập hôm nay</p>
                        <p class="text-2xl font-semibold text-green-600 dark:text-green-400" id="today-income">
                            {{ number_format($statistics['today_total_income'] ?? 0) }}₫
                        </p>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span class="growth-indicator {{ ($statistics['income_growth'] ?? 0) >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                <svg class="w-3 h-3 inline {{ ($statistics['income_growth'] ?? 0) >= 0 ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                                {{ number_format(abs($statistics['income_growth'] ?? 0), 1) }}%
                            </span>
                            so với hôm qua
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Revenue -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Thu từ đặt phòng</p>
                        <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400" id="booking-income">
                            {{ number_format($statistics['today_income'] ?? 0) }}₫
                        </p>
                    </div>
                </div>
            </div>

            <!-- Service Revenue -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Thu từ dịch vụ</p>
                        <p class="text-2xl font-semibold text-purple-600 dark:text-purple-400" id="service-income">
                            {{ number_format($statistics['today_service_income'] ?? 0) }}₫
                        </p>
                    </div>
                </div>
            </div>

            <!-- Transaction Count -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Số giao dịch</p>
                        <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400" id="transaction-count">
                            {{ number_format($statistics['today_transaction_count'] ?? 0) }}
                        </p>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            TB: {{ number_format($statistics['avg_transaction_value'] ?? 0) }}₫/GD
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="py-5">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap gap-4" id="filter-form">
                <!-- Date Selection -->
                <div class="flex-1 min-w-48">
                    <input type="date" name="date" value="{{ $selectedDate }}"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Transaction Type Filter -->
                <div>
                    <select name="transaction_type"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả loại GD</option>
                        <option value="income" {{ request('transaction_type') === 'income' ? 'selected' : '' }}>Thu nhập</option>
                    </select>
                </div>

                <!-- Payment Method Filter -->
                <div>
                    <select name="payment_type"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả PT thanh toán</option>
                        <option value="cash" {{ request('payment_type') === 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                        <option value="bank_transfer" {{ request('payment_type') === 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                        <option value="credit_card" {{ request('payment_type') === 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng</option>
                        <option value="vnpay" {{ request('payment_type') === 'vnpay' ? 'selected' : '' }}>VNPay</option>
                        <option value="momo" {{ request('payment_type') === 'momo' ? 'selected' : '' }}>MoMo</option>
                        <option value="service" {{ request('payment_type') === 'service' ? 'selected' : '' }}>Dịch vụ</option>
                    </select>
                </div>

                <!-- Guest Name Search -->
                <div class="flex-1 min-w-48">
                    <input type="text" name="guest_name" value="{{ request('guest_name') }}"
                        placeholder="Tìm theo tên khách..."
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Booking Code Search -->
                <div class="flex-1 min-w-48">
                    <input type="text" name="booking_code" value="{{ request('booking_code') }}"
                        placeholder="Tìm theo mã đặt phòng..."
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Filter Button -->
                <button type="submit"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M9 2a1 1 0 0 0 0-2H7a1 1 0 0 0 0 2v1.586L1.707 8.879A1 1 0 0 0 1 9.586V15a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-2.414L9 9.414V2ZM3 13v-2.414l3-3V2H4v4.586l-3 3V13H3Z" />
                    </svg>
                    <span class="ml-2">Lọc</span>
                </button>

                <!-- Clear Filters -->
                @if (request()->hasAny(['date', 'transaction_type', 'payment_type', 'guest_name', 'booking_code']))
                    <a href="{{ route('admin.transactions.index') }}"
                        class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                        Xóa bộ lọc
                    </a>
                @endif
            </form>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div id="notification"
                class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-green-700">Thành công!</h3>
                    <div class="text-sm text-green-600">{{ session('success') }}</div>
                </div>
                <button onclick="closeNotification()"
                    class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div id="error-notification"
                class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">{{ session('error') }}</div>
                </div>
                <button onclick="closeErrorNotification()"
                    class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="">
                <table class="table-auto w-full dark:text-gray-300">
                    <!-- Table header -->
                    <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                            </th>
                            <th class="px-6 py-4 text-left">Mã giao dịch</th>
                            <th class="px-6 py-4 text-left">Thông tin khách</th>
                            <th class="px-6 py-4 text-left">Loại giao dịch</th>
                            <th class="px-6 py-4 text-left">Mô tả</th>
                            <th class="px-6 py-4 text-left">Phương thức</th>
                            <th class="px-6 py-4 text-left">Số tiền</th>
                            <th class="px-6 py-4 text-left">Thời gian</th>
                            <th class="px-6 py-4 text-left">Trạng thái</th>
                            <th class="px-6 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60" id="transactions-tbody">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <!-- Checkbox -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox"
                                        class="transaction-checkbox rounded border-gray-300 text-violet-600 focus:ring-violet-500"
                                        value="{{ $transaction->transaction_id }}">
                                </td>

                                <!-- Transaction ID -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                {{ $transaction->transaction_id }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $transaction->booking_code }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Guest Information -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                {{ $transaction->guest_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $transaction->guest_email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Transaction Type -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400">
                                        {{ $transaction->transaction_type === 'income' ? 'Thu nhập' : 'Chi phí' }}
                                    </span>
                                </td>

                                <!-- Description -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 max-w-48 truncate">
                                        {{ $transaction->description }}
                                    </div>
                                    @if($transaction->room_names)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Phòng: {{ $transaction->room_names }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Payment Method -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $paymentTypeConfig = [
                                            'cash' => ['class' => 'bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400', 'text' => 'Tiền mặt'],
                                            'bank_transfer' => ['class' => 'bg-blue-100 dark:bg-blue-400/30 text-blue-800 dark:text-blue-400', 'text' => 'Chuyển khoản'],
                                            'credit_card' => ['class' => 'bg-purple-100 dark:bg-purple-400/30 text-purple-800 dark:text-purple-400', 'text' => 'Thẻ tín dụng'],
                                            'vnpay' => ['class' => 'bg-red-100 dark:bg-red-400/30 text-red-800 dark:text-red-400', 'text' => 'VNPay'],
                                            'momo' => ['class' => 'bg-pink-100 dark:bg-pink-400/30 text-pink-800 dark:text-pink-400', 'text' => 'MoMo'],
                                            'service' => ['class' => 'bg-yellow-100 dark:bg-yellow-400/30 text-yellow-800 dark:text-yellow-400', 'text' => 'Dịch vụ'],
                                        ];
                                        $paymentType = $paymentTypeConfig[$transaction->payment_type] ?? [
                                            'class' => 'bg-gray-100 dark:bg-gray-400/30 text-gray-800 dark:text-gray-400',
                                            'text' => ucfirst($transaction->payment_type)
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentType['class'] }}">
                                        {{ $paymentType['text'] }}
                                    </span>
                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-green-600 dark:text-green-400 mb-1">
                                        +{{ number_format($transaction->amount) }}₫
                                    </div>
                                </td>

                                <!-- Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400">
                                        Hoàn thành
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                            class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                            onclick="window.toggleDropdown('{{ $transaction->transaction_id }}')"
                                            id="dropdown-button-{{ $transaction->transaction_id }}">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-menu-{{ $transaction->transaction_id }}"
                                            class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1" role="menu">
                                                <!-- View Details -->
                                                <button onclick="window.viewTransactionDetail('{{ $transaction->transaction_id }}')"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Xem chi tiết
                                                </button>

                                                <!-- View Booking -->
                                                <button onclick="window.viewBookingDetail('{{ $transaction->booking_id }}')"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Xem đặt phòng
                                                </button>

                                                <!-- Print Receipt -->
                                                <button onclick="window.printTransaction('{{ $transaction->transaction_id }}')"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                    In biên lai
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <div class="text-lg text-gray-500 dark:text-gray-400 mb-2">Không có giao dịch nào</div>
                                        <div class="text-sm text-gray-400 dark:text-gray-500">Chưa có dữ liệu giao dịch nào trong ngày {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Hiển thị {{ $transactions->firstItem() }}-{{ $transactions->lastItem() }} của {{ $transactions->total() }} giao dịch
                        </div>
                        <div class="flex space-x-1">
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div id="transaction-detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Chi tiết giao dịch</h3>
                    <button onclick="closeTransactionDetail()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="transaction-detail-content" class="p-6">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Modal -->
    <div id="analytics-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-6xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thống kê giao dịch</h3>
                    <button onclick="closeAnalytics()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="analytics-content" class="p-6">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .button-action {
                position: relative;
            }

            .menu-button-action {
                position: absolute;
                top: 0%;
                right: 130%;
                z-index: 50;
                width: 200px;
            }

            .transaction-row {
                transition: all 0.2s ease;
            }

            .transaction-row:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .growth-indicator {
                display: inline-flex;
                align-items: center;
            }

            /* Custom scrollbar for modals */
            .modal-content::-webkit-scrollbar {
                width: 6px;
            }

            .modal-content::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .modal-content::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 3px;
            }

            .modal-content::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>
    @endpush

    <script>
        // Global functions for dropdown and modal management
        window.toggleDropdown = function(transactionId) {
            const dropdown = document.getElementById(`dropdown-menu-${transactionId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${transactionId}`) {
                    menu.classList.add('hidden');
                }
            });
            dropdown.classList.toggle('hidden');
        };

        window.closeDropdown = function(transactionId) {
            const dropdown = document.getElementById(`dropdown-menu-${transactionId}`);
            dropdown.classList.add('hidden');
        };

        window.viewTransactionDetail = function(transactionId) {
            showLoading('Đang tải thông tin...');

            fetch(`/admin/transactions/${transactionId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    document.getElementById('transaction-detail-content').innerHTML = generateTransactionDetailHTML(data.transaction);
                    document.getElementById('transaction-detail-modal').classList.remove('hidden');
                } else {
                    showNotification('Không thể tải thông tin giao dịch', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading transaction detail:', error);
                showNotification('Có lỗi xảy ra khi tải thông tin', 'error');
            });
        };

        window.viewBookingDetail = function(bookingId) {
            showLoading('Đang tải thông tin đặt phòng...');

            fetch(`/admin/bookings/${bookingId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    // Reuse booking detail modal from booking page
                    showNotification('Chức năng xem chi tiết đặt phòng đang được phát triển', 'info');
                } else {
                    showNotification('Không thể tải thông tin đặt phòng', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading booking detail:', error);
                showNotification('Có lỗi xảy ra khi tải thông tin đặt phòng', 'error');
            });
        };

        window.printTransaction = function(transactionId) {
            window.open(`/admin/transactions/${transactionId}/print`, '_blank');
        };

        function showAnalytics() {
            showLoading('Đang tải thống kê...');

            fetch('/admin/transactions/analytics', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    document.getElementById('analytics-content').innerHTML = generateAnalyticsHTML(data.data);
                    document.getElementById('analytics-modal').classList.remove('hidden');
                } else {
                    showNotification('Không thể tải thống kê', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading analytics:', error);
                showNotification('Có lỗi xảy ra khi tải thống kê', 'error');
            });
        }

        function exportTransactions() {
            const form = document.getElementById('filter-form');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value.trim()) {
                    params.append(key, value);
                }
            }

            window.open(`/admin/transactions/export?${params.toString()}`, '_blank');
        }

        function closeTransactionDetail() {
            document.getElementById('transaction-detail-modal').classList.add('hidden');
        }

        function closeAnalytics() {
            document.getElementById('analytics-modal').classList.add('hidden');
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            initializeFilters();
            initializeCheckboxes();

            // Auto-refresh every 30 seconds
            setInterval(function() {
                refreshTransactions();
            }, 30000);
        });

        // Filter functionality
        function initializeFilters() {
            const filterForm = document.getElementById('filter-form');
            const dateInput = filterForm.querySelector('input[name="date"]');

            // Auto-submit on date change
            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    filterForm.submit();
                });
            }

            // Real-time search
            const guestNameInput = filterForm.querySelector('input[name="guest_name"]');
            const bookingCodeInput = filterForm.querySelector('input[name="booking_code"]');

            let searchTimeout;
            [guestNameInput, bookingCodeInput].forEach(input => {
                if (input) {
                    input.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            filterForm.submit();
                        }, 500);
                    });
                }
            });
        }

        // Checkbox functionality
        function initializeCheckboxes() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const transactionCheckboxes = document.querySelectorAll('.transaction-checkbox');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    transactionCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            transactionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedCount();

                    const checkedCount = document.querySelectorAll('.transaction-checkbox:checked').length;
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = checkedCount === transactionCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < transactionCheckboxes.length;
                    }
                });
            });
        }

        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.transaction-checkbox:checked').length;
            // Update UI based on selection if needed
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');

            let clickedInsideDropdown = false;

            dropdowns.forEach(dropdown => {
                if (dropdown.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            buttons.forEach(button => {
                if (button.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        function generateTransactionDetailHTML(transaction) {
            return `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Transaction Information -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin giao dịch</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Mã giao dịch:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${transaction.transaction_id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Mã đặt phòng:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${transaction.booking_code}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Loại giao dịch:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400">
                                    ${transaction.transaction_type === 'income' ? 'Thu nhập' : 'Chi phí'}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Mô tả:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${transaction.description}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Số tiền:</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">+${formatCurrency(transaction.amount)}₫</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Phương thức:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${getPaymentMethodText(transaction.payment_type)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Thời gian:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${formatDateTime(transaction.created_at)}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin khách hàng</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tên khách:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${transaction.guest_name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Email:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${transaction.guest_email}</span>
                            </div>
                            ${transaction.guest_phone ? `
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Điện thoại:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${transaction.guest_phone}</span>
                                </div>
                            ` : ''}
                            ${transaction.room_names ? `
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Phòng:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${transaction.room_names}</span>
                                </div>
                            ` : ''}
                            ${transaction.check_in_date ? `
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Check-in:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${formatDate(transaction.check_in_date)}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Check-out:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${formatDate(transaction.check_out_date)}</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button onclick="closeTransactionDetail()" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Đóng
                    </button>
                    <button onclick="window.printTransaction('${transaction.transaction_id}')" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        In biên lai
                    </button>
                </div>
            `;
        }

        function generateAnalyticsHTML(data) {
            return `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                        <h3 class="text-lg font-semibold">Tổng thu nhập</h3>
                        <p class="text-2xl font-bold">${formatCurrency(data.total_stats.total_income)}₫</p>
                        <p class="text-sm opacity-80">${data.period} ngày qua</p>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                        <h3 class="text-lg font-semibold">Thu từ dịch vụ</h3>
                        <p class="text-2xl font-bold">${formatCurrency(data.total_stats.total_service_income)}₫</p>
                        <p class="text-sm opacity-80">Dịch vụ bổ sung</p>
                    </div>
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                        <h3 class="text-lg font-semibold">Tổng giao dịch</h3>
                        <p class="text-2xl font-bold">${data.total_stats.total_transactions}</p>
                        <p class="text-sm opacity-80">Giao dịch hoàn thành</p>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                        <h3 class="text-lg font-semibold">Giá trị TB</h3>
                        <p class="text-2xl font-bold">${formatCurrency(data.total_stats.avg_transaction_value)}₫</p>
                        <p class="text-sm opacity-80">Mỗi giao dịch</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Payment Methods -->
                    <div class="bg-white dark:bg-gray-900/50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Phương thức thanh toán</h4>
                        <div class="space-y-3">
                            ${data.payment_method_distribution.map(method => `
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">${getPaymentMethodText(method.payment_type)}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">${method.transaction_count} giao dịch</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">${formatCurrency(method.total_amount)}₫</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <!-- Top Services -->
                    <div class="bg-white dark:bg-gray-900/50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Top dịch vụ</h4>
                        <div class="space-y-3">
                            ${data.top_services.map((service, index) => `
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-400/30 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">${index + 1}</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">${service.service_name}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">${service.total_quantity} lần sử dụng</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">${formatCurrency(service.total_revenue)}₫</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        }

        // Utility functions
        function refreshTransactions() {
            location.reload();
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount || 0);
        }

        function getPaymentMethodText(paymentType) {
            const methods = {
                'cash': 'Tiền mặt',
                'bank_transfer': 'Chuyển khoản',
                'credit_card': 'Thẻ tín dụng',
                'vnpay': 'VNPay',
                'momo': 'MoMo',
                'service': 'Dịch vụ'
            };
            return methods[paymentType] || paymentType;
        }

        // Notification functions
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        function closeErrorNotification() {
            const notification = document.getElementById('error-notification');
            if (notification) {
                notification.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Auto-close notifications
        setTimeout(() => {
            closeNotification();
            closeErrorNotification();
        }, 5000);

        // Loading and notification functions
        function showLoading(message = 'Đang xử lý...') {
            let loadingOverlay = document.getElementById('loading-overlay');
            if (!loadingOverlay) {
                loadingOverlay = document.createElement('div');
                loadingOverlay.id = 'loading-overlay';
                loadingOverlay.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center';
                loadingOverlay.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-900 dark:text-gray-100" id="loading-message">${message}</span>
                    </div>
                `;
                document.body.appendChild(loadingOverlay);
            } else {
                document.getElementById('loading-message').textContent = message;
                loadingOverlay.classList.remove('hidden');
            }
        }

        function hideLoading() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
        }

        function showNotification(message, type = 'info') {
            const existingNotifications = document.querySelectorAll('.notification-toast');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

            const bgColor = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'warning': 'bg-yellow-500',
                'info': 'bg-blue-500'
            }[type] || 'bg-blue-500';

            const icon = {
                'success': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'error': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z',
                'warning': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z',
                'info': 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            }[type] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

            notification.className += ` ${bgColor} text-white`;

            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon}"></path>
                    </svg>
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTransactionDetail();
                closeAnalytics();
                hideLoading();
            }

            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="guest_name"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }

            if (e.key === 'F5') {
                e.preventDefault();
                refreshTransactions();
            }
        });
    </script>
</x-app-layout>