<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Quản lý danh mục bài viết</h1>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="{ showCreate: false, editModal: false, editData: null }">
        <!-- Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-gray-100">Danh sách danh mục</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Thêm, chỉnh sửa hoặc xoá danh mục bài viết.</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button @click="showCreate = true"
                    class="btn bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                    <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1a1 1 0 10-2 0v6H1a1 1 0 100 2h6v6a1 1 0 102 0V9h6a1 1 0 100-2z" />
                    </svg>
                    <span class="ml-2">Thêm danh mục</span>
                </button>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg ">
            <div class="p-6">
                @if ($categories->count())
                    <table class="w-full table-auto text-sm">
                        <thead>
                            <tr
                                class="bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Tên chuyên mục</th>
                                <th class="px-6 py-3">Slug</th>
                                <th class="px-6 py-3">Mô tả</th>
                                <th class="px-6 py-3">Ngày tạo</th>
                                <th class="px-6 py-3 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4">{{ $category->id }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $category->slug }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                        {{ $category->description ?? '—' }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                        {{ $category->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="inline-flex justify-center w-8 h-8 text-gray-500 hover:text-gray-700">
                                                <!-- Heroicon Dots Vertical -->
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zM10 8.5a1.5 1.5 0 100 3 1.5 1.5 0 000-3zM10 14a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                                <div class="py-1">
                                                    <button
                                                        @click="editModal = true; editData = {{ $category->toJson() }}; open = false"
                                                        class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <!-- Heroicon Pencil -->
                                                        <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none"
                                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path
                                                                d="M11 5h2M12 4v2m7.071 2.929a2 2 0 010 2.828l-10 10a2 2 0 01-.878.515l-4 1a1 1 0 01-1.213-1.213l1-4a2 2 0 01.515-.878l10-10a2 2 0 012.828 0z">
                                                            </path>
                                                        </svg>
                                                        Sửa
                                                    </button>
                                                    <form method="POST"
                                                        action="{{ route('admin.news.categories.destroy', $category) }}"
                                                        onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                            <!-- Heroicon Trash -->
                                                            <svg class="w-4 h-4 mr-2 text-red-600" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-4h4a1 1 0 011 1v1H8V4a1 1 0 011-1z" />
                                                            </svg>
                                                            Xoá
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">Chưa có danh mục nào.</p>
                @endif
            </div>
        </div>

        <!-- Modal: Tạo mới -->
        <div x-show="showCreate" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            style="display: none;">
            <div @click.away="showCreate = false"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Thêm danh mục mới</h3>
                <form method="POST" action="{{ route('admin.news.categories.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tên chuyên mục</label>
                        <input type="text" name="name"
                            class="form-input w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Mô tả</label>
                        <textarea name="description" rows="2" class="form-textarea w-full rounded dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="showCreate = false"
                            class="mr-2 px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded">Huỷ</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Sửa -->
        <div x-show="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            style="display: none;">
            <div @click.away="editModal = false"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Chỉnh sửa danh mục</h3>
                <form :action="`{{ route('admin.news.categories.update', '__ID__') }}`.replace('__ID__', editData.id)"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tên chuyên mục</label>
                        <input type="text" name="name" x-model="editData.name"
                            class="form-input w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Mô tả</label>
                        <textarea name="description" rows="2" x-model="editData.description"
                            class="form-textarea w-full rounded dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="editModal = false"
                            class="mr-2 px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded">Huỷ</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>