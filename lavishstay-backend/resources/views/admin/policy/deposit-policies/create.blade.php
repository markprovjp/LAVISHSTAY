<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8 flex justify-between items-center">
            <div class=" space-x-3 ">
                
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Tạo chính sách đặt cọc mới</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Tạo một chính sách đặt cọc mới cho hệ thống đặt phòng</p>

            </div>
            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back Button -->
                <a href="{{ route('admin.deposit-policies') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <i class="fa-solid fa-backward"></i><span class="ml-2">Back to List</span>
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-6 py-8">
                <form action="{{ route('admin.deposit-policies.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Policy Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1" for="name">Tên chính sách <span class="text-red-500">*</span></label>
                        <input id="name" name="name" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('name') border-red-500 @enderror" type="text" value="{{ old('name') }}" required />
                        <div class="text-xs mt-1 text-gray-500">Tên mô tả cho chính sách đặt cọc</div>
                        @error('name')
                            <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deposit Methods -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Deposit Percentage -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="deposit_percentage">Phần trăm đặt cọc (%)</label>
                            <input id="deposit_percentage" name="deposit_percentage" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('deposit_percentage') border-red-500 @enderror" type="number" min="0" max="100" step="0.01" value="{{ old('deposit_percentage') }}" />
                            <div class="text-xs mt-1 text-gray-500">Phần trăm của tổng giá trị đặt phòng (0-100%)</div>
                            @error('deposit_percentage')
                                <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fixed Deposit Amount -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="deposit_fixed_amount_vnd">Số tiền cố định (VND)</label>
                            <input id="deposit_fixed_amount_vnd" name="deposit_fixed_amount_vnd" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('deposit_fixed_amount_vnd') border-red-500 @enderror" type="number" min="0" step="1000" value="{{ old('deposit_fixed_amount_vnd') }}" />
                            <div class="text-xs mt-1 text-gray-500">Số tiền cố định cần đặt cọc (VND)</div>
                            @error('deposit_fixed_amount_vnd')
                                <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Validation Error for Deposit Method -->
                    @error('deposit_method')
                        <div class="text-sm text-red-500 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium mb-1" for="description">Mô tả</label>
                        <textarea id="description" name="description" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('description') border-red-500 @enderror" rows="4" placeholder="Mô tả chi tiết về chính sách đặt cọc...">{{ old('description') }}</textarea>
                        <div class="text-xs mt-1 text-gray-500">Mô tả chi tiết về chính sách (tối đa 1000 ký tự)</div>
                        @error('description')
                            <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" class="form-checkbox" {{ old('is_active', true) ? 'checked' : '' }} />
                            <label class="text-sm ml-2" for="is_active">Kích hoạt chính sách</label>
                        </div>
                        <div class="text-xs mt-1 text-gray-500">Chính sách sẽ có thể được sử dụng ngay sau khi tạo</div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.deposit-policies') }}" 
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

        <!-- Help Section -->
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Hướng dẫn</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Bạn có thể thiết lập đặt cọc theo phần trăm hoặc số tiền cố định</li>
                            <li>Ít nhất một trong hai phương thức đặt cọc phải được thiết lập</li>
                            <li>Nếu thiết lập cả hai, hệ thống sẽ ưu tiên sử dụng phần trăm</li>
                            <li>Chính sách chỉ có hiệu lực khi được kích hoạt</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
