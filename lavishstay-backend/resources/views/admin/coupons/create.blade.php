<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Tạo Coupon Mới</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Tạo mã giảm giá mới cho khách hàng</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.coupons.index') }}"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z"/>
                    </svg>
                    <span class="max-xs:sr-only">Quay lại</span>
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-6 py-8">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                        <!-- Left Column - Basic Info -->
                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Thông tin cơ bản
                                </h3>
                                
                                <!-- Coupon Code -->
                                <div class="mb-4">
                                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mã Coupon <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="code" id="code" value="{{ old('code') }}" 
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('code') border-red-500 @enderror"
                                            placeholder="VD: SUMMER2024" style="text-transform: uppercase;">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('code')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mã sẽ tự động chuyển thành chữ hoa</p>
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mô tả
                                    </label>
                                    <textarea name="description" id="description" rows="4" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('description') border-red-500 @enderror"
                                        placeholder="Mô tả về coupon...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status Toggles -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div>
                                            <label for="active" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Kích hoạt coupon
                                            </label>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Coupon chỉ có thể sử dụng khi được kích hoạt</p>
                                        </div>
                                        <input type="checkbox" name="active" id="active" value="1" 
                                            {{ old('active', true) ? 'checked' : '' }}
                                            class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 dark:border-gray-600 rounded">
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div>
                                            <label for="stackable" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Có thể kết hợp
                                            </label>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Cho phép sử dụng cùng với coupon khác</p>
                                        </div>
                                        <input type="checkbox" name="stackable" id="stackable" value="1" 
                                            {{ old('stackable') ? 'checked' : '' }}
                                            class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 dark:border-gray-600 rounded">
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Tips -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Gợi ý
                                </h3>
                                
                                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Mã coupon nên ngắn gọn và dễ nhớ</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Đặt thời gian bắt đầu và kết thúc hợp lý</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Giới hạn sử dụng để tránh lạm dụng</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Middle Column - Discount Config -->
                        <div class="space-y-6">
                            <!-- Discount Configuration -->
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Cấu hình giảm giá
                                </h3>
                                
                                <!-- Discount Type -->
                                <div class="mb-4">
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Loại giảm giá <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="type" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('type') border-red-500 @enderror"
                                        onchange="toggleDiscountFields()">
                                        <option value="">Chọn loại giảm giá</option>
                                        <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Discount Value -->
                                <div class="mb-4">
                                    <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Giá trị giảm <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="value" id="value" value="{{ old('value') }}" 
                                            class="block w-full px-3 py-2 pr-12 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('value') border-red-500 @enderror"
                                            placeholder="0" min="0" step="0.01">
                                        <div id="discount_unit" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm font-medium"></span>
                                        </div>
                                    </div>
                                    @error('value')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Currency (for fixed type) -->
                                <div class="mb-4" id="currency_field" style="display: none;">
                                    <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Đơn vị tiền tệ
                                    </label>
                                    <select name="currency" id="currency" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                        <option value="VND" {{ old('currency', 'VND') === 'VND' ? 'selected' : '' }}>VND</option>
                                        <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                                    </select>
                                </div>

                                <!-- Minimum Booking Amount -->
                                <div class="mb-4">
                                    <label for="min_booking_amount_vnd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Số tiền booking tối thiểu (VND)
                                    </label>
                                    <input type="number" name="min_booking_amount_vnd" id="min_booking_amount_vnd" value="{{ old('min_booking_amount_vnd') }}" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('min_booking_amount_vnd') border-red-500 @enderror"
                                        placeholder="0" min="0" step="1000">
                                    @error('min_booking_amount_vnd')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Để trống nếu không có giới hạn</p>
                                </div>
                            </div>

                            <!-- Applicable Room Types -->
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Loại phòng áp dụng
                                </h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Chọn loại phòng (để trống để áp dụng cho tất cả)
                                    </label>
                                    <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-700">
                                        @php
                                            $roomTypes = [
                                                1 => 'Standard Room',
                                                2 => 'Deluxe Room', 
                                                3 => 'Suite Room',
                                                4 => 'Presidential Suite'
                                            ];
                                            $selectedRoomTypes = old('applicable_room_type_ids', []);
                                        @endphp
                                        
                                        @foreach($roomTypes as $id => $name)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="applicable_room_type_ids[]" value="{{ $id }}" 
                                                    {{ in_array($id, $selectedRoomTypes) ? 'checked' : '' }}
                                                    class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 dark:border-gray-600 rounded">
                                                <label class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Không chọn gì có nghĩa là áp dụng cho tất cả loại phòng</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Time & Limits -->
                        <div class="space-y-6">
                            <!-- Time Configuration -->
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Thời gian hiệu lực
                                </h3>
                                
                                <!-- Start Date -->
                                <div class="mb-4">
                                    <label for="start_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Ngày bắt đầu <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="start_at" id="start_at" value="{{ old('start_at') }}" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('start_at') border-red-500 @enderror">
                                    @error('start_at')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div class="mb-4">
                                    <label for="end_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Ngày kết thúc <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="end_at" id="end_at" value="{{ old('end_at') }}" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('end_at') border-red-500 @enderror">
                                    @error('end_at')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Quick Date Buttons -->
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <button type="button" onclick="setQuickDate(7)" 
                                        class="px-3 py-2 text-xs bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 rounded-md hover:bg-violet-200 dark:hover:bg-violet-900/50 transition-colors">
                                        7 ngày
                                    </button>
                                    <button type="button" onclick="setQuickDate(30)" 
                                        class="px-3 py-2 text-xs bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 rounded-md hover:bg-violet-200 dark:hover:bg-violet-900/50 transition-colors">
                                        30 ngày
                                    </button>
                                </div>
                            </div>

                            <!-- Usage Limits -->
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Giới hạn sử dụng
                                </h3>
                                
                                <!-- Usage Limit -->
                                <div class="mb-4">
                                    <label for="usage_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tổng số lần sử dụng
                                    </label>
                                    <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('usage_limit') border-red-500 @enderror"
                                        placeholder="Không giới hạn" min="1">
                                    @error('usage_limit')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Để trống nếu không giới hạn</p>
                                </div>

                                <!-- Per User Limit -->
                                <div class="mb-4">
                                    <label for="per_user_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Giới hạn mỗi người dùng
                                    </label>
                                    <input type="number" name="per_user_limit" id="per_user_limit" value="{{ old('per_user_limit') }}" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('per_user_limit') border-red-500 @enderror"
                                        placeholder="Không giới hạn" min="1">
                                    @error('per_user_limit')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Số lần tối đa mỗi user có thể sử dụng</p>
                                </div>

                                <!-- Quick Limit Buttons -->
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" onclick="setQuickLimit('usage_limit', 100)" 
                                        class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        100
                                    </button>
                                    <button type="button" onclick="setQuickLimit('usage_limit', 500)" 
                                        class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        500
                                    </button>
                                    <button type="button" onclick="setQuickLimit('usage_limit', 1000)" 
                                        class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        1000
                                    </button>
                                </div>
                            </div>

                            <!-- Combinable Coupons -->
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    Kết hợp với coupon khác
                                </h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Có thể kết hợp với các coupon sau:
                                    </label>
                                    <textarea name="combinable_with" id="combinable_with" rows="3" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                        placeholder="Nhập mã coupon, cách nhau bằng dấu phẩy">{{ old('combinable_with') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">VD: SUMMER2024, NEWUSER, LOYALTY10</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.coupons.index') }}" 
                            class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                            Hủy
                        </a>
                        <button type="submit" 
                            class="btn bg-violet-600 text-white hover:bg-violet-700 focus:ring-2 focus:ring-violet-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tạo Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto uppercase coupon code
        document.getElementById('code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });

        // Toggle discount fields based on type
        function toggleDiscountFields() {
            const discountType = document.getElementById('type').value;
            const discountUnit = document.getElementById('discount_unit');
            const currencyField = document.getElementById('currency_field');
            const discountValueInput = document.getElementById('value');

            if (discountType === 'percent') {
                discountUnit.innerHTML = '<span class="text-gray-500 dark:text-gray-400 text-sm font-medium">%</span>';
                currencyField.style.display = 'none';
                discountValueInput.setAttribute('max', '100');
                discountValueInput.setAttribute('placeholder', '0-100');
            } else if (discountType === 'fixed') {
                discountUnit.innerHTML = '<span class="text-gray-500 dark:text-gray-400 text-sm font-medium">VND</span>';
                currencyField.style.display = 'block';
                discountValueInput.removeAttribute('max');
                discountValueInput.setAttribute('placeholder', '0');
            } else {
                discountUnit.innerHTML = '';
                currencyField.style.display = 'none';
                discountValueInput.removeAttribute('max');
                discountValueInput.setAttribute('placeholder', '0');
            }
        }

        // Set quick date ranges
        function setQuickDate(days) {
            const now = new Date();
            const startDate = new Date(now);
            const endDate = new Date(now);
            endDate.setDate(endDate.getDate() + days);

            // Format for datetime-local input
            const formatDateTime = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day}T${hours}:${minutes}`;
            };

            document.getElementById('start_at').value = formatDateTime(startDate);
            document.getElementById('end_at').value = formatDateTime(endDate);
        }

        // Set quick limit values
        function setQuickLimit(fieldId, value) {
            document.getElementById(fieldId).value = value;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleDiscountFields();
        });

        // Format number inputs
        document.getElementById('min_booking_amount_vnd').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                e.target.value = parseInt(value).toLocaleString('vi-VN');
            }
        });

        // Handle combinable_with as array
        document.querySelector('form').addEventListener('submit', function(e) {
            const combinableWith = document.getElementById('combinable_with').value;
            if (combinableWith) {
                // Convert comma-separated string to array format for backend
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'combinable_with_array';
                hiddenInput.value = JSON.stringify(combinableWith.split(',').map(s => s.trim()).filter(s => s));
                this.appendChild(hiddenInput);
            }
        });
    </script>
</x-app-layout>