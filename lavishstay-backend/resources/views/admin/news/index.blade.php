<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Danh sách bài viết</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Quản lý tất cả bài viết trong hệ thống</p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.news.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg shadow-md transition duration-200 ease-in-out cursor-pointer focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6 .4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm bài viết</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div id="notification"
                 class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-green-700">Thành công!</h3>
                    <div class="text-sm text-green-600">{{ session('success') }}</div>
                </div>
                <button onclick="closeNotification(this)"
                        class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="notification"
                 class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">{{ session('error') }}</div>
                </div>
                <button onclick="closeNotification(this)"
                        class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Search Form -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <form action="{{ route('admin.news.index') }}" method="GET"
                  class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-3">
                <div class="flex-1">
                    <input type="text" name="search_title" placeholder="Tìm kiếm theo tiêu đề..."
                           class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                                  focus:ring-2 focus:ring-violet-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                                  placeholder-gray-400 dark:placeholder-gray-500 transition duration-150 cursor-pointer"
                           value="{{ request()->search_title }}">
                </div>

                <div class="relative w-full sm:w-48">
                    <select name="category_id"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                                   focus:ring-2 focus:ring-violet-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                                   appearance-none pr-10 cursor-pointer">
                        <option value="">Chọn loại bài viết</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ request()->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div class="relative w-full sm:w-48">
                    <select name="author_id"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                                   focus:ring-2 focus:ring-violet-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                                   appearance-none pr-10 cursor-pointer">
                        <option value="">Chọn tác giả</option>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}"
                                    {{ request()->author_id == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div class="relative w-full sm:w-48">
                    <select name="status"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                                   focus:ring-2 focus:ring-violet-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                                   appearance-none pr-10 cursor-pointer">
                        <option value="">Chọn trạng thái</option>
                        <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div class="relative w-full sm:w-48">
                    <select name="is_featured"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                                   focus:ring-2 focus:ring-violet-500 focus:border-transparent text-gray-900 dark:text-gray-100 
                                   appearance-none pr-10 cursor-pointer">
                        <option value="">Chọn nổi bật</option>
                        <option value="1" {{ request()->is_featured == '1' ? 'selected' : '' }}>Nổi bật</option>
                        <option value="0" {{ request()->is_featured == '0' ? 'selected' : '' }}>Bình thường</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div class="w-full sm:w-48">
                    <input type="date" name="search_date"
                           class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                                  focus:ring-2 focus:ring-violet-500 focus:border-transparent text-gray-900 dark:text-gray-100 cursor-pointer"
                           value="{{ request()->search_date }}">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg shadow-md 
                                   transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 
                                   dark:focus:ring-offset-gray-800 cursor-pointer">
                        <i class="fas fa-search mr-2"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.news.index') }}"
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg shadow-md 
                              transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 
                              dark:focus:ring-offset-gray-800 cursor-pointer">
                        <i class="fas fa-redo-alt mr-2"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>

        <!-- Table or Empty State -->
        @if ($news->isEmpty())
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                    <span class="text-4xl text-gray-400">📝</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Không tìm thấy bài viết</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Bắt đầu bằng cách tạo bài viết đầu tiên trong hệ thống.</p>
                <a href="{{ route('admin.news.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg shadow-md 
                          transition duration-200 ease-in-out cursor-pointer focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 
                          dark:focus:ring-offset-gray-800">
                    Thêm bài viết mới
                </a>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <table class="w-full table-auto divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-12">
                                ID
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-16">
                                Ảnh
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-48 sm:min-w-64">
                                Tiêu đề
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28 sm:w-32">
                                Danh mục
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28 sm:w-32">
                                Tác giả
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28 sm:w-32">
                                Trạng thái
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28 sm:w-32">
                                Ngày đăng
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28 sm:w-32">
                                Lượt xem
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-40 sm:w-42">
                                Nổi bật
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28 sm:w-32">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($news as $item)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->id }}</td>
                                <td class="px-4 py-3">
                                    <img src="{{ $item->thumbnail ? $item->thumbnail->filepath : asset('storage/no-image.png') }}"
                                         alt="{{ $item->thumbnail ? $item->thumbnail->alt_text : 'Không có ảnh' }}"
                                         class="h-10 w-10 object-cover rounded-lg shadow-sm" loading="lazy">
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <a href=""
                                       class="text-gray-900 dark:text-gray-100 hover:text-violet-600 dark:hover:text-violet-400 transition duration-150 cursor-pointer truncate">
                                        {{ $item->meta_title ?? 'Không có tiêu đề' }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 truncate">
                                    {{ $item->category ? $item->category->name : '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 truncate">
                                    {{ $item->author ? $item->author->name : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                                 {{ $item->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $item->status ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 truncate">
                                    {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d/m/Y H:i') : '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ number_format($item->views ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <select name="is_featured" onchange="updateFeatured({{ $item->id }}, this.value)"
                                            class="w-full px-2 py-1 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg 
                                                   text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent 
                                                   appearance-none cursor-pointer">
                                        <option value="1" {{ $item->is_featured == 1 ? 'selected' : '' }}>Nổi bật</option>
                                        <option value="0" {{ $item->is_featured == 0 ? 'selected' : '' }}>Bình thường</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200 cursor-pointer"
                                                onclick="toggleDropdown({{ $item->id }})"
                                                id="dropdown-button-{{ $item->id }}">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                </path>
                                            </svg>
                                        </button>
                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-menu-{{ $item->id }}"
                                             class="hidden menu-button-action bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1" role="menu">
                                                {{-- <a href="{{ route('admin.news.show', $item->id) }}"
                                                   class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150 cursor-pointer"
                                                   role="menuitem">
                                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Xem chi tiết
                                                </a> --}}
                                                <a href="{{ route('admin.news.edit', $item->id) }}"
                                                   class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150 cursor-pointer"
                                                   role="menuitem">
                                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Sửa bài viết
                                                </a>
                                                <a href="{{ route('admin.news.comments.comments', $item->id) }}"
                                                {{-- <a href="" --}}
                                                   class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150 cursor-pointer"
                                                   role="menuitem">
                                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                    </svg>
                                                    Xem bình luận
                                                </a>
                                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                                <button onclick="deleteNews({{ $item->id }}); closeDropdown({{ $item->id }})"
                                                        class="flex mt-2 items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150 cursor-pointer"
                                                        role="menuitem">
                                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Xóa bài viết
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if ($news->hasPages())
                <div class="mt-6">{{ $news->links() }}</div>
            @endif
        @endif
    </div>

    <script>
        // Animation khi hiển thị thông báo
        document.querySelectorAll('#notification').forEach(notification => {
            notification.classList.add('translate-y-0', 'opacity-100');
            notification.classList.remove('-translate-y-full', 'opacity-0');

            // Tự động ẩn sau 5 giây
            setTimeout(() => {
                closeNotification(notification);
            }, 5000);
        });

        function closeNotification(notification) {
            notification.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }

        function toggleDropdown(newsId) {
            const dropdown = document.getElementById(`dropdown-menu-${newsId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-menu-${newsId}`) {
                    menu.classList.add('hidden');
                }
            });

            dropdown.classList.toggle('hidden');
        }

        function closeDropdown(newsId) {
            const dropdown = document.getElementById(`dropdown-menu-${newsId}`);
            dropdown.classList.add('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');

            let clickedInsideDropdown = false;

            dropdowns.forEach(dropdown => {
                if (dropdown.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            buttons.forEach(button => {
                if (button.contains(event.target)) {
                    clickedInsideDropdown = true;
                }
            });

            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        function deleteNews(newsId) {
            if (confirm('Bạn có chắc chắn muốn xóa bài viết này? Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/news/destroy/${newsId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        let debounceTimer;
        function updateFeatured(newsId, value) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/admin/news/${newsId}/update-featured`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ is_featured: value })
                })
                .then(response => response.json())
                .then(data => {
                    const notification = document.createElement('div');
                    notification.id = 'notification';
                    notification.className = `transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r ${
                        data.success ? 'from-green-50 to-green-100 border-l-4 border-green-500' : 'from-red-50 to-red-100 border-l-4 border-red-500'
                    } shadow-md`;
                    notification.innerHTML = `
                        <div class="flex items-center justify-center w-8 h-8 ${data.success ? 'text-green-500' : 'text-red-500'}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="${data.success ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'}"></path>
                            </svg>
                        </div>
                        <div class="ml-3 mr-8">
                            <h3 class="font-semibold ${data.success ? 'text-green-700' : 'text-red-700'}">${data.success ? 'Thành công!' : 'Lỗi!'}</h3>
                            <div class="text-sm ${data.success ? 'text-green-600' : 'text-red-600'}">${data.message}</div>
                        </div>
                        <button onclick="closeNotification(this)" class="absolute right-2 top-2 ${data.success ? 'text-green-600 hover:text-green-800' : 'text-red-600 hover:text-red-800'}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    `;
                    document.querySelector('.px-4.sm:px-6.lg:px-8').prepend(notification);
                    notification.classList.add('translate-y-0', 'opacity-100');
                    setTimeout(() => closeNotification(notification), 5000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    const notification = document.createElement('div');
                    notification.id = 'notification';
                    notification.className = 'transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md';
                    notification.innerHTML = `
                        <div class="flex items-center justify-center w-8 h-8 text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 mr-8">
                            <h3 class="font-semibold text-red-700">Lỗi!</h3>
                            <div class="text-sm text-red-600">Không thể cập nhật trạng thái nổi bật.</div>
                        </div>
                        <button onclick="closeNotification(this)" class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    `;
                    document.querySelector('.px-4.sm:px-6.lg:px-8').prepend(notification);
                    notification.classList.add('translate-y-0', 'opacity-100');
                    setTimeout(() => closeNotification(notification), 5000);
                });
            }, 300);
        }
    </script>

    <style>
        .button-action {
            position: relative;
        }

        .menu-button-action {
            position: absolute;
            top: 0%;
            right: 130%;
            z-index: 50;
            width: 160px;
        }

        @media (max-width: 640px) {
            .menu-button-action {
                right: 0;
                top: 100%;
                width: 140px;
            }

            .table-auto {
                font-size: 0.75rem;
            }

            .table-auto th,
            .table-auto td {
                padding: 0.5rem;
            }
        }
    </style>
</x-app-layout>