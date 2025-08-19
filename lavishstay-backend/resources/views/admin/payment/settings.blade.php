<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Cấu hình thanh toán</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý các phương thức thanh toán và cấu hình hệ thống</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Test VietQR button -->
                <button onclick="testVietQRConnection()" id="test-vietqr-btn"
                    class="btn bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 8 8 8.009 8.009 0 0 0-8-8zM8 14a6 6 0 1 1 6-6 6.007 6.007 0 0 1-6 6z"/>
                        <path d="M8 4a4 4 0 0 0-4 4h1a3 3 0 0 1 3-3V4z"/>
                    </svg>
                    <span class="max-xs:sr-only">Test VietQR</span>
                </button>
                
                <!-- Reset to defaults -->
                <button onclick="resetToDefaults()"
                    class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 8 8 8.009 8.009 0 0 0-8-8zM8 14a6 6 0 1 1 6-6 6.007 6.007 0 0 1-6 6z"/>
                        <path d="M8 4a4 4 0 0 0-4 4h1a3 3 0 0 1 3-3V4z"/>
                    </svg>
                    <span class="max-xs:sr-only">Khôi phục mặc định</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Settings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-violet-100 dark:bg-violet-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng cấu hình</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $statistics['total_settings'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Active Settings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Đang hoạt động</p>
                        <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                            {{ $statistics['active_settings'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Enabled Methods -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phương thức bật</p>
                        <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">
                            {{ count($statistics['enabled_methods'] ?? []) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Last Updated -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-400/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cập nhật lần cuối</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $statistics['last_updated'] ? $statistics['last_updated']->format('d/m/Y H:i') : 'Chưa có' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 flex items-center p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500">
                <div class="flex items-center justify-center w-8 h-8 text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold text-green-700 dark:text-green-100">Thành công!</h3>
                    <div class="text-sm text-green-600 dark:text-green-200">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 flex items-center p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500">
                <div class="flex items-center justify-center w-8 h-8 text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold text-red-700 dark:text-red-100">Lỗi!</h3>
                    <div class="text-sm text-red-600 dark:text-red-200">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 flex items-start p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500">
                <div class="flex items-center justify-center w-8 h-8 text-red-500 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold text-red-700 dark:text-red-100">Có lỗi xảy ra:</h3>
                    <ul class="text-sm text-red-600 dark:text-red-200 mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Settings Form -->
        <form method="POST" action="{{ route('admin.payment.setting.update') }}" id="settings-form">
            @csrf
            @method('PUT')

            <!-- VietQR Settings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-400/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">VietQR</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cấu hình thanh toán qua mã QR ngân hàng</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="vietqr_enabled" value="1" 
                                    {{ $settings['vietqr']['enabled'] ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 dark:peer-focus:ring-violet-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-violet-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Bật</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bank ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mã ngân hàng <span class="text-red-500">*</span>
                            </label>
                            <select name="vietqr_bank_id" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <option value="">Chọn ngân hàng</option>
                                @php
                                    $banks = [
                                        'VCB' => 'Vietcombank',
                                        'TCB' => 'Techcombank', 
                                        'MBBank' => 'MB Bank',
                                        'ICB' => 'Vietinbank',
                                        'BIDV' => 'BIDV',
                                        'ACB' => 'ACB',
                                        'TPB' => 'TPBank',
                                        'STB' => 'Sacombank'
                                    ];
                                @endphp
                                @foreach($banks as $code => $name)
                                    <option value="{{ $code }}" {{ $settings['vietqr']['bank_id'] === $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Account Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Số tài khoản <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="vietqr_account_no" value="{{ $settings['vietqr']['account_no'] }}" required
                                pattern="[0-9]+" title="Chỉ được nhập số"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Nhập số tài khoản">
                        </div>

                        <!-- Account Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tên chủ tài khoản <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="vietqr_account_name" value="{{ $settings['vietqr']['account_name'] }}" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Nhập tên chủ tài khoản">
                        </div>

                        <!-- Template -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Template QR <span class="text-red-500">*</span>
                            </label>
                            <select name="vietqr_template" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <option value="print" {{ $settings['vietqr']['template'] === 'print' ? 'selected' : '' }}>Print (Có logo ngân hàng)</option>
                                <option value="compact" {{ $settings['vietqr']['template'] === 'compact' ? 'selected' : '' }}>Compact (Nhỏ gọn)</option>
                                <option value="qr_only" {{ $settings['vietqr']['template'] === 'qr_only' ? 'selected' : '' }}>QR Only (Chỉ mã QR)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Test Result Area -->
                    <div id="vietqr-test-result" class="hidden mt-4"></div>
                </div>
            </div>

            <!-- CPay/Seepay Settings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-400/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">CPay/Seepay</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kiểm tra thanh toán tự động qua API</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="cpay_enabled" value="1" 
                                    {{ $settings['cpay']['enabled'] ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 dark:peer-focus:ring-violet-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-violet-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Bật</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Timeout -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Timeout (giây) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="cpay_timeout" value="{{ $settings['cpay']['timeout'] }}" 
                                min="5" max="300" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="30">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Thời gian chờ khi gọi API (5-300 giây)</p>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="mt-4 flex items-start p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500">
                        <div class="flex items-center justify-center w-8 h-8 text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-blue-700 dark:text-blue-100">Lưu ý</h3>
                            <div class="text-sm text-blue-600 dark:text-blue-200">
                                CPay/Seepay được sử dụng để kiểm tra thanh toán tự động. URL Google Apps Script được cấu hình trong file .env của backend.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VNPay Settings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-400/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">VNPay</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cổng thanh toán VNPay</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="vnpay_enabled" value="1" 
                                    {{ $settings['vnpay']['enabled'] ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 dark:peer-focus:ring-violet-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-violet-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Bật</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Warning Alert -->
                    <div class="flex items-start p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-center w-8 h-8 text-yellow-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-yellow-700 dark:text-yellow-100">Đang phát triển</h3>
                            <div class="text-sm text-yellow-600 dark:text-yellow-200">
                                Tính năng VNPay đang được phát triển. Hiện tại chỉ có thể bật/tắt.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pay at Hotel Settings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-400/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thanh toán tại khách sạn</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Thanh toán trực tiếp khi check-in</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="pay_at_hotel_enabled" value="1" 
                                    {{ $settings['pay_at_hotel']['enabled'] ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 dark:peer-focus:ring-violet-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-violet-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Bật</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Info Alert -->
                    <div class="flex items-start p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500">
                        <div class="flex items-center justify-center w-8 h-8 text-green-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-green-700 dark:text-green-100">Phương thức thanh toán truyền thống</h3>
                            <div class="text-sm text-green-600 dark:text-green-200">
                                Khách hàng sẽ thanh toán trực tiếp tại khách sạn khi check-in.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Settings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 dark:bg-gray-400/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Cài đặt chung</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Cấu hình chung cho hệ thống thanh toán</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Default Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phương thức thanh toán mặc định <span class="text-red-500">*</span>
                            </label>
                            <select name="general_default_payment_method" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                <option value="vietqr" {{ $settings['general']['default_payment_method'] === 'vietqr' ? 'selected' : '' }}>VietQR</option>
                                <option value="vnpay" {{ $settings['general']['default_payment_method'] === 'vnpay' ? 'selected' : '' }}>VNPay</option>
                                <option value="pay_at_hotel" {{ $settings['general']['default_payment_method'] === 'pay_at_hotel' ? 'selected' : '' }}>Thanh toán tại khách sạn</option>
                            </select>
                        </div>

                        <!-- Payment Timeout -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Thời gian timeout thanh toán (giây) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="general_payment_timeout" value="{{ $settings['general']['payment_timeout'] }}" 
                                min="300" max="3600" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="900">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Thời gian chờ thanh toán (5-60 phút)</p>
                        </div>

                        <!-- API Base URL -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                API Base URL <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="general_api_base_url" value="{{ $settings['general']['api_base_url'] }}" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                placeholder="http://localhost:8888/api">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="window.location.reload()"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-800 dark:text-gray-300">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 8 8 8.009 8.009 0 0 0-8-8zM8 14a6 6 0 1 1 6-6 6.007 6.007 0 0 1-6 6z"/>
                        <path d="M8 4a4 4 0 0 0-4 4h1a3 3 0 0 1 3-3V4z"/>
                    </svg>
                    <span>Hủy</span>
                </button>
                
                <button type="submit" id="save-btn"
                    class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15.8 2.1l-2-2a.5.5 0 0 0-.7 0L1.4 11.8a.5.5 0 0 0-.1.2L.1 15.5a.5.5 0 0 0 .6.6l3.5-1.2a.5.5 0 0 0 .2-.1L15.8 2.8a.5.5 0 0 0 0-.7z"/>
                    </svg>
                    <span>Lưu cấu hình</span>
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        // Add CSRF token to meta tag if not exists
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.getElementsByTagName('head')[0].appendChild(meta);
        }

        // Test VietQR Connection
        async function testVietQRConnection() {
            const btn = document.getElementById('test-vietqr-btn');
            const resultDiv = document.getElementById('vietqr-test-result');
            
            // Get current form values
            const bankId = document.querySelector('select[name="vietqr_bank_id"]').value;
            const accountNo = document.querySelector('input[name="vietqr_account_no"]').value;
            const accountName = document.querySelector('input[name="vietqr_account_name"]').value;
            
            if (!bankId || !accountNo || !accountName) {
                showNotification('error', 'Vui lòng điền đầy đủ thông tin VietQR trước khi test');
                return;
            }
            
            // Show loading state
            btn.disabled = true;
            const originalContent = btn.innerHTML;
            btn.innerHTML = `
                <svg class="animate-spin fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                    <circle class="opacity-25" cx="8" cy="8" r="7" stroke="currentColor" stroke-width="2" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M8 0a8 8 0 0 1 8 8h-2a6 6 0 0 0-6-6V0z"></path>
                </svg>
                <span class="max-xs:sr-only">Đang test...</span>
            `;
            
            try {
                const response = await fetch('{{ route("admin.payment.setting.test-vietqr") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        bank_id: bankId,
                        account_no: accountNo,
                        account_name: accountName
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    resultDiv.innerHTML = `
                        <div class="flex items-start p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500">
                            <div class="flex items-center justify-center w-8 h-8 text-green-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-green-700 dark:text-green-100">Test thành công!</h3>
                                <div class="text-sm text-green-600 dark:text-green-200">${result.message}</div>
                                <a href="${result.test_url}" target="_blank">${result.test_url ? `<div class="text-xs text-green-500 dark:text-green-300 mt-2 font-mono break-all">${result.test_url}</div>` : ''}</a>
                            </div>
                        </div>
                    `;
                    showNotification('success', 'Test VietQR thành công!');
                } else {
                    resultDiv.innerHTML = `
                        <div class="flex items-start p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500">
                            <div class="flex items-center justify-center w-8 h-8 text-red-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-red-700 dark:text-red-100">Test thất bại!</h3>
                                <div class="text-sm text-red-600 dark:text-red-200">${result.message}</div>
                            </div>
                        </div>
                    `;
                    showNotification('error', 'Test VietQR thất bại: ' + result.message);
                }
                
                resultDiv.classList.remove('hidden');
                
            } catch (error) {
                console.error('Test error:', error);
                resultDiv.innerHTML = `
                    <div class="flex items-start p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500">
                        <div class="flex items-center justify-center w-8 h-8 text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-red-700 dark:text-red-100">Lỗi kết nối!</h3>
                            <div class="text-sm text-red-600 dark:text-red-200">Không thể kết nối đến server. Vui lòng thử lại.</div>
                        </div>
                    </div>
                `;
                resultDiv.classList.remove('hidden');
                showNotification('error', 'Có lỗi xảy ra khi test VietQR');
            } finally {
                // Restore button
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        }
        
        // Reset to defaults
        function resetToDefaults() {
            if (confirm('Bạn có chắc chắn muốn khôi phục tất cả cấu hình về mặc định? Thao tác này không thể hoàn tác.')) {
                window.location.href = '{{ route("admin.payment.setting.reset") }}';
            }
        }
        
        // Form submission with loading state
        document.getElementById('settings-form').addEventListener('submit', function(e) {
            const saveBtn = document.getElementById('save-btn');
            const originalContent = saveBtn.innerHTML;
            
            saveBtn.disabled = true;
            saveBtn.innerHTML = `
                <svg class="animate-spin fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                    <circle class="opacity-25" cx="8" cy="8" r="7" stroke="currentColor" stroke-width="2" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M8 0a8 8 0 0 1 8 8h-2a6 6 0 0 0-6-6V0z"></path>
                </svg>
                <span>Đang lưu...</span>
            `;
            
            // Re-enable button after 10 seconds as fallback
            setTimeout(() => {
                if (saveBtn.disabled) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalContent;
                }
            }, 10000);
        });
        
        // Notification system
        function showNotification(type, message) {
            const notification = document.createElement('div');
            const isError = type === 'error';
            
            notification.className = `fixed top-4 right-4 z-50 transform transition-all duration-300 ease-out flex items-start p-4 rounded-xl shadow-2xl max-w-sm ${
                isError 
                    ? 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500' 
                    : 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center justify-center w-8 h-8 ${isError ? 'text-red-500' : 'text-green-500'}">
                    ${isError ? `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    ` : `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    `}
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="font-semibold ${isError ? 'text-red-700 dark:text-red-100' : 'text-green-700 dark:text-green-100'}">${isError ? 'Lỗi!' : 'Thành công!'}</h3>
                    <div class="text-sm ${isError ? 'text-red-600 dark:text-red-200' : 'text-green-600 dark:text-green-200'} mt-1">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 ${isError ? 'text-red-400 hover:text-red-600' : 'text-green-400 hover:text-green-600'} transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
        
        // Auto-hide existing alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-yellow-50');
            alerts.forEach(alert => {
                if (alert.classList.contains('mb-4')) { // Only session alerts
                    alert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }
            });
        }, 5000);

        // Form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('settings-form');
            const inputs = form.querySelectorAll('input[required], select[required]');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('border-red-500')) {
                        validateField(this);
                    }
                });
            });
            
            function validateField(field) {
                const isValid = field.checkValidity();
                
                if (isValid) {
                    field.classList.remove('border-red-500', 'dark:border-red-400');
                    field.classList.add('border-green-500', 'dark:border-green-400');
                } else {
                    field.classList.remove('border-green-500', 'dark:border-green-400');
                    field.classList.add('border-red-500', 'dark:border-red-400');
                }
            }
            
            // Reset field colors on focus
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.remove('border-red-500', 'dark:border-red-400', 'border-green-500', 'dark:border-green-400');
                });
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+S or Cmd+S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.getElementById('settings-form').submit();
            }
            
            // Escape to cancel/reload
            if (e.key === 'Escape') {
                if (confirm('Bạn có muốn hủy các thay đổi và tải lại trang?')) {
                    window.location.reload();
                }
            }
        });

        // Warn before leaving if form has changes
        let formChanged = false;
        const formElements = document.querySelectorAll('#settings-form input, #settings-form select');
        
        formElements.forEach(element => {
            element.addEventListener('change', function() {
                formChanged = true;
            });
        });
        
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'Bạn có thay đổi chưa được lưu. Bạn có chắc chắn muốn rời khỏi trang?';
            }
        });
        
        // Reset formChanged flag when form is submitted
        document.getElementById('settings-form').addEventListener('submit', function() {
            formChanged = false;
        });

        console.log('Payment Settings Admin Panel loaded successfully');
    </script>
</x-app-layout>