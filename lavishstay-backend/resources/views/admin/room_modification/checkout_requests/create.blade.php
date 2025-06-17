<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">Thêm Yêu cầu trả phòng</h1>
            <p class="text-gray-600 dark:text-gray-400">Tạo yêu cầu trả phòng mới cho khách hàng</p>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow">
                <p class="font-semibold">Đã xảy ra lỗi:</p>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-300 dark:border-gray-600 px-6 py-8">
            <form action="{{ route('admin.check_out_requests.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Booking ID -->
                    <div>
                        <label for="booking_id" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">ID Đặt phòng *</label>
                        <select name="booking_id" id="booking_id"
                            class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                            <option value="">Chọn ID đặt phòng</option>
                            @if (isset($bookings))
                                @foreach ($bookings as $booking)
                                    <option value="{{ $booking->booking_id }}">{{ $booking->booking_id }} - {{ $booking->check_out_date }}</option>
                                @endforeach
                            @else
                                <option value="">Không có booking nào</option>
                            @endif
                        </select>
                        @error('booking_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Selection -->
                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Thời gian trả phòng mới *</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Hour -->
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400">Giờ</label>
                                <select name="checkout_hour"
                                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white"
                                    required>
                                    <option value="">--</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Minute -->
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400">Phút</label>
                                <select name="checkout_minute"
                                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white"
                                    required>
                                    <option value="">--</option>
                                    @for ($i = 0; $i < 60; $i++)
                                        <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Period -->
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400">SA / CH</label>
                                <select name="checkout_period"
                                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white"
                                    required>
                                    <option value="">--</option>
                                    <option value="AM">SA (Sáng)</option>
                                    <option value="PM">CH (Chiều)</option>
                                </select>
                            </div>
                        </div>

                        @error('checkout_hour') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @endif
                        @error('checkout_minute') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @endif
                        @error('checkout_period') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @endif
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="checkout_date" class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Ngày trả phòng mới *</label>
                        <input type="date" name="checkout_date" id="checkout_date"
                            class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white"
                            required>
                        @error('checkout_date')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @endif
                    </div>
                </div>

                <!-- Spacer -->
                <div class="mt-6"></div> <!-- Thêm khoảng cách -->

                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-end gap-4">
                    <a href="{{ route('admin.check_out_requests') }}"
                        class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-md hover:bg-red-600 transition-colors">
                        Hủy bỏ
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-md hover:bg-green-600 transition-colors">
                        Thêm Yêu cầu
                    </button>
                </div>
            </form>
        </div>

        <!-- Note -->   
        <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            <span class="text-red-500">*</span> Các trường bắt buộc
        </div>
    </div>
</x-app-layout>