<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-9xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 md:text-3xl">Danh sách bài viết</h1>
            <a href="{{ route('admin.news.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Thêm bài viết
            </a>
        </div>

        <!-- Search Form -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <form action="{{ route('admin.news.index') }}" method="GET"
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-3">
                <div class="flex-1">
                    <input type="text" name="search_title" placeholder="Tìm kiếm theo tiêu đề..."
                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                           focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                           placeholder-gray-400 dark:placeholder-gray-500 transition duration-150"
                        value="{{ request()->search_title }}">
                </div>

                <div class="relative">
                    <select name="category_id"
                        class="w-48 px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                            appearance-none pr-10">
                        <option value="">Chọn loại bài viết</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request()->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div class="relative">
                    <select name="author_id"
                        class="w-48 px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                            appearance-none pr-10">
                        <option value="">Chọn tác giả</option>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}"
                                {{ request()->author_id == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div class="relative">
                    <select name="status"
                        class="w-48 px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                            appearance-none pr-10">
                        <option value="">Chọn trạng thái</option>
                        <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <input type="date" name="search_date"
                    class="w-48 px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                       focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100"
                    value="{{ request()->search_date }}">

                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md 
                            transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                            dark:focus:ring-offset-gray-800">
                        Tìm
                    </button>
                    <a href="{{ route('admin.news.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md 
                       transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 
                       dark:focus:ring-offset-gray-800">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($news->isEmpty())
            <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg shadow-md" role="alert">
                <span class="block sm:inline">Không tìm thấy bài viết nào phù hợp với tiêu chí.</span>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Ảnh đại diện
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tiêu đề
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Danh mục
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tác giả
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Ngày đăng
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($news as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $item->id }}</td>
                                <td class="px-6 py-4">
                                    <img src="{{ $item->thumbnail ? $item->thumbnail->filepath : asset('storage/no-image.png') }}"
                                        alt="{{ $item->thumbnail ? $item->thumbnail->alt_text : 'Không có ảnh' }}"
                                        class="h-12 w-12 object-cover rounded-lg shadow-sm">
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.news.show', $item->id) }}"
                                        class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 transition duration-150">
                                        {{ $item->meta_title ?? 'Không có tiêu đề' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->category ? $item->category->name : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->author ? $item->author->name : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $item->status
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $item->status ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d/m/Y H:i') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.news.edit', $item->id) }}"
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 dark:bg-blue-900 
                                           dark:text-blue-200 dark:hover:bg-blue-800 font-medium rounded-lg transition duration-150">
                                            Sửa
                                        </a>
                                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-800 
                                                    dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800 font-medium rounded-lg 
                                                    transition duration-150">
                                                Xoá
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">{{ $news->links() }}</div>
    </div>
</x-app-layout>