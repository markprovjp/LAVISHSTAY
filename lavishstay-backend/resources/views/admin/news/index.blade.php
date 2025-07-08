
<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-9xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Danh sách bài viết</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý toàn bộ bài viết tin tức của bạn</p>
            </div>
            <a href="{{ route('admin.news.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md shadow-md">
                + Viết bài mới
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Tiêu đề</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Tác giả</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Lượt xem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Ngày đăng</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($news as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('admin.news.edit', $item->id) }}"
                                    class="hover:underline">{{ $item->title }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->author->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->categories->pluck('name')->join(', ') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($item->status)
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Hiển
                                        thị</span>
                                @else
                                    <span
                                        class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Ẩn</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->views }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->published_at ? $item->published_at->format('d/m/Y H:i') : '—' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.news.edit', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm mr-3">Sửa</a>
                                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Xoá bài này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-6">Chưa có bài
                                viết nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $news->links() }}</div>
    </div>
</x-app-layout>
