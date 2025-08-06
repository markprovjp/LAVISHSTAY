<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Create New Meal Type</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Add a new Meal Type to your inventory</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.services.meals') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
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

                        <form action="{{ route('admin.services.meals.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="type_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tên Loại <span class="text-red-500">*</span></label>
                                    <input type="text" name="type_name" id="type_name" value="{{ old('type_name') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Nhập tên loại..." required>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mô tả</label>
                                    <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Nhập mô tả...">{{ old('description') }}</textarea>
                                </div>
                                <div>
                                    <label for="base_price_vnd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Giá (VND) <span class="text-red-500">*</span></label>
                                    <input type="number" name="base_price_vnd" id="base_price_vnd" step="0.01" value="{{ old('base_price_vnd') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Nhập giá..." required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trạng thái</label>
                                    <label class="flex mt-3 items-center">
                                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-violet-600">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Hoạt động</span>
                                    </label>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t mt-5 rounded-b-lg">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('admin.services.meals') }}" class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</a>
                                    <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white">Create Meal Type</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>