<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chỉnh sửa chính sách chuyển phòng</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Cập nhật thông tin chính sách chuyển phòng: {{ $policy->name }}</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.transfer-policies') }}"
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
                <form action="{{ route('admin.transfer-policies.update', $policy->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Thông tin cơ bản</h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Policy Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tên chính sách <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $policy->name) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('name') border-red-500 @enderror"
                                    placeholder="Nhập tên chính sách chuyển phòng">
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
                                    placeholder="Nhập mô tả cho chính sách chuyển phòng">{{ old('description', $policy->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Room Type -->
                            <div>
                                <label for="room_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Loại phòng áp dụng
                                </label>
                                <select name="room_type_id" id="room_type_id"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('room_type_id') border-red-500 @enderror">
                                    <option value="">Tất cả loại phòng</option>
                                    @foreach($roomTypes as $roomType)
                                        <option value="{{ $roomType->id }}" {{ old('room_type_id', $policy->room_type_id) == $roomType->id ? 'selected' : '' }}>
                                            {{ $roomType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_type_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Settings -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Cài đặt chuyển phòng</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Transfer Fee VND -->
                            <div>
                                <label for="transfer_fee_vnd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Phí chuyển phòng (VND)
                                </label>
                                <input type="number" name="transfer_fee_vnd" id="transfer_fee_vnd" value="{{ old('transfer_fee_vnd', $policy->transfer_fee_vnd) }}" min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('transfer_fee_vnd') border-red-500 @enderror"
                                    placeholder="Nhập phí chuyển phòng">
                                @error('transfer_fee_vnd')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Transfer Fee Percentage -->
                            <div>
                                <label for="transfer_fee_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Phần trăm phí chuyển phòng (%)
                                </label>
                                <input type="number" name="transfer_fee_percentage" id="transfer_fee_percentage" value="{{ old('transfer_fee_percentage', $policy->transfer_fee_percentage) }}" min="0" max="100" step="0.01"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('transfer_fee_percentage') border-red-500 @enderror"
                                    placeholder="Nhập phần trăm phí">
                                @error('transfer_fee_percentage')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Min Days Before Check In -->
                            <div>
                                <label for="min_days_before_check_in" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Số ngày tối thiểu trước check-in
                                </label>
                                <input type="number" name="min_days_before_check_in" id="min_days_before_check_in" value="{{ old('min_days_before_check_in', $policy->min_days_before_check_in) }}" min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('min_days_before_check_in') border-red-500 @enderror"
                                    placeholder="Nhập số ngày">
                                @error('min_days_before_check_in')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Requires Guest Confirmation -->
                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input type="checkbox" name="requires_guest_confirmation" id="requires_guest_confirmation" value="1" {{ old('requires_guest_confirmation', $policy->requires_guest_confirmation) ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-gray-600 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yêu cầu xác nhận từ khách hàng</span>
                                </label>
                                @error('requires_guest_confirmation')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Application Rules -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Quy tắc áp dụng</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Applies to Holiday -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="applies_to_holiday" id="applies_to_holiday" value="1" {{ old('applies_to_holiday', $policy->applies_to_holiday) ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-gray-600 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Áp dụng cho ngày lễ</span>
                                </label>
                                @error('applies_to_holiday')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Applies to Weekend -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="applies_to_weekend" id="applies_to_weekend" value="1" {{ old('applies_to_weekend', $policy->applies_to_weekend) ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-gray-600 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Áp dụng cho cuối tuần</span>
                                </label>
                                @error('applies_to_weekend')
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
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $policy->is_active) ? 'checked' : '' }}
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
                        <a href="{{ route('admin.transfer-policies') }}"
                            class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                            Hủy
                        </a>
                        <button type="submit"
                            class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                            Cập nhật chính sách
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Policy Information Card -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6">
            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Thông tin chính sách</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">ID:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $policy->id }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Ngày tạo:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $policy->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Cập nhật lần cuối:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $policy->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>