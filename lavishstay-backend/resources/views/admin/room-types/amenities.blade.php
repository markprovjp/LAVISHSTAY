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
                                <span class="ml-1 text-gray-500 dark:text-gray-400">Quản lý tiện ích</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Quản lý tiện ích - {{ $roomType->name }}</h1>
            </div>

            <!-- Right: Actions -->
<<<<<<< HEAD
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button onclick="openAddAmenityModal()"
                    class="btn bg-blue-600 hover:bg-blue-700 text-white">
                    <svg class="w-4 h-4 fill-current shrink-0 mr-2" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span>Thêm tiện ích</span>
                </button>
=======
            
            <div class="grid cursor-pointer grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add room type button -->
              
                    <button onclick="openAddAmenityModal()"
                        class="btn items-center cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                        <svg class="fill-current items-center shrink-0 w-4 h-4 me-2" viewBox="0 0 12 12" width="24" height="24">
                        <path d="M7 4V2a1 1 0 012 0v2h2a1 1 0 010 2H9v2a1 1 0 01-2 0V6H5a1 1 0 010-2h2z"/>
                    </svg>
                        <span class="max-xs:sr-only">Thêm tiện ích</span>
                    </button>
              
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
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
                                <input type="text" id="amenitySearch" placeholder="Tìm kiếm tiện ích..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100">
<<<<<<< HEAD
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
=======
                                <div class="absolute inset-y-3 top-0 mt-3 left-0 pl-3 flex items-center pointer-events-none">
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
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
                                $categories = $roomType->amenities->pluck('category')->unique()->filter();
                            @endphp
                            @foreach($categories as $category)
                                <button onclick="filterByCategory('{{ $category }}')" 
                                    class="filter-btn px-3 py-2 text-sm rounded-lg transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                    {{ $category }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Current Amenities -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <div class="flex justify-between items-center">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                                Tiện ích hiện tại
                                <span class="text-gray-400 dark:text-gray-500 font-medium">({{ $roomType->amenities->count() }})</span>
                            </h2>
                            
                            @if($roomType->amenities->count() > 0)
                                <div class="flex items-center space-x-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="selectAll" onchange="selectAll()" class="mr-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Chọn tất cả</span>
                                    </label>
                                    
                                    <div id="bulkActions" class="hidden space-x-2">
                                        <button onclick="highlightSelected()" class="btn-sm bg-yellow-500 hover:bg-yellow-600 text-white">
                                            Đánh dấu nổi bật
                                        </button>
                                        <button onclick="removeSelected()" class="btn-sm bg-red-500 hover:bg-red-600 text-white">
                                            Xóa đã chọn
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

