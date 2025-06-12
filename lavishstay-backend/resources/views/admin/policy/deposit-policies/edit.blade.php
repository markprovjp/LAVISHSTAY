<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8 flex justify-between items-center">
            <div class="space-x-3 mb-4">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chỉnh sửa chính sách đặt cọc</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Cập nhật thông tin chính sách đặt cọc: {{ $depositPolicy->name }}</p>

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
                <form action="{{ route('admin.deposit-policies.update', $depositPolicy) }}" method="POST" class="space-y-6" id="policyForm">
                    @csrf
                    @method('PUT')

                    <!-- Policy Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1" for="name">Tên chính sách <span class="text-red-500">*</span></label>
                        <input id="name" name="name" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('name') border-red-500 @enderror" type="text" value="{{ old('name', $depositPolicy->name) }}" required />
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
                            <input id="deposit_percentage" name="deposit_percentage" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('deposit_percentage') border-red-500 @enderror" type="number" min="0" max="100" step="0.01" value="{{ old('deposit_percentage', $depositPolicy->deposit_percentage) }}" />
                            <div class="text-xs mt-1 text-gray-500">Phần trăm của tổng giá trị đặt phòng (0-100%)</div>
                            @error('deposit_percentage')
                                <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fixed Deposit Amount -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="deposit_fixed_amount_vnd">Số tiền cố định (VND)</label>
                            <input id="deposit_fixed_amount_vnd" name="deposit_fixed_amount_vnd" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('deposit_fixed_amount_vnd') border-red-500 @enderror" type="number" min="0" step="1000" value="{{ old('deposit_fixed_amount_vnd', $depositPolicy->deposit_fixed_amount_vnd) }}" />
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
                        <textarea id="description" name="description" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('description') border-red-500 @enderror" rows="4" placeholder="Mô tả chi tiết về chính sách đặt cọc...">{{ old('description', $depositPolicy->description) }}</textarea>
                        <div class="text-xs mt-1 text-gray-500">Mô tả chi tiết về chính sách (tối đa 1000 ký tự)</div>
                        @error('description')
                            <div class="text-xs mt-1 text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" class="form-checkbox" {{ old('is_active', $depositPolicy->is_active) ? 'checked' : '' }} />
                            <label class="text-sm ml-2" for="is_active">Kích hoạt chính sách</label>
                        </div>
                        <div class="text-xs mt-1 text-gray-500">Chính sách sẽ có thể được sử dụng khi được kích hoạt</div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.deposit-policies') }}" 
                        class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                            Hủy
                        </a>
                        <button type="submit" 
                                class="btn cursor-pointer bg-violet-500 hover:bg-violet-600 text-white">
                            <span class="button-text">Cập nhật chính sách</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Policy Information -->
        <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl  p-4">
            <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-3">Thông tin chính sách</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">ID:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $depositPolicy->policy_id }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Ngày tạo:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $depositPolicy->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Cập nhật lần cuối:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $depositPolicy->updated_at->format('d/m/Y H:i') }}</span>
                </div>
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
    <script>
    // Form validation function
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const depositPercentage = document.getElementById('deposit_percentage').value;
        const depositFixedAmount = document.getElementById('deposit_fixed_amount_vnd').value;
        
        // Check required fields
        if (!name) {
            return false;
        }
        
        // Check that at least one deposit method is provided
        if (!depositPercentage && !depositFixedAmount) {
            showNotification('Vui lòng nhập ít nhất một trong hai: phần trăm đặt cọc hoặc số tiền cố định', 'error');
            return false;
        }
        
        return true;
    }

    // Notification function
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'error' ? 'bg-red-500 text-white' : 
            type === 'success' ? 'bg-green-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission
        const policyForm = document.getElementById('policyForm');
        if (policyForm) {
            policyForm.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    showNotification('Vui lòng điền đầy đủ các trường bắt buộc', 'error');
                    return;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const buttonText = submitBtn.querySelector('.button-text');
                const originalText = buttonText.innerHTML;
                
                // Show loading state
                buttonText.innerHTML = `
                    <svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" width="20" height="20">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Đang cập nhật...
                `;
                submitBtn.disabled = true;

                // Reset button after 10 seconds (fallback)
                setTimeout(() => {
                    buttonText.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            });
        }

        // Real-time validation
        const nameField = document.getElementById('name');
        const depositPercentageField = document.getElementById('deposit_percentage');
        const depositFixedAmountField = document.getElementById('deposit_fixed_amount_vnd');

        if (nameField) {
            nameField.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                }
            });
        }

        // Deposit method validation
        function validateDepositMethods() {
            const hasPercentage = depositPercentageField && depositPercentageField.value;
            const hasFixedAmount = depositFixedAmountField && depositFixedAmountField.value;
            
            if (!hasPercentage && !hasFixedAmount) {
                if (depositPercentageField) depositPercentageField.classList.add('border-red-500');
                if (depositFixedAmountField) depositFixedAmountField.classList.add('border-red-500');
            } else {
                if (depositPercentageField) depositPercentageField.classList.remove('border-red-500');
                if (depositFixedAmountField) depositFixedAmountField.classList.remove('border-red-500');
            }
        }

        if (depositPercentageField) {
            depositPercentageField.addEventListener('input', validateDepositMethods);
        }
        
        if (depositFixedAmountField) {
            depositFixedAmountField.addEventListener('input', validateDepositMethods);
        }
    });
</script>

</x-app-layout>
