<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Tạo chính sách check-out mới</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Thêm chính sách check-out mới cho hệ thống đặt phòng</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.checkout-policies') }}"
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
                <form action="{{ route('admin.checkout-policies.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Basic Information -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Thông tin cơ bản</h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Policy Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tên chính sách <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('name') border-red-500 @enderror"
                                    placeholder="Nhập tên chính sách check-out" maxlength="100">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Mô tả
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('description') border-red-500 @enderror"
                                    placeholder="Nhập mô tả cho chính sách check-out">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Standard Check-out Time -->
                            <div>
                                <label for="standard_check_out_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Giờ check-out tiêu chuẩn
                                </label>
                                <input type="time" name="standard_check_out_time" id="standard_check_out_time" value="{{ old('standard_check_out_time') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('standard_check_out_time') border-red-500 @enderror">
                                @error('standard_check_out_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Policy Settings -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Cài đặt chính sách</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Late Check-out Fee -->
                            <div>
                                <label for="late_check_out_fee_vnd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Phí check-out muộn (VND)
                                </label>
                                <input type="number" name="late_check_out_fee_vnd" id="late_check_out_fee_vnd" value="{{ old('late_check_out_fee_vnd') }}" min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('late_check_out_fee_vnd') border-red-500 @enderror"
                                    placeholder="Nhập phí check-out muộn">
                                @error('late_check_out_fee_vnd')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Mức độ ưu tiên
                                </label>
                                <input type="number" name="priority" id="priority" value="{{ old('priority', 0) }}" min="0" max="999"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('priority') border-red-500 @enderror"
                                    placeholder="Nhập mức độ ưu tiên (0-999)">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Số càng cao thì ưu tiên càng cao. Mặc định là 0.</p>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Conditions and Actions -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Điều kiện và hành động</h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Conditions -->
                            <div>
                                <label for="conditions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Điều kiện áp dụng chính sách
                                </label>
                                <textarea name="conditions" id="conditions" rows="4"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('conditions') border-red-500 @enderror"
                                    placeholder="Ví dụ: Áp dụng cho check-out sau 12:00, chỉ áp dụng cho phòng VIP, yêu cầu thanh toán phí...">{{ old('conditions') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mô tả các điều kiện để áp dụng chính sách này (thời gian, loại phòng, thanh toán, v.v.)</p>
                                @error('conditions')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action -->
                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Hành động khi áp dụng chính sách
                                </label>
                                <textarea name="action" id="action" rows="4"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('action') border-red-500 @enderror"
                                    placeholder="Ví dụ: Cho phép check-out và thu phí, từ chối check-out, yêu cầu xác nhận từ quản lý...">{{ old('action') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mô tả hành động sẽ được thực hiện khi chính sách này được áp dụng</p>
                                @error('action')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Trạng thái</h3>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 dark:border-gray-600 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50 dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Kích hoạt chính sách</span>
                            </label>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.checkout-policies') }}"
                            class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                            Hủy
                        </a>
                        <button type="submit"
                            class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                            Tạo chính sách
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>