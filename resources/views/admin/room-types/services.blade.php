<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.room-types') }}"
                                class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Quản lý loại phòng
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.room-types.show', $roomType) }}"
                                    class="ml-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">{{ $roomType->name }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">Quản lý dịch vụ</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý dịch vụ - {{ $roomType->name }}</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid cursor-pointer grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button onclick="openAddServiceModal()"
                    class="btn items-center cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current items-center shrink-0 w-4 h-4 me-2" viewBox="0 0 12 12" width="24" height="24">
                        <path d="M7 4V2a1 1 0 012 0v2h2a1 1 0 010 2H9v2a1 1 0 01-2 0V6H5a1 1 0 010-2h2z"/>
                    </svg>
                    <span class="max-xs:sr-only">Thêm dịch vụ</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Filters and Search -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" id="serviceSearch" placeholder="Tìm kiếm dịch vụ..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                <div class="absolute inset-y-3 top-0 mt-3 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Category Filters -->
                        <div class="flex flex-wrap gap-2">
                            <button onclick="filterByCategory('all')" 
                                class="filter-btn px-3 py-2 text-sm rounded-lg transition-colors bg-blue-600 text-white">
                                Tất cả
                            </button>
                            @php
                                $categories = $roomType->services->pluck('unit')->unique()->filter();
                            @endphp
                            @foreach($categories as $unit)
                                <button onclick="filterByCategory('{{ $unit }}')" 
                                    class="filter-btn px-3 py-2 text-sm rounded-lg transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                    {{ $unit }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Current Services -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <div class="flex justify-between items-center">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                                Dịch vụ hiện tại
                                <span class="text-gray-400 dark:text-gray-500 font-medium">({{ $roomType->services->count() }})</span>
                            </h2>
                            @if($roomType->services->count() > 0)
                                <div class="flex items-center space-x-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="selectAll" onchange="selectAll()" class="mr-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Chọn tất cả</span>
                                    </label>
                                    <div id="bulkActions" class="hidden space-x-2">
                                        <button onclick="activateSelected()" class="btn-sm bg-green-500 hover:bg-green-600 text-white">
                                            Kích hoạt
                                        </button>
                                        {{-- <button onclick="deactivateSelected()" class="btn-sm bg-red-500 hover:bg-red-600 text-white">
                                            Ngưng hoạt động
                                        </button> --}}
                                        <button onclick="removeSelected()" class="btn-sm bg-red-500 hover:bg-red-600 text-white">
                                            Xóa đã chọn
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @if ($roomType->services->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach ($roomType->services as $service)
                                    <div class="service-item relative p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-md transition-all duration-200 {{ $service->is_active ? 'ring-2 ring-green-400 bg-green-50 dark:bg-green-900/20' : '' }}"
                                        data-service-id="{{ $service->service_id }}"
                                        data-active="{{ $service->is_active ? 'true' : 'false' }}"
                                        data-unit="{{ $service->unit }}">
                                        <div class="flex justify-between items-start gap-6">
                                            <input type="checkbox" class="service-checkbox" value="{{ $service->service_id }}" onchange="toggleServiceSelection({{ $service->service_id }})">
                                            <span class="bg-{{ $service->is_active ? 'green' : 'red' }}-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $service->is_active ? 'Hoạt động' : 'Ngưng' }}
                                            </span>
                                        </div>
                                        <div class="mt-2 flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                @if ($service->unit)
                                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                        {{-- <span class="text-green-600 dark:text-green-400 text-lg">{{ substr($service->unit, 0, 1) }}</span> --}}
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $service->name }}</h3>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $service->price_with_unit }}</p>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $service->unit }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 flex flex-col space-y-1">
                                                <button onclick="toggleStatus({{ $service->service_id }})" class="p-1 text-{{ $service->is_active ? 'red' : 'green' }}-600 hover:text-{{ $service->is_active ? 'red' : 'green' }}-700 hover:bg-{{ $service->is_active ? 'red' : 'green' }}-50 rounded transition-colors">
                                                    <svg class="w-4 h-4" fill="{{ $service->is_active ? 'none' : 'currentColor' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                                <button onclick="removeService({{ $service->service_id }})" class="p-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chưa có dịch vụ nào</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">Thêm dịch vụ để khách hàng có thể xem thông tin về loại phòng này</p>
                                <button onclick="openAddServiceModal()"
                                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Thêm dịch vụ đầu tiên
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Statistics -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Thống kê</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tổng số dịch vụ</span>
                            <span class="text-lg font-semibold text-green-600 dark:text-green-400" data-stat="total">{{ $roomType->services->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Dịch vụ hoạt động</span>
                            <span class="text-lg font-semibold text-green-600 dark:text-green-400" data-stat="active">{{ $roomType->services->where('is_active', 1)->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Có thể thêm</span>
                            <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ $availableServices->flatten()->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if($roomType->services->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Hành động nhanh</h3>
                    <div class="space-y-3">
                        <button onclick="activateAll()" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 inline-block mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20" width="24px" height="24px">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.922-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Kích hoạt tất cả
                        </button>
                        <button onclick="deactivateAll()" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 inline-block mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24px" height="24px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Ngưng hoạt động tất cả
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Add Service Modal -->
    <div id="addServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl mt-10 w-full max-w-7xl max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thêm dịch vụ</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Chọn các dịch vụ muốn thêm vào loại phòng</p>
                    </div>
                    <button onclick="closeAddServiceModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto p-6 modal-body">
                    @if($availableServices->flatten()->count() > 0)
                        @foreach($availableServices as $unit => $services)
                            <div class="mb-8">
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 px-3 py-1 rounded-full text-sm mr-3">{{ $unit }}</span>
                                    <span class="text-sm text-gray-500">({{ $services->filter()->count() }} dịch vụ)</span>
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($services as $service)
                                        @if(is_object($service) && $service instanceof \App\Models\Service)
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all duration-200 service-item">
                                                <div class="flex items-start space-x-3">
                                                    <!-- Selection checkbox -->
                                                    <div class="flex-shrink-0 mt-1">
                                                        <input type="checkbox"
                                                            class="modal-service-checkbox"
                                                            value="{{ $service->service_id }}"
                                                            data-service-id="{{ $service->service_id }}"
                                                            onchange="toggleModalSelection({{ $service->service_id }})">
                                                    </div>

                                                    <!-- Icon -->
                                                    <div class="flex-shrink-0">
                                                        @if ($service->unit)
                                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                                <span class="text-green-600 dark:text-green-400 text-lg">{{ substr($service->unit, 0, 1) }}</span>
                                                            </div>
                                                        @else
                                                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Content -->
                                                    <div class="flex-1 min-w-0">
                                                        <h5 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $service->name }}</h5>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $service->price_with_unit }}</p>
                                                        <label class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                                            <input type="checkbox" 
                                                                class="active-checkbox mr-2" 
                                                                data-service-id="{{ $service->service_id }}">
                                                            <span>Kích hoạt</span>
                                                        </label>
                                                    </div>

                                                    <!-- Quick add button -->
                                                    <div class="flex-shrink-0">
                                                        <button onclick="quickAddService({{ $service->service_id }})"
                                                                class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-colors"
                                                                title="Thêm nhanh">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @php
                                                \Log::warning('Invalid service data found in availableServices: ', ['service' => $service, 'unit' => $unit]);
                                            @endphp
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Không có dịch vụ nào để thêm</h3>
                            <p class="text-gray-500 dark:text-gray-400">Tất cả dịch vụ có sẵn đã được thêm vào loại phòng này.</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                @if($availableServices->flatten()->count() > 0)
                <div class="flex items-center justify-between p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="selectAllModal" onchange="selectAllInModal()" class="mr-2">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Chọn tất cả</span>
                        </label>
                        <span id="modalSelectionCounter" class="text-sm text-gray-500 dark:text-gray-400">0 đã chọn</span>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button onclick="closeAddServiceModal()" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors">
                            Hủy
                        </button>
                        <button onclick="addSelectedServices()" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            Thêm đã chọn
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
            <span class="text-gray-700 dark:text-gray-300">Đang xử lý...</span>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        const roomTypeId = {{ $roomType->room_type_id }};
        let selectedServices = new Set();
        let modalSelectedServices = new Set();

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Modal functions
        function openAddServiceModal() {
            document.getElementById('addServiceModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            modalSelectedServices.clear();
            updateModalSelectionUI();
        }

        function closeAddServiceModal() {
            document.getElementById('addServiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Reset modal state
            modalSelectedServices.clear();
            document.querySelectorAll('.modal-service-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.active-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAllModal').checked = false;
            updateModalSelectionUI();
        }

        // Modal selection functions
        function toggleModalSelection(serviceId) {
            if (modalSelectedServices.has(serviceId)) {
                modalSelectedServices.delete(serviceId);
            } else {
                modalSelectedServices.add(serviceId);
            }
            updateModalSelectionUI();
        }

        function selectAllInModal() {
            const selectAllCheckbox = document.getElementById('selectAllModal');
            const checkboxes = document.querySelectorAll('.modal-service-checkbox');
            
            modalSelectedServices.clear();
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                if (selectAllCheckbox.checked) {
                    modalSelectedServices.add(parseInt(checkbox.value));
                }
            });
            
            updateModalSelectionUI();
        }

        function updateModalSelectionUI() {
            const counter = document.getElementById('modalSelectionCounter');
            if (counter) {
                counter.textContent = `${modalSelectedServices.size} đã chọn`;
            }
        }

        
        async function addSelectedServices() {
            if (modalSelectedServices.size === 0) {
                showNotification('Vui lòng chọn ít nhất một dịch vụ.', 'warning');
                return;
            }

            showLoading();
            
            try {
                const activeIds = [];
                modalSelectedServices.forEach(serviceId => {
                    const activeCheckbox = document.querySelector(`.active-checkbox[data-service-id="${serviceId}"]`);
                    if (activeCheckbox && activeCheckbox.checked) {
                        activeIds.push(serviceId);
                    }
                });

                const response = await fetch(`/admin/room-types/${roomTypeId}/services`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        service_ids: Array.from(modalSelectedServices),
                        active_ids: activeIds
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeAddServiceModal();
                    setTimeout(() => location.reload(), 1000); // Reload để cập nhật giao diện
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi thêm dịch vụ.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function quickAddService(serviceId) {
            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/services`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        service_ids: [serviceId],
                        active_ids: [serviceId] // Mặc định kích hoạt khi thêm nhanh
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi thêm dịch vụ.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Current services management
        function toggleServiceSelection(serviceId) {
            if (selectedServices.has(serviceId)) {
                selectedServices.delete(serviceId);
            } else {
                selectedServices.add(serviceId);
            }
            updateBulkActionsUI();
        }

        function selectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.service-checkbox');
            
            selectedServices.clear();
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                if (selectAllCheckbox.checked) {
                    selectedServices.add(parseInt(checkbox.value));
                }
            });
            
            updateBulkActionsUI();
        }

        function updateBulkActionsUI() {
            const bulkActions = document.getElementById('bulkActions');
            if (bulkActions) {
                if (selectedServices.size > 0) {
                    bulkActions.classList.remove('hidden');
                } else {
                    bulkActions.classList.add('hidden');
                }
            }
        }

        // Individual service actions
        async function toggleStatus(serviceId) {
            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/services/${serviceId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi cập nhật trạng thái.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function removeService(serviceId) {
            if (!confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')) {
                return;
            }

            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/services/${serviceId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi xóa dịch vụ.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Bulk actions
        async function activateSelected() {
            if (selectedServices.size === 0) {
                showNotification('Vui lòng chọn ít nhất một dịch vụ.', 'warning');
                return;
            }

            showLoading();
            
            try {
                const promises = Array.from(selectedServices).map(serviceId => 
                    fetch(`/admin/room-types/${roomTypeId}/services/${serviceId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ is_active: true })
                    })
                );

                const responses = await Promise.all(promises);
                const results = await Promise.all(responses.map(r => r.json()));
                
                const successCount = results.filter(r => r.success).length;
                
                if (successCount > 0) {
                    showNotification(`Đã kích hoạt ${successCount} dịch vụ!`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Không có dịch vụ nào được kích hoạt.', 'warning');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi kích hoạt dịch vụ.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function deactivateSelected() {
            if (selectedServices.size === 0) {
                showNotification('Vui lòng chọn ít nhất một dịch vụ.', 'warning');
                return;
            }

            if (!confirm(`Bạn có chắc chắn muốn ngưng hoạt động ${selectedServices.size} dịch vụ đã chọn?`)) {
                return;
            }

            showLoading();
            
            try {
                const promises = Array.from(selectedServices).map(serviceId => 
                    fetch(`/admin/room-types/${roomTypeId}/services/${serviceId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ is_active: false })
                    })
                );

                const responses = await Promise.all(promises);
                const results = await Promise.all(responses.map(r => r.json()));
                
                const successCount = results.filter(r => r.success).length;
                
                if (successCount > 0) {
                    showNotification(`Đã ngưng hoạt động ${successCount} dịch vụ!`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Không có dịch vụ nào được ngưng hoạt động.', 'warning');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi ngưng hoạt động dịch vụ.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function removeSelected() {
            if (selectedServices.size === 0) {
                showNotification('Vui lòng chọn ít nhất một dịch vụ.', 'warning');
                return;
            }

            if (!confirm(`Bạn có chắc chắn muốn xóa ${selectedServices.size} dịch vụ đã chọn?`)) {
                return;
            }

            showLoading();
            
            try {
                const promises = Array.from(selectedServices).map(serviceId => 
                    fetch(`/admin/room-types/${roomTypeId}/services/${serviceId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        }
                    })
                );

                const responses = await Promise.all(promises);
                const results = await Promise.all(responses.map(r => r.json()));
                
                const successCount = results.filter(r => r.success).length;
                
                if (successCount > 0) {
                    showNotification(`Đã xóa ${successCount} dịch vụ!`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Không có dịch vụ nào được xóa.', 'warning');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi xóa dịch vụ.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Quick actions
        async function activateAll() {
            if (!confirm('Bạn có chắc chắn muốn kích hoạt tất cả dịch vụ?')) {
                return;
            }

            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/services/toggle-all-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ status: true })
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi kích hoạt dịch vụ.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function deactivateAll() {
            if (!confirm('Bạn có chắc chắn muốn ngưng hoạt động tất cả dịch vụ?')) {
                return;
            }

            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/services/toggle-all-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ status: false })
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi ngưng hoạt động dịch vụ.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Utility functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        function showNotification(message, type = 'info') {
            document.querySelectorAll('.notification').forEach(n => n.remove());
            
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm`;
            
            switch (type) {
                case 'success':
                    notification.className += ' bg-green-500 text-white';
                    break;
                case 'error':
                    notification.className += ' bg-red-500 text-white';
                    break;
                case 'warning':
                    notification.className += ' bg-yellow-500 text-white';
                    break;
                default:
                    notification.className += ' bg-blue-500 text-white';
            }
            
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addServiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddServiceModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('addServiceModal');
                    if (!modal.classList.contains('hidden')) {
                        closeAddServiceModal();
                    }
                }
            });

            updateBulkActionsUI();
            updateModalSelectionUI();
        });

        // Search functionality
        function filterServices(searchTerm) {
            const serviceItems = document.querySelectorAll('.service-item');
            const modalItems = document.querySelectorAll('.modal-service-item');
            
            const filter = searchTerm.toLowerCase();
            
            [...serviceItems, ...modalItems].forEach(item => {
                const name = item.querySelector('h3, h5')?.textContent.toLowerCase() || '';
                const price = item.querySelector('p')?.textContent.toLowerCase() || '';
                const unit = item.dataset.unit?.toLowerCase() || '';
                
                if (name.includes(filter) || price.includes(filter) || unit.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        const debouncedFilter = debounce(filterServices, 300);

        const searchInput = document.getElementById('serviceSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                debouncedFilter(e.target.value);
            });
        }

        // Statistics update
        function updateStatistics() {
            const totalElement = document.querySelector('[data-stat="total"]');
            const activeElement = document.querySelector('[data-stat="active"]');
            
            if (totalElement) {
                const totalCount = document.querySelectorAll('.service-item').length;
                totalElement.textContent = totalCount;
            }
            
            if (activeElement) {
                const activeCount = document.querySelectorAll('.service-item[data-active="true"]').length;
                activeElement.textContent = activeCount;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateStatistics();
        });

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>

    <!-- Additional CSS -->
    <style>
        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .dark .modal-body::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .modal-body::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark .modal-body::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        .service-item {
            min-height: 150px; /* Đảm bảo chiều cao tối thiểu */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .grid {
            grid-gap: 1.5rem; /* Tăng khoảng cách giữa các cột */
        }

        @media (max-width: 768px) {
            .md\:grid-cols-3 {
                grid-template-columns: 1fr; /* Đảm bảo chỉ 1 cột trên màn hình nhỏ */
            }
        }

        .service-item:hover {
            transform: translateY(-1px);
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .loading {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .notification {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        button:focus,
        input:focus,
        select:focus {
            outline: 2px solid #10b981;
            outline-offset: 2px;
        }

        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #10b981;
        }

        @media (max-width: 768px) {
            .grid-cols-1.md\\:grid-cols-2 {
                grid-template-columns: 1fr;
            }
            
            .modal-content {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
            }
        }

        @media print {
            .btn, button {
                display: none;
            }
            
            .service-item {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</x-app-layout>