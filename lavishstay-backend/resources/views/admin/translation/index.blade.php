<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" id="page-container">
        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý bản dịch</h1>
            </div>
            <!-- Right: Actions -->
            <div class="mb-4 sm:mb-0">
                <a href="{{ route('admin.translation.manage-tables') }}" class="btn bg-violet-500 hover:bg-violet-600 text-white">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    Quản lý bảng
                </a>
            </div>
            <!-- Right: Pagination -->
            @if ($totalTables > $perPage)
                <div class="flex justify-end items-center space-x-2" id="pagination">
                    @php
                        $totalPages = ceil($totalTables / $perPage);
                        $currentPage = $page;
                    @endphp
                    {{-- Previous --}}
                    <a href="{{ $currentPage > 1 ? route('admin.translation.index', ['page' => $currentPage - 1]) : '#' }}"
                       class="px-4 py-2 rounded-lg transition duration-200 pagination-link
                            {{ $currentPage > 1 ? 'bg-gray-200 text-gray-800 hover:bg-gray-300' : 'bg-gray-100 text-gray-400 pointer-events-none opacity-50' }}"
                       data-page="{{ $currentPage > 1 ? $currentPage - 1 : '' }}">
                        Previous
                    </a>
                    {{-- Page Numbers --}}
                    @for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                        <a href="{{ route('admin.translation.index', ['page' => $i]) }}"
                           class="px-4 py-2 rounded-lg transition duration-200 pagination-link
                                {{ $i == $currentPage ? 'bg-blue-100 text-blue-800 font-semibold border border-blue-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                           data-page="{{ $i }}">
                            {{ $i }}
                        </a>
                    @endfor
                    {{-- Next --}}
                    <a href="{{ $currentPage < $totalPages ? route('admin.translation.index', ['page' => $currentPage + 1]) : '#' }}"
                       class="px-4 py-2 rounded-lg transition duration-200 pagination-link
                            {{ $currentPage < $totalPages ? 'bg-gray-200 text-gray-800 hover:bg-gray-300' : 'bg-gray-100 text-gray-400 pointer-events-none opacity-50' }}"
                       data-page="{{ $currentPage < $totalPages ? $currentPage + 1 : '' }}">
                        Next
                    </a>
                    <span class="text-sm text-gray-600 dark:text-gray-400 ml-4">
                        Page {{ $currentPage }} of {{ $totalPages }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-12 gap-6" id="cards-container">
            @forelse ($tables as $table)
                @if ($table && is_string($table) && !empty($table))
                    <?php
                        $tableDataItem = [
                            'total' => $translations->where('table_name', $table)->count(),
                            'display_name' => $displayNames[$table] ?? $table,
                        ];
                    ?>
                    <div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-xs rounded-xl">
                        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ $tableDataItem['display_name'] }}</h2>
                        </header>
                        <div class="px-5 py-4 grow flex flex-col justify-center">
                            <div><span>Tổng số bản dịch:</span> <span class="text-gray-800 dark:text-gray-100 font-semibold">{{ $tableDataItem['total'] }}</span></div>
                            <button class="btn mt-4 cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                                <a href="{{ route('admin.translation.show', ['table' => $table]) }}" class="flex items-center justify-center w-full">
                                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
                                    </svg>
                                    <span class="max-xs:sr-only">Xem chi tiết</span>
                                </a>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-500 dark:text-gray-400 py-6 col-span-12">
                        Giá trị bảng không hợp lệ: {{ $table ?? 'null' }}
                    </div>
                @endif
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-6 col-span-12">
                    Không có bảng nào được tìm thấy.
                </div>
            @endforelse
        </div>

        <!-- Recent Translations -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 mt-6">Danh sách các bản dịch gần đây</h2>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Bảng</th>
                            <th scope="col" class="px-6 py-3">Cột</th>
                            <th scope="col" class="px-6 py-3">Giá trị</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTranslations as $translation)
                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">{{ $translation->table_name }}</td>
                                <td class="px-6 py-4">{{ $translation->column_name }}</td>
                                <td class="px-6 py-4">{{ $translation->value }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center">Không có bản dịch gần đây.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>  

        

    <style>
        #cards-container {
            transition: transform 0.5s ease-in-out;
            position: relative;
        }
        .pagination-link {
            cursor: pointer;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('.pagination-link');
            const container = document.getElementById('cards-container');
            const pagination = document.getElementById('pagination');
            let currentPage = {{ $page }};
            let scrollPosition = 0;

            if (!container || !pagination) {
                console.error('Container or pagination element not found!');
                return;
            }

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    scrollPosition = window.scrollY;
                    const newPage = parseInt(this.getAttribute('data-page'));
                    if (newPage !== currentPage) {
                        const direction = newPage > currentPage ? 'left' : 'right';
                        container.style.transform = direction === 'left' ? 'translateX(-100%)' : 'translateX(100%)';

                        fetch(this.href + '?ajax=1', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data);
                            if (data.cards && data.pagination) {
                                container.style.transition = 'none';
                                container.innerHTML = data.cards;
                                pagination.innerHTML = data.pagination;
                                container.style.transition = 'transform 0.5s ease-in-out';
                                container.style.transform = 'translateX(0)';
                                currentPage = newPage;
                                window.scrollTo(0, scrollPosition);
                            } else {
                                console.error('Invalid data structure:', data);
                                window.location.href = this.href;
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            window.location.href = this.href;
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>