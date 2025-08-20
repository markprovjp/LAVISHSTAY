<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý đặt phòng</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý và theo dõi các đặt phòng của khách sạn</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Export button -->
                <button onclick="exportBookings()"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0L4 4h2v8h4V4h2L8 0z" />
                    </svg>
                    <span class="max-xs:sr-only">Xuất báo cáo</span>
                </button>

                <!-- Add booking button -->
                <button onclick="showNewBookingModal()"
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Đặt phòng mới</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Bookings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng đặt phòng</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="total-bookings">
                            {{ $statistics['total_bookings'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Bookings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-yellow-100 dark:bg-yellow-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Chờ xác nhận</p>
                        <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400" id="pending-bookings">
                            {{ $statistics['pending_bookings'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Confirmed Bookings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Đã xác nhận</p>
                        <p class="text-2xl font-semibold text-green-600 dark:text-green-400" id="confirmed-bookings">
                            {{ $statistics['confirmed_bookings'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-red-100 dark:bg-red-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-15 h-15 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" width="30" height="30">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Doanh thu</p>
                        <p class="text-xl font-semibold text-red-600 dark:text-red-400" id="total-revenue">
                            {{ number_format($statistics['total_revenue'] ?? 0) }}₫
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="py-5">
            <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-wrap gap-4" id="filter-form">
                <!-- Booking Code Search -->
                <div class="flex-1 min-w-64">
                    <input type="text" name="booking_code" value="{{ request('booking_code') }}"
                        placeholder="Tìm kiếm theo mã đặt phòng..."
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Guest Name Search -->
                <div class="flex-1 min-w-64">
                    <input type="text" name="guest_name" value="{{ request('guest_name') }}"
                        placeholder="Tìm kiếm theo tên khách..."
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận
                        </option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                        </option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy
                        </option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành
                        </option>
                    </select>
                </div>

                <!-- Check-in Date -->
                <div>
                    <input type="date" name="check_in_date" value="{{ request('check_in_date') }}"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Check-out Date -->
                <div>
                    <input type="date" name="check_out_date" value="{{ request('check_out_date') }}"
                        class="border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                </div>

                <!-- Filter Button -->
                <button type="submit"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M9 2a1 1 0 0 0 0-2H7a1 1 0 0 0 0 2v1.586L1.707 8.879A1 1 0 0 0 1 9.586V15a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-2.414L9 9.414V2ZM3 13v-2.414l3-3V2H4v4.586l-3 3V13H3Z" />
                    </svg>
                    <span class="ml-2">Lọc</span>
                </button>

                <!-- Clear Filters -->
                @if (request()->hasAny(['booking_code', 'guest_name', 'status', 'check_in_date', 'check_out_date']))
                    <a href="{{ route('admin.bookings') }}"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-green-700">Thành công!</h3>
                    <div class="text-sm text-green-600">{{ session('success') }}</div>
                </div>
                <button onclick="closeNotification()"
                    class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">{{ session('error') }}</div>
                </div>
                <button onclick="closeErrorNotification()"
                    class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <div class="">
                <table class="table-auto w-full dark:text-gray-300">
                    <!-- Table header -->
                    <thead
                        class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                            </th>
                            <th class="px-6 py-4 text-left">Mã đặt phòng</th>
                            <th class="px-6 py-4 text-left">Thông tin khách</th>
                            <th class="px-6 py-4 text-left">Thời gian lưu trú</th>
                            <th class="px-6 py-4 text-left">Tổng khách</th>
                            <th class="px-6 py-4 text-left">Gói phòng</th>
                            <th class="px-6 py-4 text-left">Phòng</th>
                            <th class="px-6 py-4 text-left">Tổng tiền</th>
                            <th class="px-6 py-4 text-left">Trạng thái</th>
                            <th class="px-6 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60" id="bookings-tbody">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <!-- Checkbox -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox"
                                        class="booking-checkbox rounded border-gray-300 text-violet-600 focus:ring-violet-500"
                                        value="{{ $booking->booking_id ?? $booking->id }}">
                                </td>

                                <!-- Booking Code -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                                {{ $booking->booking_code }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Guest Information -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div
                                                class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                {{ $booking->guest_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                    </path>
                                                </svg>
                                                {{ $booking->guest_phone }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                {{ $booking->guest_email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Stay Duration -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="bg-green-50 dark:bg-green-400/20 px-2 py-1 rounded text-xs font-medium text-green-700 dark:text-green-400 mb-1">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d/m') }} -
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d/m') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 text-center font-medium">
                                        {{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) }}
                                        đêm
                                    </div>
                                </td>

                                <!-- Guest Count -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center mb-1">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $booking->adults ?? $booking->guest_count }} NL
                                            @if ($booking->children > 0)
                                                + {{ $booking->children }} TE
                                            @endif
                                        </span>
                                    </div>
                                    @if ($booking->children_age)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Tuổi TE:
                                            @php
                                                try {
                                                    $ages = is_string($booking->children_age)
                                                        ? json_decode($booking->children_age, true)
                                                        : $booking->children_age;

                                                    if (is_array($ages) && !empty($ages)) {
                                                        echo implode(', ', array_filter($ages));
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                } catch (Exception $e) {
                                                    echo 'N/A';
                                                }
                                            @endphp
                                        </div>
                                    @endif



                                </td>

                                <!-- Package/Option -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-purple-100 dark:bg-purple-400/30 rounded-lg flex items-center justify-center mr-2">
                                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $booking->option_names ?: 'Chưa chọn gói' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Room Information -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center mr-2">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2                                                 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            @if ($booking->total_rooms > 1)
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $booking->total_rooms }} phòng
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 dark:text-gray-400 max-w-32 truncate">
                                                    {{ $booking->room_names ?: 'Chưa chọn phòng' }}
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 dark:text-gray-400 max-w-32 truncate">
                                                    {{ $booking->room_type_names }}
                                                </div>
                                            @elseif($booking->room_names || $booking->room_names)
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $booking->room_names ?: explode(',', $booking->room_names)[0] }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $booking->room_type_names ? explode(',', $booking->room_type_names)[0] : 'ID: ' . ($booking->room_id ?: 'N/A') }}
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    Chưa chọn phòng
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Total Price -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-red-600 dark:text-red-400 mb-1">
                                        {{ number_format($booking->total_price_vnd ?? 0) }}₫
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Tổng cộng</div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusConfig = [
                                            'pending' => [
                                                'class' =>
                                                    'bg-yellow-100 dark:bg-yellow-400/30 text-yellow-800 dark:text-yellow-400',
                                                'text' => 'Chờ xác nhận',
                                            ],
                                            'confirmed' => [
                                                'class' =>
                                                    'bg-blue-100 dark:bg-blue-400/30 text-blue-800 dark:text-blue-400',
                                                'text' => 'Đã xác nhận',
                                            ],
                                            'cancelled' => [
                                                'class' =>
                                                    'bg-red-100 dark:bg-red-400/30 text-red-800 dark:text-red-400',
                                                'text' => 'Đã hủy',
                                            ],
                                            'completed' => [
                                                'class' =>
                                                    'bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400',
                                                'text' => 'Hoàn thành',
                                            ],
                                        ];
                                        $status = $statusConfig[$booking->status] ?? [
                                            'class' =>
                                                'bg-gray-100 dark:bg-gray-400/30 text-gray-800 dark:text-gray-400',
                                            'text' => $booking->status,
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $status['class'] }}">
                                        {{ $status['text'] }}
                                    </span>
                                </td>

                                <!-- Actions - Thay thế đoạn code từ dòng ~580 -->
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                            class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                            onclick="window.toggleDropdown({{ $booking->booking_id ?? $booking->id }})"
                                            id="dropdown-button-{{ $booking->booking_id ?? $booking->id }}">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-menu-{{ $booking->booking_id ?? $booking->id }}"
                                            class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1 z-500" role="menu">

                                                <!-- View Details -->
                                                <button
                                                    onclick="window.viewBookingDetail({{ $booking->booking_id ?? $booking->id }})"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Xem chi tiết
                                                </button>

                                                <!-- Assign Room (if no room assigned) -->
                                                @if (empty($booking->room_names) || str_contains($booking->room_names, 'null'))
                                                    <button
                                                        onclick="window.selectRoom({{ $booking->booking_id ?? $booking->id }})"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-150"
                                                        role="menuitem">
                                                        <svg class="w-4 h-4 mr-3" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        Chọn phòng
                                                    </button>
                                                @endif

                                                <!-- Confirm Booking (if pending) -->
                                                @if ($booking->status === 'pending')
                                                    <button
                                                        onclick="window.confirmBooking({{ $booking->booking_id ?? $booking->id }})"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-150"
                                                        role="menuitem">
                                                        <svg class="w-4 h-4 mr-3" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                        Xác nhận
                                                    </button>
                                                @endif

                                                <!-- Print -->
                                                <button
                                                    onclick="window.printBooking({{ $booking->booking_id ?? $booking->id }})"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                    role="menuitem">
                                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                        </path>
                                                    </svg>
                                                    In hóa đơn
                                                </button>

                                                <!-- Divider -->
                                                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                                <!-- Cancel Booking (if pending) -->
                                                @if ($booking->status === 'pending')
                                                    <button
                                                        onclick="window.cancelBooking({{ $booking->booking_id ?? $booking->id }}); window.closeDropdown({{ $booking->booking_id ?? $booking->id }})"
                                                        class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                        role="menuitem">
                                                        <svg class="w-4 h-4 mr-3" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                        Hủy đặt phòng
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>

                            <!-- Hidden row for booking details -->
                            <tr id="details-{{ $booking->booking_id ?? $booking->id }}"
                                class="hidden bg-gray-50 dark:bg-gray-900/50">
                                <td colspan="10" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <!-- Booking Information -->
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Thông tin đặt
                                                phòng</h4>
                                            <div class="space-y-2">
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Mã
                                                        đặt phòng:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $booking->booking_code }}</div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Ngày
                                                        đặt:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Check-in:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Check-out:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Guest Information -->
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Thông tin
                                                khách hàng</h4>
                                            <div class="space-y-2">
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Tên
                                                        khách:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $booking->guest_name }}</div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $booking->guest_email }}</div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Điện
                                                        thoại:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $booking->guest_phone }}</div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Tổng
                                                        khách:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $booking->adults ?? $booking->guest_count }} người lớn
                                                        @if ($booking->children > 0)
                                                            + {{ $booking->children }} trẻ em
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment Information -->
                                        <div>
                                            <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Thông tin
                                                thanh toán</h4>
                                            <div class="space-y-2">
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Tổng
                                                        tiền:</span>
                                                    <div class="text-sm font-semibold text-red-600 dark:text-red-400">
                                                        {{ number_format($booking->total_price_vnd ?? 0) }}₫</div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">Trạng
                                                        thái thanh toán:</span>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $booking->payment_status ?? 'Chưa thanh toán' }}</div>
                                                </div>
                                                @if ($booking->payment_type ?? '')
                                                    <div>
                                                        <span
                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">Phương
                                                            thức:</span>
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            {{ $booking->payment_type ?? '' }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <div class="text-lg text-gray-500 dark:text-gray-400 mb-2">Không có đặt phòng
                                            nào</div>
                                        <div class="text-sm text-gray-400 dark:text-gray-500">Chưa có dữ liệu đặt phòng
                                            nào được tìm thấy</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($bookings->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Hiển thị {{ $bookings->firstItem() }}-{{ $bookings->lastItem() }} của
                            {{ $bookings->total() }} đặt phòng
                        </div>
                        <div class="flex space-x-1">
                            {{ $bookings->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Booking Detail Modal -->
    <div id="booking-detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Chi tiết đặt phòng</h3>
                    <button onclick="closeBookingDetail()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="booking-detail-content" class="p-6">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Room Selection Modal -->
    <div id="room-selection-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Chọn phòng cho khách</h3>
                    <button onclick="closeRoomSelection()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="room-selection-content" class="p-6">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulk-actions-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thao tác hàng loạt</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Đã chọn <span id="selected-count">0</span> đặt phòng
                    </p>
                    <div class="space-y-3">
                        <button onclick="bulkConfirm()" class="w-full btn bg-green-600 text-white hover:bg-green-700">
                            Xác nhận tất cả
                        </button>
                        <button onclick="bulkCancel()" class="w-full btn bg-red-600 text-white hover:bg-red-700">
                            Hủy tất cả
                        </button>
                        <button onclick="bulkExport()" class="w-full btn bg-blue-600 text-white hover:bg-blue-700">
                            Xuất danh sách
                        </button>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button onclick="closeBulkActions()" class="btn bg-gray-300 text-gray-700 hover:bg-gray-400">
                        Đóng
                    </button>
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

            .booking-row {
                transition: all 0.2s ease;
            }

            .booking-row:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .status-badge {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.8;
                }
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
        window.toggleDropdown = function(bookingId) {
            const dropdown = document.getElementById(`dropdown-menu-${bookingId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${bookingId}`) {
                    menu.classList.add('hidden');
                }
            });
            dropdown.classList.toggle('hidden');
        };
        window.closeDropdown = function(bookingId) {
            const dropdown = document.getElementById(`dropdown-menu-${bookingId}`);
            dropdown.classList.add('hidden');
        };

        window.viewBookingDetail = function(bookingId) {
            showLoading('Đang tải thông tin...');

            fetch(`{{ url('admin/bookings') }}/${bookingId}`, {
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
                        document.getElementById('booking-detail-content').innerHTML = generateBookingDetailHTML(data
                            .booking);
                        document.getElementById('booking-detail-modal').classList.remove('hidden');
                    } else {
                        showNotification('Không thể tải thông tin đặt phòng', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error loading booking detail:', error);
                    showNotification('Có lỗi xảy ra khi tải thông tin', 'error');
                });
        };

        window.selectRoom = function(bookingId) {
            showLoading('Đang tải danh sách phòng...');

            fetch(`{{ url('admin/bookings') }}/${bookingId}/available-rooms`, {
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
                        document.getElementById('room-selection-content').innerHTML = generateRoomSelectionHTML(data
                            .rooms, bookingId);
                        document.getElementById('room-selection-modal').classList.remove('hidden');
                    } else {
                        showNotification('Không thể tải danh sách phòng', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error loading available rooms:', error);
                    showNotification('Có lỗi xảy ra khi tải danh sách phòng', 'error');
                });
        };

        window.confirmBooking = function(bookingId) {
            if (!confirm('Bạn có chắc chắn muốn xác nhận đặt phòng này?')) {
                return;
            }

            showLoading('Đang xác nhận...');

            fetch(`{{ url('admin/bookings') }}/${bookingId}/confirm`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showNotification('Đã xác nhận đặt phòng thành công', 'success');
                        closeBookingDetail();
                        location.reload();
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra khi xác nhận đặt phòng', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error confirming booking:', error);
                    showNotification('Có lỗi xảy ra khi xác nhận đặt phòng', 'error');
                });
        };

        window.cancelBooking = function(bookingId) {
            if (!confirm('Bạn có chắc chắn muốn hủy đặt phòng này? Hành động này không thể hoàn tác.')) {
                return;
            }

            showLoading('Đang hủy đặt phòng...');

            fetch(`{{ url('admin/bookings') }}/${bookingId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showNotification('Đã hủy đặt phòng thành công', 'success');
                        location.reload();
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra khi hủy đặt phòng', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error canceling booking:', error);
                    showNotification('Có lỗi xảy ra khi hủy đặt phòng', 'error');
                });
        };

        window.printBooking = function(bookingId) {
            window.open(`{{ url('admin/bookings') }}/${bookingId}/print`, '_blank');
        };

        window.showNewBookingModal = function() {
            // Implement new booking modal functionality
            showNotification('Chức năng đặt phòng mới đang được phát triển', 'info');
        };

        window.exportBookings = function() {
            const form = document.getElementById('filter-form');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value.trim()) {
                    params.append(key, value);
                }
            }

            params.append('export', 'excel');
            window.open(`{{ route('admin.bookings.export') }}?${params.toString()}`, '_blank');
        };

        // Rest of the functions remain the same but not exposed to window
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize components
            initializeFilters();
            initializeCheckboxes();

            // Auto-refresh every 30 seconds
            setInterval(function() {
                refreshBookings();
            }, 30000);
        });

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

        // Filter functionality
        function initializeFilters() {
            const filterForm = document.getElementById('filter-form');

            // Real-time search for booking code and guest name
            const bookingCodeInput = filterForm.querySelector('input[name="booking_code"]');
            const guestNameInput = filterForm.querySelector('input[name="guest_name"]');

            let searchTimeout;
            [bookingCodeInput, guestNameInput].forEach(input => {
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
            const bookingCheckboxes = document.querySelectorAll('.booking-checkbox');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    bookingCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            bookingCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedCount();

                    // Update select all checkbox
                    const checkedCount = document.querySelectorAll('.booking-checkbox:checked').length;
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = checkedCount === bookingCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount <
                            bookingCheckboxes.length;
                    }
                });
            });
        }

        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.booking-checkbox:checked').length;
            const selectedCountElement = document.getElementById('selected-count');
            if (selectedCountElement) {
                selectedCountElement.textContent = checkedCount;
            }

            // Show/hide bulk actions button
            const bulkActionsBtn = document.getElementById('bulk-actions-btn');
            if (bulkActionsBtn) {
                bulkActionsBtn.style.display = checkedCount > 0 ? 'block' : 'none';
            }
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

        function generateBookingDetailHTML(booking) {
            return `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Booking Information -->
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin đặt phòng</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Mã đặt phòng:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.booking_code}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Ngày đặt:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${formatDateTime(booking.created_at)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Check-in:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${formatDate(booking.check_in_date)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Check-out:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${formatDate(booking.check_out_date)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Số đêm:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${calculateNights(booking.check_in_date, booking.check_out_date)} đêm</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Trạng thái:</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(booking.status)}">
                            ${getStatusText(booking.status)}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Guest Information -->
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin khách hàng</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tên khách:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.guest_name}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Email:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.guest_email}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Điện thoại:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.guest_phone}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Người lớn:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.adults || booking.guest_count}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Trẻ em:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.children || 0}</span>
                    </div>
                        ${booking.children_age ? `
                            <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tuổi trẻ em:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">${(() => {
                                    try {
                                            const ages = JSON.parse(booking.children_age);
                                            return Array.isArray(ages) && ages.length > 0 ? ages.join(', ') : 'N/A';
                                    } catch (e) {
                                            return 'N/A';
                                    }
                                    })()}</span>
                            </div>
                            ` : ''}
                </div>
            </div>

            <!-- Room Information -->
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin phòng</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Số phòng:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.total_rooms || 1}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tên phòng:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.room_names || booking.room_name || 'Chưa chọn phòng'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Loại phòng:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.room_type_names || 'N/A'}</span>
                    </div>
                    ${booking.option_names ? `
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Gói phòng:</span>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">${booking.option_names}</span>
                                        </div>
                                    ` : ''}
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin thanh toán</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tổng tiền:</span>
                        <span class="font-semibold text-red-600 dark:text-red-400">${formatCurrency(booking.total_price_vnd)}₫</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Trạng thái TT:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.payment_status || 'Chưa thanh toán'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Phương thức:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">${booking.payment_type || 'N/A'}</span>
                    </div>
                    ${booking.transaction_id ? `
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Mã GD:</span>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">${booking.transaction_id}</span>
                                        </div>
                                    ` : ''}
                </div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="mt-6 flex justify-end space-x-3">
                        <button onclick="closeBookingDetail()" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                Đóng
            </button>
            ${booking.status === 'pending' ? `
                                <button onclick="window.confirmBooking(${booking.booking_id || booking.id})" 
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    Xác nhận đặt phòng
                                </button>
                            ` : ''}
            <button onclick="window.printBooking(${booking.booking_id || booking.id})" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                In hóa đơn
            </button>
        </div>
    `;
        }

        function closeBookingDetail() {
            document.getElementById('booking-detail-modal').classList.add('hidden');
        }

        function generateRoomSelectionHTML(rooms, bookingId) {
            return `
        <div class="mb-4">
            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Chọn phòng cho đặt phòng #${bookingId}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">Danh sách phòng có sẵn trong thời gian lưu trú</p>
        </div>
        
        <div class="max-h-96 overflow-y-auto modal-content">
            ${rooms.length === 0 ? `
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="text-gray-500 dark:text-gray-400">Không có phòng trống</div>
                                </div>
                            ` : `
                                <div class="grid gap-3">
                                    ${rooms.map(room => `
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer room-option transition-colors duration-200" 
                             onclick="assignRoom(${bookingId}, ${room.id})">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">${room.name}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">${room.room_type_name}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">Tối đa ${room.max_guests} khách</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold ${room.status === 'available' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400'}">
                                        ${room.status === 'available' ? 'Có sẵn' : 'Đang bảo trì'}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">Phòng ${room.room_number}</div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                                </div>
                            `}
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="closeRoomSelection()" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                Hủy
            </button>
        </div>
    `;
        }

        function assignRoom(bookingId, roomId) {
            if (!confirm('Bạn có chắc chắn muốn gán phòng này cho đặt phòng?')) {
                return;
            }

            showLoading('Đang gán phòng...');

            fetch(`{{ url('admin/bookings') }}/${bookingId}/assign-room`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        room_id: roomId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showNotification('Đã gán phòng thành công', 'success');
                        closeRoomSelection();
                        location.reload(); // Refresh page to show updated data
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra khi gán phòng', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error assigning room:', error);
                    showNotification('Có lỗi xảy ra khi gán phòng', 'error');
                });
        }

        function closeRoomSelection() {
            document.getElementById('room-selection-modal').classList.add('hidden');
        }

        // Bulk actions
        function showBulkActions() {
            const checkedCount = document.querySelectorAll('.booking-checkbox:checked').length;
            if (checkedCount === 0) {
                showNotification('Vui lòng chọn ít nhất một đặt phòng', 'warning');
                return;
            }
            document.getElementById('bulk-actions-modal').classList.remove('hidden');
        }

        function closeBulkActions() {
            document.getElementById('bulk-actions-modal').classList.add('hidden');
        }

        function bulkConfirm() {
            const selectedIds = Array.from(document.querySelectorAll('.booking-checkbox:checked')).map(cb => cb.value);

            if (selectedIds.length === 0) {
                showNotification('Vui lòng chọn ít nhất một đặt phòng', 'warning');
                return;
            }

            if (!confirm(`Bạn có chắc chắn muốn xác nhận ${selectedIds.length} đặt phòng đã chọn?`)) {
                return;
            }

            showLoading('Đang xác nhận hàng loạt...');

            fetch(`{{ url('admin/bookings/bulk-confirm') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showNotification(`Đã xác nhận thành công ${data.confirmed_count} đặt phòng`, 'success');
                        closeBulkActions();
                        location.reload();
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra khi xác nhận hàng loạt', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error bulk confirming:', error);
                    showNotification('Có lỗi xảy ra khi xác nhận hàng loạt', 'error');
                });
        }

        function bulkCancel() {
            const selectedIds = Array.from(document.querySelectorAll('.booking-checkbox:checked')).map(cb => cb.value);

            if (selectedIds.length === 0) {
                showNotification('Vui lòng chọn ít nhất một đặt phòng', 'warning');
                return;
            }

            if (!confirm(
                    `Bạn có chắc chắn muốn hủy ${selectedIds.length} đặt phòng đã chọn? Hành động này không thể hoàn tác.`
                )) {
                return;
            }

            showLoading('Đang hủy hàng loạt...');

            fetch(`{{ url('admin/bookings/bulk-cancel') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showNotification(`Đã hủy thành công ${data.cancelled_count} đặt phòng`, 'success');
                        closeBulkActions();
                        location.reload();
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra khi hủy hàng loạt', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error bulk canceling:', error);
                    showNotification('Có lỗi xảy ra khi hủy hàng loạt', 'error');
                });
        }

        function bulkExport() {
            const selectedIds = Array.from(document.querySelectorAll('.booking-checkbox:checked')).map(cb => cb.value);

            if (selectedIds.length === 0) {
                showNotification('Vui lòng chọn ít nhất một đặt phòng', 'warning');
                return;
            }

            const params = new URLSearchParams();
            selectedIds.forEach(id => params.append('booking_ids[]', id));
            params.append('export', 'excel');

            window.open(`{{ route('admin.bookings.export') }}?${params.toString()}`, '_blank');
            closeBulkActions();
        }

        // Utility functions
        function refreshBookings() {
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

        function calculateNights(checkIn, checkOut) {
            const start = new Date(checkIn);
            const end = new Date(checkOut);
            const diffTime = Math.abs(end - start);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount || 0);
        }

        function getStatusClass(status) {
            const statusConfig = {
                'pending': 'bg-yellow-100 dark:bg-yellow-400/30 text-yellow-800 dark:text-yellow-400',
                'confirmed': 'bg-blue-100 dark:bg-blue-400/30 text-blue-800 dark:text-blue-400',
                'cancelled': 'bg-red-100 dark:bg-red-400/30 text-red-800 dark:text-red-400',
                'completed': 'bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400'
            };
            return statusConfig[status] || 'bg-gray-100 dark:bg-gray-400/30 text-gray-800 dark:text-gray-400';
        }

        function getStatusText(status) {
            const statusConfig = {
                'pending': 'Chờ xác nhận',
                'confirmed': 'Đã xác nhận',
                'cancelled': 'Đã hủy',
                'completed': 'Hoàn thành'
            };
            return statusConfig[status] || status;
        }

        // Loading and notification functions
        function showLoading(message = 'Đang xử lý...') {
            // Create loading overlay if it doesn't exist
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
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification-toast');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className =
                `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

            const bgColor = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'warning': 'bg-yellow-500',
                'info': 'bg-blue-500'
            } [type] || 'bg-blue-500';

            const icon = {
                'success': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'error': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z',
                'warning': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z',
                'info': 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            } [type] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

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

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Quick stats refresh
        function refreshStats() {
            fetch('{{ route('admin.bookings.stats') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('total-bookings').textContent = data.statistics.total_bookings || 0;
                        document.getElementById('pending-bookings').textContent = data.statistics.pending_bookings || 0;
                        document.getElementById('confirmed-bookings').textContent = data.statistics
                            .confirmed_bookings || 0;
                        document.getElementById('total-revenue').textContent = formatCurrency(data.statistics
                            .total_revenue || 0) + '₫';
                    }
                })
                .catch(error => {
                    console.error('Error refreshing stats:', error);
                });
        }

        // Auto-refresh stats every 60 seconds
        setInterval(refreshStats, 60000);

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // ESC to close modals
            if (e.key === 'Escape') {
                closeBookingDetail();
                closeRoomSelection();
                closeBulkActions();
                hideLoading();
            }

            // Ctrl+F to focus search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="booking_code"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }

            // F5 to refresh
            if (e.key === 'F5') {
                e.preventDefault();
                refreshBookings();
            }

            // Ctrl+A to select all
            if (e.ctrlKey && e.key === 'a') {
                const selectAllCheckbox = document.getElementById('selectAll');
                if (selectAllCheckbox && document.activeElement.tagName !== 'INPUT') {
                    e.preventDefault();
                    selectAllCheckbox.click();
                }
            }
        });
    </script>

</x-app-layout>
