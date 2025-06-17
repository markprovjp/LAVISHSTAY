<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Edit Currency</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Edit Currency</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.multinational.currencies') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <i class="fa-solid fa-backward"></i><span class="ml-2">Back to List</span>
                </a>
            </div>
        </div>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.multinational.currencies.update', $currency->currency_code) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="currency_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mã Tiền Tệ <span class="text-red-500">*</span></label>
                                    <input type="text" name="currency_code" id="currency_code" value="{{ old('currency_code', $currency->currency_code) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Nhập mã tiền tệ (ví dụ: USD)" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tên Tiền Tệ <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $currency->name) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Nhập tên tiền tệ" required>
                                </div>
                                <div>
                                    <label for="exchange_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tỷ Giá (VND) <span class="text-red-500">*</span></label>
                                    <input type="number" name="exchange_rate" id="exchange_rate" step="0.0001" value="{{ old('exchange_rate', $currency->exchange_rate) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Nhập tỷ giá" required>
                                </div>
                                <div>
                                    <label for="symbol" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ký Hiệu <span class="text-red-500">*</span></label>
                                    <input type="text" name="symbol" id="symbol" value="{{ old('symbol', $currency->symbol) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Nhập ký hiệu (ví dụ: $)" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Định Dạng <span class="text-red-500">*</span></label>
                                    <input type="text" name="format" id="format" value="{{ old('format', $currency->format) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Nhập định dạng (ví dụ: ${amount})" required>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t mt-5 rounded-b-lg">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('admin.multinational.currencies') }}" class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</a>
                                    <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white">Update Currency</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>