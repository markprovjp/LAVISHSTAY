<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Tạo Chính Sách Bồi Thường Mới</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Điền thông tin để tạo một chính sách bồi thường mới</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.compensation-policies') }}">
                    <button class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M9 12l-7-7 1.41-1.41L9 9.17l5.59-5.58L14 4z" />
                        </svg>
                        <span class="max-xs:sr-only cursor-pointer">Quay lại</span>
                    </button>
                </a>
            </div>
        </div>
        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.compensation-policies.store') }}" class="space-y-6">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tên chính sách <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100" required>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mô tả</label>
                                <textarea name="description" id="description" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100">{{ old('description') }}</textarea>
                            </div>
                            <div>
                                <label for="applies_to_room_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Loại phòng áp dụng</label>
                                <select name="applies_to_room_type_id" id="applies_to_room_type_id" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="">Tất cả</option>
                                    @foreach ($roomTypes as $roomType)
                                        <option value="{{ $roomType->room_type_id }}" {{ old('applies_to_room_type_id') == $roomType->room_type_id ? 'selected' : '' }}>
                                            {{ $roomType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="condition_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Loại sự cố <span class="text-red-500">*</span></label>
                                <select name="condition_type" id="condition_type" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100" required>
                                    <option value="" disabled {{ old('condition_type') ? '' : 'selected' }}>Chọn loại sự cố</option>
                                    <option value="room_damage" {{ old('condition_type') === 'room_damage' ? 'selected' : '' }}>Hư hỏng phòng</option>
                                    <option value="service_failure" {{ old('condition_type') === 'service_failure' ? 'selected' : '' }}>Lỗi dịch vụ</option>
                                    <option value="overbooking" {{ old('condition_type') === 'overbooking' ? 'selected' : '' }}>Quá tải đặt phòng</option>
                                    <option value="other" {{ old('condition_type') === 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div>
                                <label for="discount_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Loại giảm giá <span class="text-red-500">*</span></label>
                                <select name="discount_type" id="discount_type" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100" required>
                                    <option value="" disabled {{ old('discount_type') ? '' : 'selected' }}>Chọn loại giảm giá</option>
                                    <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                    <option value="fixed_amount" {{ old('discount_type') === 'fixed_amount' ? 'selected' : '' }}>Số tiền cố định</option>
                                </select>
                            </div>
                            <div>
                                <label for="discount_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giá trị giảm giá <span class="text-red-500">*</span></label>
                                <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value') }}" step="0.01" min="0" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100" required>
                            </div>
                            <div>
                                <label for="max_compensation_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mức bồi thường tối đa</label>
                                <input type="number" name="max_compensation_amount" id="max_compensation_amount" value="{{ old('max_compensation_amount') }}" step="0.01" min="0" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-100">
                            </div>
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                                <input type="checkbox" name="is_active" id="is_active" {{ old('is_active') ? 'checked' : '' }} class="mt-1 h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 dark:border-gray-600 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Hoạt động</span>
                            </div>
                            <div>
                                <button type="submit" class="w-full bg-violet-500 text-white py-2 px-4 rounded-md hover:bg-violet-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 cursor-pointer">
                                    Tạo Chính Sách
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>