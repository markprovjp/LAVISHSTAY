<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Chỉnh Sửa Chính Sách Bồi Thường</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Cập nhật thông tin cho chính sách bồi thường</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.compensation-policies') }}">
                    <button class="btn bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-gray-200 flex items-center px-4 py-2 rounded-md transition-colors duration-200">
                        <svg class="fill-current shrink-0 w-4 h-4 mr-2" viewBox="0 0 16 16">
                            <path d="M9 12l-7-7 1.41-1.41L9 9.17l5.59-5.58L14 4z" />
                        </svg>
                        <span class="max-xs:sr-only cursor-pointer">Quay lại</span>
                    </button>
                </a>
            </div>
        </div>

        <div class="py-6">
            <div class="">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-sm">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6">
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.compensation-policies.update', $policy->compensation_policy_id) }}" onsubmit="return confirmSubmit();" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tên chính sách <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $policy->name) }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm transition-colors duration-200" required>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tên duy nhất, tối đa 255 ký tự.</p>
                                    </div>
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mô tả</label>
                                        <textarea name="description" id="description" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm resize-y h-24 transition-colors duration-200">{{ old('description', $policy->description) }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mô tả chi tiết về chính sách (tùy chọn).</p>
                                    </div>
                                    <div>
                                        <label for="applies_to_room_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Loại phòng áp dụng</label>
                                        <select name="applies_to_room_type_id" id="applies_to_room_type_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm transition-colors duration-200">
                                            <option value="">Tất cả</option>
                                            @foreach ($roomTypes as $roomType)
                                                <option value="{{ $roomType->room_type_id }}" {{ old('applies_to_room_type_id', $policy->applies_to_room_type_id) == $roomType->room_type_id ? 'selected' : '' }}>
                                                    {{ $roomType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Chọn loại phòng hoặc để trống cho tất cả.</p>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="condition_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Loại sự cố <span class="text-red-500">*</span></label>
                                        <select name="condition_type" id="condition_type" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm transition-colors duration-200" required>
                                            <option value="" disabled {{ old('condition_type', $policy->condition_type) ? '' : 'selected' }}>Chọn loại sự cố</option>
                                            <option value="room_damage" {{ old('condition_type', $policy->condition_type) === 'room_damage' ? 'selected' : '' }}>Hư hỏng phòng</option>
                                            <option value="service_failure" {{ old('condition_type', $policy->condition_type) === 'service_failure' ? 'selected' : '' }}>Lỗi dịch vụ</option>
                                            <option value="overbooking" {{ old('condition_type', $policy->condition_type) === 'overbooking' ? 'selected' : '' }}>Quá tải đặt phòng</option>
                                            <option value="other" {{ old('condition_type', $policy->condition_type) === 'other' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Chọn loại sự cố áp dụng.</p>
                                    </div>
                                    <div>
                                        <label for="discount_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Loại giảm giá <span class="text-red-500">*</span></label>
                                        <select name="discount_type" id="discount_type" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm transition-colors duration-200" required>
                                            <option value="" disabled {{ old('discount_type', $policy->discount_type) ? '' : 'selected' }}>Chọn loại giảm giá</option>
                                            <option value="percentage" {{ old('discount_type', $policy->discount_type) === 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                            <option value="fixed_amount" {{ old('discount_type', $policy->discount_type) === 'fixed_amount' ? 'selected' : '' }}>Số tiền cố định</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Chọn loại giảm giá áp dụng.</p>
                                    </div>
                                    <div>
                                        <label for="discount_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giá trị giảm giá <span class="text-red-500">*</span></label>
                                        <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value', $policy->discount_value) }}" step="0.01" min="0" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm transition-colors duration-200" required>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nhập giá trị (≥ 0).</p>
                                    </div>
                                    <div>
                                        <label for="max_compensation_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mức bồi thường tối đa</label>
                                        <input type="number" name="max_compensation_amount" id="max_compensation_amount" value="{{ old('max_compensation_amount', $policy->max_compensation_amount) }}" step="0.01" min="0" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100 text-sm transition-colors duration-200">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nhập mức tối đa (tùy chọn, ≥ 0).</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái <span class="text-gray-500 text-xs">(Chọn để kích hoạt)</span></label>
                                    <div class="mt-1 flex items-center">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $policy->is_active) == 1 ? 'checked' : '' }} class="h-4 w-4 text-violet-600 focus:ring-2 focus:ring-violet-500 border-gray-300 dark:border-gray-600 rounded transition-colors duration-200">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Hoạt động</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Bỏ chọn để đặt trạng thái thành 'Không hoạt động'.</p>
                                </div>

                                <div class="flex justify-end gap-4">
                                    <a href="{{ route('admin.compensation-policies') }}" class="btn bg-gray-900 text-white hover:bg-gray-800 flex items-center px-4 py-2 rounded-md transition-colors duration-200">
                                        <svg class="fill-current shrink-0 w-4 h-4 mr-2" viewBox="0 0 16 16">
                                            <path d="M9 12l-7-7 1.41-1.41L9 9.17l5.59-5.58L14 4z" />
                                        </svg>
                                        Quay lại
                                    </a>
                                    <button type="submit" class="bg-violet-600 text-white hover:bg-violet-700 flex items-center px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 transition-colors duration-200">
                                        <svg class="fill-current shrink-0 w-4 h-4 mr-2" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        </svg>
                                        Cập Nhật Chính Sách
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmSubmit() {
            const isActive = document.querySelector('#is_active').checked;
            const confirmation = isActive ? "Bạn có chắc chắn muốn kích hoạt chính sách này?" : "Bạn có chắc chắn muốn đặt chính sách này thành 'Không hoạt động'?";
            return confirm(confirmation);
        }
    </script>
</x-app-layout>