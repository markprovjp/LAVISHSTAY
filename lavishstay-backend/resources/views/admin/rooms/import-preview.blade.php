<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.rooms') }}"
                               class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Tổng quan phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                     width="16" height="16">
                                    <path fill-rule="evenodd"
                                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">Preview Nhập Excel</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                    Preview Dữ Liệu Nhập Excel
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Xem trước dữ liệu từ file Excel cho loại phòng {{ session('import_details.room_type_id') }}
                </p>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Không cần action ở đây -->
            </div>
        </div>

        @if (session('success'))
            <div id="notification" class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 shadow-md">
                <div class="flex items-center justify-center w-8 h-8 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-green-700">Thành công!</h3>
                    <div class="text-sm text-green-600">{{ session('success') }}</div>
                </div>
                <button onclick="closeNotification()" class="absolute right-2 top-2 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div id="notification-error" class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md relative">
                <div class="flex items-center justify-center w-8 h-8 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-3 mr-8">
                    <h3 class="font-semibold text-red-700">Lỗi!</h3>
                    <div class="text-sm text-red-600">{{ session('error') }}</div>
                </div>
                <button onclick="closeNotificationError()" class="absolute right-2 top-2 text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if (!empty(session('import_details.duplicate_rows')))
    <div id="notification-error" class="transform transition-all duration-300 ease-out mb-4 flex items-center p-4 rounded-lg bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 shadow-md relative">
        <div class="flex items-center justify-center w-8 h-8 text-red-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div class="ml-3 mr-8">
            <h3 class="font-semibold text-red-700">Lỗi!</h3>
            <div class="text-sm text-red-600">Tên phòng đã tồn tại ở các hàng: {{ implode('; ', session('import_details.duplicate_rows')) }}. Vui lòng thay đổi tên trước khi xác nhận.</div>
        </div>
        <button onclick="closeNotificationError()" class="absolute right-2 top-2 text-red-600 hover:text-red-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

        <!-- Preview Content -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Dữ Liệu Preview</h2>
            </div>
            @if (session('import_details'))
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Loại phòng:</strong></p>
                            <p class="text-md font-medium text-gray-900 dark:text-gray-100">{{ session('import_details.room_type_id') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Số phòng còn lại:</strong></p>
                            <p class="text-md font-medium text-gray-900 dark:text-gray-100">{{ session('import_details.remaining_rooms') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Số phòng sẽ thêm:</strong></p>
                            <p class="text-md font-medium text-gray-900 dark:text-gray-100">{{ session('import_details.count_to_add') }}</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 border-b border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    @php
                                        $columns = ['STT'];
                                        if (!empty(session('import_details.data_rows'))) {
                                            $firstRow = session('import_details.data_rows')[0];
                                            for ($i = 0; $i < count($firstRow); $i++) {
                                                $columns[] = ['name', 'image', 'floor_id', 'bed_type_fixed', 'status', 'description', 'last_cleaned'][$i] ?? 'column_' . ($i + 1);
                                            }
                                        }
                                    @endphp
                                    @foreach ($columns as $column)
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $column) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach (session('import_details.data_rows') as $index => $row)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $index + 2 }}</td>
                                        @foreach ($row as $cell)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $cell ?? 'N/A' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 text-right">
                        <form action="{{ route('rooms.import.confirm', ['room_type_id' => session('import_details.room_type_id')]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn bg-violet-500 hover:bg-violet-600 text-white px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition-all">
                                Xác Nhận Thêm
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">Không có dữ liệu để hiển thị.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Animation khi hiển thị
        ['notification', 'notification-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('translate-y-0', 'opacity-100');
                el.classList.remove('-translate-y-full', 'opacity-0');

                setTimeout(() => {
                    el.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => el.remove(), 300);
                }, 5000);
            }
        });

        function closeNotification() {
            const el = document.getElementById('notification');
            if (el) {
                el.classList.add('opacity-0', 'scale-95');
                setTimeout(() => el.remove(), 300);
            }
        }

        function closeNotificationError() {
            const el = document.getElementById('notification-error');
            if (el) {
                el.classList.add('opacity-0', 'scale-95');
                setTimeout(() => el.remove(), 300);
            }
        }
    </script>
</x-app-layout>