<<<<<<< HEAD
                    <div class="p-5">
                        @if ($roomType->amenities->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
=======
                    <div class="p-6">
                        @if ($roomType->amenities->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
                                @foreach ($roomType->amenities as $amenity)
                                    <div class="amenity-item relative p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-md transition-all duration-200 {{ $amenity->pivot->is_highlighted ? 'ring-2 ring-yellow-400 bg-yellow-50 dark:bg-yellow-900/20' : '' }}"
                                         data-amenity-id="{{ $amenity->amenity_id }}"
                                         data-highlighted="{{ $amenity->pivot->is_highlighted ? 'true' : 'false' }}"
                                         data-category="{{ $amenity->category }}">
                                        
<<<<<<< HEAD
                                        <!-- Selection checkbox -->
                                        <div class="absolute top-3 left-3">
                                            <input type="checkbox" 
                                                   class="amenity-checkbox" 
                                                   value="{{ $amenity->amenity_id }}"
                                                   onchange="toggleAmenitySelection({{ $amenity->amenity_id }})">
                                        </div>

                                        <!-- Highlight badge -->
                                        @if($amenity->pivot->is_highlighted)
                                            <div class="absolute top-3 right-3">
                                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                    ⭐ Nổi bật
                                                </span>
                                            </div>
                                        @endif

                                        <div class="flex items-start space-x-3 mt-6">
=======
                                        <div class="flex justify-between items-center gap-6 ">
                                            <!-- Selection checkbox -->
                                            <div class="">
                                                <input type="checkbox" 
                                                       class="amenity-checkbox" 
                                                       value="{{ $amenity->amenity_id }}"
                                                       onchange="toggleAmenitySelection({{ $amenity->amenity_id }})">
                                            </div>
    
                                            <!-- Highlight badge -->
                                            @if($amenity->pivot->is_highlighted)
                                                <div class="">
                                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                        ⭐ Nổi bật
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex items-start space-x-3 mt-2">
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
                                            <div class="flex-shrink-0">
                                                @if ($amenity->icon)
                                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                        <span class="text-blue-600 dark:text-blue-400 text-lg">{{ $amenity->icon }}</span>
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
                                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $amenity->name }}</h3>
                                                @if ($amenity->description)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ $amenity->description }}</p>
                                                @endif
                                                
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $amenity->category }}</span>
                                                </div>
                                            </div>

                                            <div class="flex-shrink-0 flex flex-col space-y-1">
                                                <button onclick="toggleHighlight({{ $amenity->amenity_id }})"
                                                    class="p-1 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded transition-colors"
                                                    title="{{ $amenity->pivot->is_highlighted ? 'Bỏ nổi bật' : 'Đánh dấu nổi bật' }}">
                                                    <svg class="w-4 h-4" fill="{{ $amenity->pivot->is_highlighted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                    </svg>
                                                </button>
                                                
                                                <button onclick="removeAmenity({{ $amenity->amenity_id }})"
                                                    class="p-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded transition-colors"
                                                    title="Xóa tiện ích">
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
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chưa có tiện ích nào</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">Thêm tiện ích để khách hàng có thể xem thông tin về loại phòng này</p>
                                <button onclick="openAddAmenityModal()"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Thêm tiện ích đầu tiên
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
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tổng số tiện ích</span>
                            <span class="text-lg font-semibold text-blue-600 dark:text-blue-400" data-stat="total">{{ $roomType->amenities->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tiện ích nổi bật</span>
                            <span class="text-lg font-semibold text-yellow-600 dark:text-yellow-400" data-stat="highlighted">{{ $roomType->amenities->where('pivot.is_highlighted', 1)->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Có thể thêm</span>
                            <span class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $availableAmenities->flatten()->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if($roomType->amenities->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Hành động nhanh</h3>
                    <div class="space-y-3">
                        <button onclick="highlightAll()" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 inline-block mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" width="24px" height="24px">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.922-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Đánh dấu nổi bật tất cả
                        </button>
                        <button onclick="removeAllHighlights()" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 inline-block mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24px" height="24px">>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Bỏ nổi bật tất cả
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Add Amenity Modal -->
    <div id="addAmenityModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
<<<<<<< HEAD
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
=======
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl mt-10 w-full max-w-7xl max-h-[90vh] flex flex-col">
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Thêm tiện ích</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Chọn các tiện ích muốn thêm vào loại phòng</p>
                    </div>
                    <button onclick="closeAddAmenityModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto p-6">
                    @if($availableAmenities->flatten()->count() > 0)
                        @foreach($availableAmenities as $category => $amenities)
                            <div class="mb-8">
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 px-3 py-1 rounded-full text-sm mr-3">{{ $category }}</span>
                                    <span class="text-sm text-gray-500">({{ $amenities->count() }} tiện ích)</span>
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($amenities as $amenity)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                            <div class="flex items-start space-x-3">
                                                <!-- Selection checkbox -->
                                                <div class="flex-shrink-0 mt-1">
                                                    <input type="checkbox" 
                                                           class="modal-amenity-checkbox" 
                                                           value="{{ $amenity->amenity_id }}"
                                                           data-amenity-id="{{ $amenity->amenity_id }}"
                                                           onchange="toggleModalSelection({{ $amenity->amenity_id }})">
                                                </div>

                                                <!-- Icon -->
                                                <div class="flex-shrink-0">
                                                    @if ($amenity->icon)
                                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                            <span class="text-blue-600 dark:text-blue-400 text-lg">{{ $amenity->icon }}</span>
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
                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $amenity->name }}</h5>
                                                    @if ($amenity->description)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $amenity->description }}</p>
                                                    @endif
                                                    
                                                    <!-- Highlight option -->
                                                    <label class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                                        <input type="checkbox" 
                                                               class="highlight-checkbox mr-2" 
                                                               data-amenity-id="{{ $amenity->amenity_id }}">
                                                        <span>Đánh dấu nổi bật</span>
                                                    </label>
                                                </div>

                                                <!-- Quick add button -->
                                                <div class="flex-shrink-0">
                                                    <button onclick="quickAddAmenity({{ $amenity->amenity_id }})"
                                                        class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors"
                                                        title="Thêm nhanh">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Không có tiện ích nào để thêm</h3>
                            <p class="text-gray-500 dark:text-gray-400">Tất cả tiện ích có sẵn đã được thêm vào loại phòng này.</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                @if($availableAmenities->flatten()->count() > 0)
                <div class="flex items-center justify-between p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="selectAllModal" onchange="selectAllInModal()" class="mr-2">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Chọn tất cả</span>
                        </label>
                        <span id="modalSelectionCounter" class="text-sm text-gray-500 dark:text-gray-400">0 đã chọn</span>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button onclick="closeAddAmenityModal()" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors">
                            Hủy
                        </button>
                        <button onclick="addSelectedAmenities()" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
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
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="text-gray-700 dark:text-gray-300">Đang xử lý...</span>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        const roomTypeId = {{ $roomType->room_type_id }};
        let selectedAmenities = new Set();
        let modalSelectedAmenities = new Set();

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Modal functions
        function openAddAmenityModal() {
            document.getElementById('addAmenityModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            modalSelectedAmenities.clear();
            updateModalSelectionUI();
        }

        function closeAddAmenityModal() {
            document.getElementById('addAmenityModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Reset modal state
            modalSelectedAmenities.clear();
            document.querySelectorAll('.modal-amenity-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.highlight-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAllModal').checked = false;
            updateModalSelectionUI();
        }

        // Modal selection functions
        function toggleModalSelection(amenityId) {
            if (modalSelectedAmenities.has(amenityId)) {
                modalSelectedAmenities.delete(amenityId);
            } else {
                modalSelectedAmenities.add(amenityId);
            }
            updateModalSelectionUI();
        }

        function selectAllInModal() {
            const selectAllCheckbox = document.getElementById('selectAllModal');
            const checkboxes = document.querySelectorAll('.modal-amenity-checkbox');
            
            modalSelectedAmenities.clear();
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                if (selectAllCheckbox.checked) {
                    modalSelectedAmenities.add(parseInt(checkbox.value));
                }
            });
            
            updateModalSelectionUI();
        }

        function updateModalSelectionUI() {
            const counter = document.getElementById('modalSelectionCounter');
            if (counter) {
                counter.textContent = `${modalSelectedAmenities.size} đã chọn`;
            }
        }

        // Add amenities functions
        async function addSelectedAmenities() {
            if (modalSelectedAmenities.size === 0) {
                showNotification('Vui lòng chọn ít nhất một tiện ích.', 'warning');
                return;
            }

            showLoading();
            
            try {
                // Get highlighted amenities
                const highlightedIds = [];
                modalSelectedAmenities.forEach(amenityId => {
                    const highlightCheckbox = document.querySelector(`.highlight-checkbox[data-amenity-id="${amenityId}"]`);
                    if (highlightCheckbox && highlightCheckbox.checked) {
                        highlightedIds.push(amenityId);
                    }
                });

                const response = await fetch(`/admin/room-types/${roomTypeId}/amenities`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        amenity_ids: Array.from(modalSelectedAmenities),
                        highlighted_ids: highlightedIds
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeAddAmenityModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi thêm tiện ích.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function quickAddAmenity(amenityId) {
            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/amenities`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        amenity_ids: [amenityId],
                        highlighted_ids: []
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
                showNotification('Có lỗi xảy ra khi thêm tiện ích.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Current amenities management
        function toggleAmenitySelection(amenityId) {
            if (selectedAmenities.has(amenityId)) {
                selectedAmenities.delete(amenityId);
            } else {
                selectedAmenities.add(amenityId);
            }
            updateBulkActionsUI();
        }

        function selectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.amenity-checkbox');
            
            selectedAmenities.clear();
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                if (selectAllCheckbox.checked) {
                    selectedAmenities.add(parseInt(checkbox.value));
                }
            });
            
            updateBulkActionsUI();
        }

        function updateBulkActionsUI() {
            const bulkActions = document.getElementById('bulkActions');
            if (bulkActions) {
                if (selectedAmenities.size > 0) {
                    bulkActions.classList.remove('hidden');
                } else {
                    bulkActions.classList.add('hidden');
                }
            }
        }

        // Individual amenity actions
        async function toggleHighlight(amenityId) {
            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/amenities/${amenityId}/highlight`, {
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
                showNotification('Có lỗi xảy ra khi cập nhật trạng thái nổi bật.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function removeAmenity(amenityId) {
            if (!confirm('Bạn có chắc chắn muốn xóa tiện ích này?')) {
                return;
            }

            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/amenities/${amenityId}`, {
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
                showNotification('Có lỗi xảy ra khi xóa tiện ích.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Bulk actions
        async function highlightSelected() {
            if (selectedAmenities.size === 0) {
                showNotification('Vui lòng chọn ít nhất một tiện ích.', 'warning');
                return;
            }

            showLoading();
            
            try {
                const promises = Array.from(selectedAmenities).map(amenityId => 
                    fetch(`/admin/room-types/${roomTypeId}/amenities/${amenityId}/highlight`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ is_highlighted: true })
                    })
                );

                const responses = await Promise.all(promises);
                const results = await Promise.all(responses.map(r => r.json()));
                
                const successCount = results.filter(r => r.success).length;
                
                if (successCount > 0) {
                    showNotification(`Đã đánh dấu nổi bật ${successCount} tiện ích!`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Không có tiện ích nào được cập nhật.', 'warning');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi đánh dấu tiện ích.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function removeSelected() {
            if (selectedAmenities.size === 0) {
                showNotification('Vui lòng chọn ít nhất một tiện ích.', 'warning');
                return;
            }

            if (!confirm(`Bạn có chắc chắn muốn xóa ${selectedAmenities.size} tiện ích đã chọn?`)) {
                return;
            }

            showLoading();
            
            try {
                const promises = Array.from(selectedAmenities).map(amenityId => 
                    fetch(`/admin/room-types/${roomTypeId}/amenities/${amenityId}`, {
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
                    showNotification(`Đã xóa ${successCount} tiện ích!`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Không có tiện ích nào được xóa.', 'warning');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra khi xóa tiện ích.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Quick actions
        async function highlightAll() {
            if (!confirm('Bạn có chắc chắn muốn đánh dấu nổi bật tất cả tiện ích?')) {
                return;
            }

            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/amenities/highlight-all`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ is_highlighted: true })
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
                showNotification('Có lỗi xảy ra khi đánh dấu tiện ích.', 'error');
            } finally {
                hideLoading();
            }
        }

        async function removeAllHighlights() {
            if (!confirm('Bạn có chắc chắn muốn bỏ đánh dấu nổi bật tất cả tiện ích?')) {
                return;
            }

            showLoading();
            
            try {
                const response = await fetch(`/admin/room-types/${roomTypeId}/amenities/highlight-all`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ is_highlighted: false })
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
                showNotification('Có lỗi xảy ra khi bỏ đánh dấu tiện ích.', 'error');
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
            // Remove existing notifications
            document.querySelectorAll('.notification').forEach(n => n.remove());
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm`;
            
            // Set colors based on type
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
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal when clicking outside
            document.getElementById('addAmenityModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddAmenityModal();
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('addAmenityModal');
                    if (!modal.classList.contains('hidden')) {
                        closeAddAmenityModal();
                    }
                }
            });

            // Initialize UI state
            updateBulkActionsUI();
            updateModalSelectionUI();
        });

        // Search functionality (optional enhancement)
        function filterAmenities(searchTerm) {
            const amenityItems = document.querySelectorAll('.amenity-item');
            const modalItems = document.querySelectorAll('.modal-amenity-item');
            
            const filter = searchTerm.toLowerCase();
            
            [...amenityItems, ...modalItems].forEach(item => {
                const name = item.querySelector('h3, h5')?.textContent.toLowerCase() || '';
                const description = item.querySelector('p')?.textContent.toLowerCase() || '';
                const category = item.dataset.category?.toLowerCase() || '';
                
                if (name.includes(filter) || description.includes(filter) || category.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Statistics update
        function updateStatistics() {
            const totalElement = document.querySelector('[data-stat="total"]');
            const highlightedElement = document.querySelector('[data-stat="highlighted"]');
            
            if (totalElement) {
                const totalCount = document.querySelectorAll('.amenity-item').length;
                totalElement.textContent = totalCount;
            }
            
            if (highlightedElement) {
                const highlightedCount = document.querySelectorAll('.amenity-item[data-highlighted="true"]').length;
                highlightedElement.textContent = highlightedCount;
            }
        }

        // Auto-save functionality (optional)
        let autoSaveTimeout;
        function scheduleAutoSave() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Auto-save logic here if needed
                console.log('Auto-save triggered');
            }, 2000);
        }

        // Export/Import functionality (future enhancement)
        function exportAmenities() {
            const amenities = Array.from(document.querySelectorAll('.amenity-item')).map(item => ({
                id: item.dataset.amenityId,
                highlighted: item.dataset.highlighted === 'true',
                category: item.dataset.category
            }));
            
            const dataStr = JSON.stringify(amenities, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = `room-type-${roomTypeId}-amenities.json`;
            link.click();
        }

        // Drag and drop reordering (future enhancement)
        function initializeDragAndDrop() {
            // Implementation for drag and drop reordering
            console.log('Drag and drop initialized');
        }

        // Performance optimization
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

        // Optimized search with debouncing
        const debouncedFilter = debounce(filterAmenities, 300);

        // Add search input event listener if search box exists
        const searchInput = document.getElementById('amenitySearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                debouncedFilter(e.target.value);
            });
        }

        // Accessibility improvements
        function initializeAccessibility() {
            // Add ARIA labels and keyboard navigation
            document.querySelectorAll('button').forEach(button => {
                if (!button.getAttribute('aria-label') && button.title) {
                    button.setAttribute('aria-label', button.title);
                }
            });

            // Add keyboard navigation for modal
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('addAmenityModal');
                if (!modal.classList.contains('hidden')) {
                    if (e.key === 'Tab') {
                        // Handle tab navigation within modal
                        const focusableElements = modal.querySelectorAll(
                            'button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
                        );
                        const firstElement = focusableElements[0];
                        const lastElement = focusableElements[focusableElements.length - 1];

                        if (e.shiftKey && document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        } else if (!e.shiftKey && document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        }

        // Initialize everything when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeAccessibility();
            updateStatistics();
            
            // Add smooth scrolling for better UX
            document.documentElement.style.scrollBehavior = 'smooth';
            
            // Initialize tooltips if needed
            const tooltipElements = document.querySelectorAll('[title]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    // Custom tooltip implementation if needed
                });
            });
        });

        // Error handling for network issues
        window.addEventListener('online', function() {
            showNotification('Kết nối internet đã được khôi phục.', 'success');
        });

        window.addEventListener('offline', function() {
            showNotification('Mất kết nối internet. Vui lòng kiểm tra kết nối của bạn.', 'warning');
        });

        // Cleanup function
        window.addEventListener('beforeunload', function() {
            // Cleanup any pending operations
            clearTimeout(autoSaveTimeout);
        });

    </script>

    <!-- Additional CSS for better styling -->
    <style>
        /* Custom scrollbar for modal */
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

        /* Dark mode scrollbar */
        .dark .modal-body::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .modal-body::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark .modal-body::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Smooth transitions */
        .amenity-item {
            transition: all 0.2s ease-in-out;
        }

        .amenity-item:hover {
            transform: translateY(-1px);
        }

        /* Loading animation */
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

        /* Notification animations */
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

        /* Focus styles for accessibility */
        button:focus,
        input:focus,
        select:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Custom checkbox styles */
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #3b82f6;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .grid-cols-1.md\\:grid-cols-2 {
                grid-template-columns: 1fr;
            }
            
            .modal-content {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
            }
        }

        /* Print styles */
        @media print {
            .btn, button {
                display: none;
            }
            
            .amenity-item {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>

</x-app-layout>


