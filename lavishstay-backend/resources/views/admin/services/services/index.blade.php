<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Services Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage all Services and their configurations</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.services.services.create') }}">
                    <button class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                            <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
                        </svg>
                        <span class="max-xs:sr-only">Add Service</span>
                    </button>
                </a>
            </div>
        </div>

        <div class="py-12">
            <div class="">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tên dịch vụ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mô tả</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Giá (VND)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Đơn vị</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trạng thái</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Loại phòng sử dụng</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ngày tạo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($services as $service)
                                        <tr class="">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $service->service_id }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="font-medium">{{ $service->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="max-w-xs">
                                                    {{ Str::limit($service->description, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <div class="font-medium text-green-600">
                                                    {{ number_format($service->price_vnd, 0, ',', '.') }} ₫
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $service->unit ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button onclick="toggleStatus({{ $service->service_id }})" 
                                                        class="status-toggle px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $service->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                @if($service->roomTypes && $service->roomTypes->count() > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($service->roomTypes->take(2) as $roomType)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ $roomType->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($service->roomTypes->count() > 2)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                +{{ $service->roomTypes->count() - 2 }} khác
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-xs">Chưa sử dụng</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $service->created_at ? $service->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                                <div class="relative inline-block text-left">
                                                    <button type="button" 
                                                            class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200" 
                                                            onclick="toggleDropdown({{ $service->service_id }})" 
                                                            id="dropdown-button-{{ $service->service_id }}">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                        </svg>
                                                    </button>
                                                    <div id="dropdown-menu-{{ $service->service_id }}" 
                                                         class="hidden menu-button-action absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1 z-500" role="menu">
                                                            <a href="{{ route('admin.services.services.show', $service->service_id) }}" 
                                                               class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150" 
                                                               role="menuitem">
                                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                View Service
                                                            </a>
                                                            <a href="{{ route('admin.services.services.edit', $service->service_id) }}" 
                                                               class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150" 
                                                               role="menuitem">
                                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                                Edit Service
                                                            </a>
                                                            <button onclick="deleteService({{ $service->service_id }}); closeDropdown({{ $service->service_id }})" 
                                                                    class="flex mt-2 items-center w-full px-4 py-2 cursor-pointer text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150" 
                                                                    role="menuitem">
                                                                <svg style="width: 20px; align-items: center" class="mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                                Delete Service
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    <p class="text-lg font-medium">Chưa có dịch vụ nào</p>
                                                    <p class="text-sm text-gray-400 mt-1">Bắt đầu bằng cách tạo dịch vụ đầu tiên của bạn</p>
                                                    <a href="{{ route('admin.services.services.create') }}" 
                                                       class="mt-4 inline-flex items-center px-4 py-2 bg-violet-600 text-white text-sm font-medium rounded-md hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                        Tạo dịch vụ mới
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($services->hasPages())
                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex-1 flex justify-between sm:hidden">
                                    @if ($services->onFirstPage())
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                            « Previous
                                        </span>
                                    @else
                                        <a href="{{ $services->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                            « Previous
                                        </a>
                                    @endif

                                    @if ($services->hasMorePages())
                                        <a href="{{ $services->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                            Next »
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                            Next »
                                        </span>
                                    @endif
                                </div>

                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Showing
                                            <span class="font-medium">{{ $services->firstItem() }}</span>
                                            to
                                            <span class="font-medium">{{ $services->lastItem() }}</span>
                                            of
                                            <span class="font-medium">{{ $services->total() }}</span>
                                            results
                                        </p>
                                    </div>
                                    <div>
                                        {{ $services->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown(serviceId) {
            const dropdown = document.getElementById(`dropdown-menu-${serviceId}`);
            dropdown.classList.toggle('hidden');
        }

        function closeDropdown(serviceId) {
            const dropdown = document.getElementById(`dropdown-menu-${serviceId}`);
            dropdown.classList.add('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-button-"]');
            let clickedInsideDropdown = false;

            dropdowns.forEach(dropdown => {
                if (dropdown.contains(event.target)) clickedInsideDropdown = true;
            });

            buttons.forEach(button => {
                if (button.contains(event.target)) clickedInsideDropdown = true;
            });

            if (!clickedInsideDropdown) {
                dropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
            }
        });

        function toggleStatus(serviceId) {
            if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái dịch vụ này?')) {
                fetch(`/admin/services/services/toggle-status/${serviceId}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi cập nhật trạng thái!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                });
            }
        }

        function deleteService(serviceId) {
            if (confirm('Bạn có chắc chắn muốn xóa dịch vụ này? Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/services/services/destroy/${serviceId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

       

     

       
    </script>

  
</x-app-layout>

        

