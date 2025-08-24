<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Chi Tiết Chính Sách Bồi Thường</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Thông tin chi tiết về chính sách bồi thường</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.compensation-policies') }}"
                    class="btn bg-gray-500 text-white hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Quay lại</span>
                </a>
                <a href="{{ route('admin.compensation-policies.edit', $policy->compensation_policy_id) }}"
                    class="btn bg-violet-600 text-white hover:bg-violet-700 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Sửa</span>
                </a>
                <button onclick="deletePolicy({{ $policy->compensation_policy_id }})"
                    class="btn bg-red-600 text-white hover:bg-red-700 transition-colors duration-200 cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Xóa</span>
                </button>
            </div>
        </div>

        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Chi Tiết Chính Sách Bồi Thường') }}
                </h2>
            </div>
        </x-slot>

        <div class="py-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Policy Information Card -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Thông tin chính sách</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tên:</span>
                            <p class="text-base text-gray-900 dark:text-gray-100 mt-1">{{ $policy->name }}</p>
                        </div>
                        @if ($policy->description)
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mô tả:</span>
                                <p class="text-base text-gray-900 dark:text-gray-100 mt-1">{{ $policy->description }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Loại sự cố:</span>
                            <p class="text-base text-gray-900 dark:text-gray-100 mt-1">
                                {{ $policy->condition_type_label }}</p>
                        </div>
                    </div>
                </div>

                <!-- Compensation Rules Card -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quy định bồi thường</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Loại giảm giá:</span>
                            <p class="text-base text-gray-900 dark:text-gray-100 mt-1">
                                {{ $policy->discount_type_label }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Giá trị giảm giá:</span>
                            <p class="text-base text-gray-900 dark:text-gray-100 mt-1">
                                {{ $policy->formatted_discount_value }}</p>
                        </div>
                        @if ($policy->max_compensation_amount)
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mức bồi thường tối
                                    đa:</span>
                                <p class="text-base text-gray-900 dark:text-gray-100 mt-1">
                                    {{ number_format($policy->max_compensation_amount, 0, ',', '.') }} VND</p>
                            </div>
                        @endif
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Loại phòng áp
                                dụng:</span>
                            <p class="text-base text-gray-900 dark:text-gray-100 mt-1">
                                {{ $policy->roomType ? $policy->roomType->name : 'Tất cả' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status and Timestamps Card -->
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Trạng thái</h3>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tình trạng hiện
                                        tại:</span>
                                    <p
                                        class="text-base mt-1 {{ $policy->is_active == 1 ? 'text-green-600' : 'text-red-600' }} dark:text-gray-100">
                                        {{ $policy->is_active == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Dấu thời gian</h3>
                            <div class="space-y-2 text-gray-600 dark:text-gray-400">
                                <div><span class="font-medium">Ngày tạo:</span>
                                    {{ $policy->created_at->format('d/m/Y H:i') }}</div>
                                <div><span class="font-medium">Ngày sửa:</span>
                                    {{ $policy->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Delete Policy
        function deletePolicy(policyId) {
            if (confirm('Bạn có chắc chắn muốn xóa chính sách này? Hành động này không thể hoàn tác!')) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/compensation-policies/${policyId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
