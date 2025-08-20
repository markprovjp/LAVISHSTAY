<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chỉnh sửa chính sách phụ thu trẻ em</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Cập nhật chính sách phụ thu cho độ tuổi {{ $childrenSurcharge->min_age }} - {{ $childrenSurcharge->max_age }} tuổi</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.children-surcharge') }}"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z" />
                    </svg>
                    <span class="max-xs:sr-only">Quay lại</span>
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <div class="px-6 py-8">
                <form action="{{ route('admin.children-surcharge.update', $childrenSurcharge) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Age Range Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Khoảng độ tuổi</h3>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Min Age -->
                            <div>
                                <label for="min_age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tuổi tối thiểu <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="min_age" 
                                       name="min_age" 
                                       value="{{ old('min_age', $childrenSurcharge->min_age) }}"
                                       min="0" 
                                       max="255"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('min_age') border-red-500 @enderror"
                                       placeholder="Ví dụ: 0">
                                @error('min_age')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Age -->
                            <div>
                                <label for="max_age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tuổi tối đa <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="max_age" 
                                       name="max_age" 
                                       value="{{ old('max_age', $childrenSurcharge->max_age) }}"
                                       min="0" 
                                       max="255"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('max_age') border-red-500 @enderror"
                                       placeholder="Ví dụ: 5">
                                @error('max_age')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @error('age_range')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Policy Type Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Loại chính sách</h3>
                        
                        <!-- Policy Options -->
                        <div class="space-y-4">
                            <!-- Free Option -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_free" 
                                           name="is_free" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('is_free', $childrenSurcharge->is_free) ? 'checked' : '' }}
                                           onchange="handlePolicyTypeChange()"
                                           class="focus:ring-violet-500 h-4 w-4 text-violet-600 border-gray-300 dark:border-gray-600 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_free" class="font-medium text-gray-700 dark:text-gray-300">
                                        Miễn phí
                                    </label>
                                    <p class="text-gray-500 dark:text-gray-400">Trẻ em trong độ tuổi này được miễn phí hoàn toàn</p>
                                </div>
                            </div>

        
                            <!-- Surcharge Option -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="requires_extra_bed" 
                                           name="requires_extra_bed" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('requires_extra_bed', $childrenSurcharge->requires_extra_bed) ? 'checked' : '' }}
                                           onchange="handlePolicyTypeChange()"
                                           class="focus:ring-violet-500 h-4 w-4 text-violet-600 border-gray-300 dark:border-gray-600 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="requires_extra_bed" class="font-medium text-gray-700 dark:text-gray-300">
                                        Giường phụ
                                    </label>
                                    <p class="text-gray-500 dark:text-gray-400">Trẻ em trong độ tuổi này có giường phụ hoặc không</p>
                                </div>
                            </div>

                            <!-- Count as Adult Option -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="count_as_adult" 
                                           name="count_as_adult" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('count_as_adult', $childrenSurcharge->count_as_adult) ? 'checked' : '' }}
                                           onchange="handlePolicyTypeChange()"
                                           class="focus:ring-violet-500 h-4 w-4 text-violet-600 border-gray-300 dark:border-gray-600 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="count_as_adult" class="font-medium text-gray-700 dark:text-gray-300">
                                        Tính như người lớn
                                    </label>
                                    <p class="text-gray-500 dark:text-gray-400">Trẻ em trong độ tuổi này được tính giá như người lớn</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Surcharge Amount Section -->
                    <div id="surcharge_section" class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Mức phụ thu</h3>
                        <div>
                            <label for="surcharge_amount_vnd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mức phụ thu (VND)
                            </label>
                            <input type="number" 
                                   id="surcharge_amount_vnd" 
                                   name="surcharge_amount_vnd" 
                                   value="{{ old('surcharge_amount_vnd', $childrenSurcharge->surcharge_amount_vnd) }}"
                                   min="0"
                                   step="1000"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('surcharge_amount_vnd') border-red-500 @enderror"
                                   placeholder="Ví dụ: 100000">
                            @error('surcharge_amount_vnd')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Nhập mức phụ thu cố định cho trẻ em trong độ tuổi này
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.children-surcharge') }}"
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
    </div>

    <script>
        function handlePolicyTypeChange() {
            const isFree = document.getElementById('is_free').checked;
            const countAsAdult = document.getElementById('count_as_adult').checked;
            const surchargeSection = document.getElementById('surcharge_section');
            const surchargeInput = document.getElementById('surcharge_amount_vnd');

            // If free or count as adult is checked, hide surcharge section
            if (isFree || countAsAdult) {
                surchargeSection.style.display = 'none';
                surchargeInput.value = '';
                surchargeInput.removeAttribute('required');
            } else {
                surchargeSection.style.display = 'block';
                surchargeInput.setAttribute('required', 'required');
            }

            // Prevent both checkboxes from being checked
            if (isFree && countAsAdult) {
                if (event.target.id === 'is_free') {
                    document.getElementById('count_as_adult').checked = false;
                } else {
                    document.getElementById('is_free').checked = false;
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            handlePolicyTypeChange();
        });
    </script>
</x-app-layout>

