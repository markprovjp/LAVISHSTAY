<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Thêm chính sách hủy mới</h1>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="p-6">
                <form action="{{ route('admin.cancellation-policies.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1" for="name">Tên chính sách <span class="text-red-500">*</span></label>
                            <input id="name" name="name" class="form-input w-full @error('name') border-red-500 @enderror" type="text" value="{{ old('name') }}" required />
                            @error('name')
                                <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Free Cancellation Days -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="free_cancellation_days">Số ngày hủy miễn phí</label>
                            <input id="free_cancellation_days" name="free_cancellation_days" class="form-input w-full @error('free_cancellation_days') border-red-500 @enderror" type="number" min="0" value="{{ old('free_cancellation_days') }}" />
                            <div class="text-xs mt-1 text-gray-500">Số ngày trước check-in được hủy miễn phí</div>
                            @error('free_cancellation_days')
                                <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Penalty Percentage -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="penalty_percentage">Phạt hủy (%)</label>
                            <input id="penalty_percentage" name="penalty_percentage" class="form-input w-full @error('penalty_percentage') border-red-500 @enderror" type="number" min="0" max="100" step="0.01" value="{{ old('penalty_percentage') }}" />
                            <div class="text-xs mt-1 text-gray-500">Phần trăm phạt khi hủy (0-100%)</div>
                            @error('penalty_percentage')
                                <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fixed Penalty Amount -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="penalty_fixed_amount_vnd">Phạt cố định (VND)</label>
                            <input id="penalty_fixed_amount_vnd" name="penalty_fixed_amount_vnd" class="form-input w-full @error('penalty_fixed_amount_vnd') border-red-500 @enderror" type="number" min="0" step="1000" value="{{ old('penalty_fixed_amount_vnd') }}" />
                            <div class="text-xs mt-1 text-gray-500">Số tiền phạt cố định khi hủy</div>
                            @error('penalty_fixed_amount_vnd')
                                <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Trạng thái</label>
                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" class="form-checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                                <label class="text-sm ml-2" for="is_active">Kích hoạt</label>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1" for="description">Mô tả</label>
                            <textarea id="description" name="description" class="form-textarea w-full @error('description') border-red-500 @enderror" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <!-- Form footer -->
                    <div class="flex flex-col px-6 py-5 border-t border-gray-100 dark:border-gray-700/60">
                        <div class="flex self-end">
                            <a href="{{ route('admin.cancellation-policies') }}" class="btn border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">Hủy</a>
                            <button type="submit" class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white ml-3">Tạo chính sách</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-app-layout>
