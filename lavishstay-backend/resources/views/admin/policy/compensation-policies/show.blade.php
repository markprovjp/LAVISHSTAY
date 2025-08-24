<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chi Tiết Chính Sách Bồi Thường</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Xem thông tin chi tiết của chính sách bồi thường</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.compensation-policies') }}">
                    <button class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M9 12l-7-7 1.41-1.41L9 9.17l5.59-5.58L14 4z" />
                        </svg>
                        <span class="max-xs:sr-only">Quay lại</span>
                    </button>
                </a>
            </div>
        </div>

        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Chi Tiết Chính Sách Bồi Thường') }}
                </h2>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Thông tin chính sách</h3>
                                    <div class="mt-4 space-y-2">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tên:</span>
                                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $policy->name }}</p>
                                        </div>
                                        @if ($policy->description)
                                            <div>
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mô tả:</span>
                                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $policy->description }}</p>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Loại sự cố:</span>
                                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $policy->condition_type_label }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Quy định bồi thường</h3>
                                    <div class="mt-4 space-y-2">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Loại giảm giá:</span>
                                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $policy->discount_type_label }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Giá trị giảm giá:</span>
                                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $policy->formatted_discount_value }}</p>
                                        </div>
                                        @if ($policy->max_compensation_amount)
                                            <div>
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mức bồi thường tối đa:</span>
                                                <p class="text-base text-gray-900 dark:text-gray-100">{{ number_format($policy->max_compensation_amount, 0, ',', '.') }} VND</p>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Loại phòng áp dụng:</span>
                                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $policy->roomType ? $policy->roomType->name : 'Tất cả' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Thông tin trạng thái</h3>
                                    <div class="mt-4 space-y-2">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tình trạng hiện tại:</span>
                                            <p class="text-base {{ $policy->is_active ? 'text-green-600' : 'text-red-600' }} dark:text-gray-100">
                                                {{ $policy->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Dấu thời gian</h3>
                                    <div class="mt-4 space-y-2 text-gray-600 dark:text-gray-400">
                                        <div><span class="font-medium">Ngày tạo:</span> {{ $policy->created_at->format('d/m/Y H:i') }}</div>
                                        <div><span class="font-medium">Ngày sửa:</span> {{ $policy->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>