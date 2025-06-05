<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">

            <!-- Left: Title -->
            <div class="mb-4 ">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý loại phòng ✨</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add room type button -->
                <button class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white" onclick="showComingSoon('Thêm loại phòng')">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Thêm loại phòng</span>
                </button>
            </div>

        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 px-4 py-3 rounded-md bg-blue-50 border border-blue-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
            <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Danh sách loại phòng <span class="text-gray-400 dark:text-gray-500 font-medium">{{ $roomTypes->total() }}</span></h2>
            </header>
            <div class="p-3">

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <!-- Table header -->
                        <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                            <tr>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Mã loại phòng</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Tên loại phòng</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Mô tả</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-center">Tiện ích</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-center">Số phòng</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-center">Hành động</div>
                                </th>
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($roomTypes as $roomType)
                                <tr>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ $roomType->room_type_id }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ $roomType->name }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                            {{ $roomType->description ?? 'Chưa có mô tả' }}
                                        </div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-sm font-medium">
                                                {{ $roomType->amenities->count() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-sm font-medium">
                                                {{ $roomType->rooms->count() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- View Details -->
                                            <a href="{{ route('admin.room-types.show', $roomType->room_type_id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Xem chi tiết">
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                                                    <path d="M8 12c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zM8 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
                                                    <path d="M8 0C4.7 0 1.8 2.1.3 5.4c-.2.4-.2.8 0 1.2C1.8 9.9 4.7 12 8 12s6.2-2.1 7.7-5.4c.2-.4.2-.8 0-1.2C14.2 2.1 11.3 0 8 0zM8 10c-1.7 0-3.2-.9-4.2-2.3C4.8 6.4 6.3 5.5 8 5.5s3.2.9 4.2 2.2C11.2 9.1 9.7 10 8 10z"/>
                                                </svg>
                                            </a>
                                            <!-- Edit -->
                                            <button onclick="showComingSoon('Sửa loại phòng')" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300" title="Sửa">
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                                                    <path d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z"/>
                                                </svg>
                                            </button>
                                            <!-- Delete -->
                                            <button onclick="showComingSoon('Xóa loại phòng')" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Xóa">
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                                                    <path d="M5 7h6v6c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1V7zM3 3v1h10V3h-2.5l-.5-.5h-4L5.5 3H3zM4 5v8c0 .6.4 1 1 1h6c.6 0 1-.4 1-1V5H4z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="text-lg font-medium">Chưa có loại phòng nào</p>
                                            <p class="text-sm">Hãy thêm loại phòng đầu tiên để bắt đầu quản lý.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($roomTypes->hasPages())
                    <div class="mt-6">
                        {{ $roomTypes->links() }}
                    </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Coming Soon Modal Script -->
    <script>
        function showComingSoon(feature) {
            alert(`Chức năng "${feature}" đang được phát triển và sẽ sớm ra mắt!`);
        }
    </script>

</x-app-layout>
