<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Chỉnh sửa Bản dịch</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Cập nhật giá trị cho bản dịch ID: {{ $translation->translation_id }}</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.translation.show', $translation->table_name) }}">
                    <button class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M7 16l-4-4h3V4h2v8h3z"/>
                        </svg>
                        <span class="max-xs:sr-only">Quay lại</span>
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

                        <form action="{{ route('admin.translation.update', $translation->translation_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="table_name" value="{{ $translation->table_name }}">
                            <div class="mb-4">
                                <label class="block text-sm font-medium">Cột</label>
                                <input type="text" name="column_name" value="{{ $translation->column_name }}" class="w-full border rounded px-3 py-2" readonly>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium">ID Bản ghi</label>
                                <input type="text" name="record_id" value="{{ $translation->record_id }}" class="w-full border rounded px-3 py-2" readonly>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium">Ngôn ngữ</label>
                                <select name="language_code" class="w-full border rounded px-3 py-2" readonly>
                                    @foreach ($languages as $lang)
                                        <option value="{{ $lang->language_code }}" {{ $translation->language_code === $lang->language_code ? 'selected' : '' }}>{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium">Giá trị</label>
                                <input type="text" name="value" value="{{ old('value', $translation->value) }}" class="w-full border rounded px-3 py-2">
                            </div>
                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button" onclick="window.history.back()" class="btn bg-yellow-400 text-gray-800 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-300" style="min-width: 80px;">Hủy</button>
                                <button type="submit" class="btn bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300" style="min-width: 80px;">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>