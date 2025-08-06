<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-centesr">
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
                                <span class="ml-1 text-gray-500 dark:text-gray-400">Chi tiết</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $roomType->name }}</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button
                    class="btn mt-4 cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <a href="{{ route('admin.room-types.edit', $roomType->room_type_id) }}"
                        class="flex flex-center justify-center w-full ">
                        <svg class="fill-current shrink-0 w-4 h-4 me-2" viewBox="0 0 16 16" width="16"
                            height="16">
                            <path
                                d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z" />
                        </svg>
                        <span class="max-xs:sr-only content-center">Sửa</span>
                    </a>
                </button>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Main Content -->
            <div class="xl:col-span-2 space-y-6">


                <!-- Room Type Images Gallery -->
                <div class="bg-white w-full dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700/60">
                        <div class="px-5 py-4">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                                Thư viện ảnh
                                <span
                                    class="text-gray-400 dark:text-gray-500 font-medium">({{ $roomType->images->count() }})</span>
                            </h2>
                        </div>

                        <button onclick="openImageManager({{ $roomType->room_type_id }})"
                            class="btn cursor-pointer bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                            <svg class="shrink-0 me-2" width="16px" height="16px" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="max-xs:sr-only content-center">Quản lý ảnh</span>
                        </button>
                    </div>

                    <div class="p-5">
                        <div class="space-y-4 p-6">
                            @if ($roomType->images->count() > 0)
                                @php
                                    $mainImage = $roomType->images->where('is_main', 1)->first();
                                    $otherImages = $roomType->images->where('is_main', 0)->take(4);

                                    // Nếu không có ảnh chính, lấy ảnh đầu tiên làm ảnh chính
                                    if (!$mainImage) {
                                        $mainImage = $roomType->images->first();
                                        $otherImages = $roomType->images->skip(1)->take(4);
                                    }

                                    $totalImages = $roomType->images->count();
                                    $remainingImages = $totalImages - 5; // Trừ đi 1 ảnh chính + 4 ảnh phụ
                                @endphp

                                <!-- Gallery Layout -->
                                <div class="flex gap-4 h-96">

                                    <!-- Main Image (Left Half) -->
                                    <div class="w-1/2 relative">
                                        <div class="relative h-full group cursor-pointer overflow-hidden rounded-xl"
                                            onclick="viewSingleImage('{{ $mainImage->image_path }}', '{{ $roomType->name }} - Ảnh chính')">
                                            <img src="{{ $mainImage->image_path }}"
                                                alt="Ảnh chính - {{ $roomType->name }}"
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">

                                            <!-- Main Image Badge -->
                                            <div
                                                class="absolute top-3 left-3 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg z-10">
                                                ⭐ Ảnh chính
                                            </div>

                                            <!-- Hover Overlay -->
                                            <div
                                                class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300">
                                            </div>
                                            <div
                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <div class="bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg">
                                                    <svg class="w-5 h-5 inline-block mr-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    Xem ảnh lớn
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Other Images (Right Half) -->
                                    <div class="w-1/2 flex flex-col gap-2">
                                        <!-- Top Row -->
                                        <div class="flex gap-2 h-1/2">
                                            <!-- Image 2 -->
                                            <div class="w-1/2 relative">
                                                @if ($otherImages->count() > 0)
                                                    <div class="relative h-full group cursor-pointer overflow-hidden rounded-lg"
                                                        onclick="viewSingleImage('{{ $otherImages->first()->image_path }}', '{{ $roomType->name }} - Ảnh 2')">
                                                        <img src="{{ $otherImages->first()->image_path }}"
                                                            alt="Ảnh 2"
                                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                        <div
                                                            class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="h-full bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                                        onclick="openImageManager({{ $roomType->room_type_id }})">
                                                        <div class="text-center text-gray-400">
                                                            <svg class="w-8 h-8 mx-auto mb-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                            <p class="text-xs">Thêm ảnh</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Image 3 -->
                                            <div class="w-1/2 relative">
                                                @if ($otherImages->count() > 1)
                                                    <div class="relative h-full group cursor-pointer overflow-hidden rounded-lg"
                                                        onclick="viewSingleImage('{{ $otherImages->skip(1)->first()->image_path }}', '{{ $roomType->name }} - Ảnh 3')">
                                                        <img src="{{ $otherImages->skip(1)->first()->image_path }}"
                                                            alt="Ảnh 3"
                                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                        <div
                                                            class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="h-full bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                                        onclick="openImageManager({{ $roomType->room_type_id }})">
                                                        <div class="text-center text-gray-400">
                                                            <svg class="w-8 h-8 mx-auto mb-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                            <p class="text-xs">Thêm ảnh</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Bottom Row -->
                                        <div class="flex gap-2 h-1/2">
                                            <!-- Image 4 -->
                                            <div class="w-1/2 relative">
                                                @if ($otherImages->count() > 2)
                                                    <div class="relative h-full group cursor-pointer overflow-hidden rounded-lg"
                                                        onclick="viewSingleImage('{{ $otherImages->skip(2)->first()->image_path }}', '{{ $roomType->name }} - Ảnh 4')">
                                                        <img src="{{ $otherImages->skip(2)->first()->image_path }}"
                                                            alt="Ảnh 4"
                                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                        <div
                                                            class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="h-full bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                                        onclick="openImageManager({{ $roomType->room_type_id }})">
                                                        <div class="text-center text-gray-400">
                                                            <svg class="w-8 h-8 mx-auto mb-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                            <p class="text-xs">Thêm ảnh</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Image 5 / View All -->
                                            <div class="w-1/2 relative">
                                                @if ($otherImages->count() > 3)
                                                    <div
                                                        class="relative h-full cursor-pointer overflow-hidden rounded-lg">
                                                        <img src="{{ $otherImages->skip(3)->first()->image_path }}"
                                                            alt="Ảnh 5" class="w-full h-full object-cover">

                                                        @if ($remainingImages > 0)
                                                            <!-- "+X more" overlay -->
                                                            <div class="absolute inset-0 opacity-50 bg-black flex items-center justify-center cursor-pointer"
                                                                onclick="openAllImagesModal()">
                                                                <div class="text-center text-white">
                                                                    <div class="text-3xl font-bold">
                                                                        +{{ $remainingImages }}</div>
                                                                    <div class="text-sm mt-1">ảnh khác</div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <!-- Normal click to view -->
                                                            <div class="absolute inset-0 group cursor-pointer"
                                                                onclick="viewSingleImage('{{ $otherImages->skip(3)->first()->image_path }}', '{{ $roomType->name }} - Ảnh 5')">
                                                                <div
                                                                    class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="h-full bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                                        onclick="openImageManager({{ $roomType->room_type_id }})">
                                                        <div class="text-center text-gray-400">
                                                            <svg class="w-8 h-8 mx-auto mb-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                            <p class="text-xs">Thêm ảnh</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- View All Images Button -->
                                <div class="mt-4 text-center">
                                    <button onclick="openAllImagesModal()"
                                        class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Xem tất cả {{ $roomType->images->count() }} ảnh
                                    </button>
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="text-center py-12">
                                    <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-8 max-w-md mx-auto">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chưa có
                                            ảnh
                                            nào</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-6">Thêm ảnh để khách hàng có thể
                                            xem
                                            loại phòng này</p>
                                        <button onclick="openImageManager({{ $roomType->room_type_id }})"
                                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Thêm ảnh đầu tiên
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Single Image Viewer Modal -->
                <div id="singleImageModal" class="h-full fixed inset-0 bg-black bg-opacity-90 z-50 hidden">
                    <div class="h-full flex items-center justify-center p-4">
                        <!-- Close Button -->
                        <button onclick="closeSingleImageModal()"
                            class="absolute top-6 right-6 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-3 z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Image Container -->
                        <div class="max-w-4xl max-h-full flex flex-col items-center">
                            <img id="singleImageSrc" src="" alt=""
                                class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">

                            <!-- Image Info -->
                            <div class="mt-4 text-center">
                                <h3 id="singleImageTitle" class="text-xl font-semibold text-white mb-2"></h3>
                                <p class="text-gray-300">Click ESC để đóng</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Images Gallery Modal -->
                <div id="allImagesModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden">
                    <div class="h-full flex flex-col">

                        <!-- Header -->
                        <div class="flex justify-between items-center p-6 bg-black bg-opacity-50">
                            <div>
                                <h2 class="text-2xl font-bold text-white">Tất cả ảnh - {{ $roomType->name }}</h2>
                                <p class="text-gray-300">{{ $roomType->images->count() }} ảnh</p>
                            </div>
                            <button onclick="closeAllImagesModal()"
                                class="text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Images Grid -->
                        <div class="flex-1 overflow-y-auto p-6">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                @foreach ($roomType->images as $index => $image)
                                    <div class="relative group cursor-pointer"
                                        onclick="viewImageFromGallery({{ $index }})">
                                        <img src="{{ $image->image_path }}"
                                            alt="Ảnh {{ $index + 1 }} - {{ $roomType->name }}"
                                            class="w-full h-48 object-cover rounded-lg shadow-lg group-hover:shadow-xl transition-all duration-300">

                                        @if ($image->is_main)
                                            <div
                                                class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                Ảnh chính
                                            </div>
                                        @endif

                                        <!-- Hover Overlay -->
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            <div class="bg-white bg-opacity-90 text-gray-800 px-3 py-2 rounded-lg">
                                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Xem
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>


                <div class="space-y-6 flex items-center gap-6 justify-between">
                    <!-- Room Type Info -->
                    <div class="bg-white h-full flex-1 dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thông tin loại phòng</h2>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4 p-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên
                                        loại phòng</label>
                                    <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $roomType->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mã
                                        loại phòng</label>
                                    <p
                                        class="text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md inline-block">
                                        {{ $roomType->room_type_id }} </p> - <span
                                        class="bg-red-500 rounded-md text-white p-2 dark:bg-green-700 dark:text-white-400">{{ $roomType->room_code }}</span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô
                                        tả</label>
                                    <p class="text-gray-900 dark:text-gray-100">
                                        {{ $roomType->description ?? 'Chưa có mô tả' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Statistics -->
                    <div class="bg-white h-full flex-1 dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Thông tin loại phòng</h2>
                        </div>
                        <div class="p-5 mt-3">
                            <div class="space-y-4 p-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Tổng số ảnh</span>
                                    <span
                                        class="text-lg font-semibold text-purple-600 dark:text-purple-400">{{ $roomType->images->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Ảnh chính</span>
                                    <span
                                        class="text-lg font-semibold {{ $roomType->images->where('is_main', 1)->count() > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $roomType->images->where('is_main', 1)->count() > 0 ? '✓' : '✗' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Tổng số tiện ích</span>
                                    <span
                                        class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $roomType->amenities->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Tiện ích nổi bật</span>
                                    <span
                                        class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ $roomType->amenities->where('is_highlighted', 1)->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Số phòng</span>
                                    <span
                                        class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $roomType->rooms->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-5 flex justify-between py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                            Tiện ích
                            <span
                                class="text-gray-400 dark:text-gray-500 font-medium">({{ $roomType->amenities->count() }})</span>
                        </h2>
                        <button onclick="openAmenityManager({{ $roomType->room_type_id }})"
                            class="btn cursor-pointer bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                            <svg class="shrink-0 me-2" width="16px" height="16px"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="max-xs:sr-only content-center">Quản lý tiện ích</span>
                        </button>
                    </div>
                    <div class="p-5">
                        @if ($roomType->amenities->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-3">
                                @foreach ($roomType->amenities as $amenity)
                                    <div
                                        class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg {{ $amenity->is_highlighted ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                        <div class="flex-shrink-0">
                                        </div>

                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $amenity->name }}
                                                @if ($amenity->is_highlighted)
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                        Nổi bật
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 002 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">Chưa có tiện ích nào</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            

            <!-- Package Management Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                <div class="px-5 flex justify-between py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-medium text-lg text-gray-800 dark:text-gray-100">
                        Quản lý gói dịch vụ
                        <span class="text-gray-400 dark:text-gray-500 font-medium">({{ $roomType->packages->count() ?? 0 }})</span>
                    </h2>
                    <button onclick="openPackageModal({{ $roomType->room_type_id }}, null, 'create')"
                            class="btn cursor-pointer bg-green-600 text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 transition-colors duration-200">
                        <svg class="shrink-0 me-2" width="16px" height="16px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span class="max-xs:sr-only content-center">Thêm gói mới</span>
                    </button>
                </div>
                <div class="p-5">
                    @if ($roomType->packages->count() > 0)
                        <div class="overflow-x-visible">
                            <table class="w-full table-auto text-sm text-left text-gray-900 dark:text-gray-100">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-3">ID</th>
                                        <th class="px-6 py-3">Tên gói</th>
                                        <th class="px-6 py-3">Phụ thu (VND)</th>
                                        <th class="px-6 py-3">Bao gồm tất cả dịch vụ</th>
                                        <th class="px-6 py-3">Số dịch vụ</th>
                                        <th class="px-6 py-3">Trạng thái</th>
                                        <th class="px-6 py-3">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($roomType->packages as $package)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $package->package_id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="max-w-xs truncate">{{ $package->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($package->price_modifier_vnd, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $package->include_all_services ? 'Có' : 'Không' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                {{ $package->services->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button type="button"
                                                    onclick="togglePackageStatus({{ $package->package_id }}, {{ $package->is_active ? 'true' : 'false' }}, '{{ $package->name }}')"
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium focus:outline-none transition-colors
                                                        {{ $package->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 hover:bg-red-200' }}">
                                                    {{ $package->is_active ? 'Hoạt động' : 'Ngưng hoạt động' }}
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center align-middle relative">
                                                <div class="flex justify-center items-center h-full">
                                                    <button type="button"
                                                        class="button-action inline-flex items-center justify-center w-8 h-8 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors duration-200"
                                                        onclick="togglePackageDropdown({{ $package->package_id }})"
                                                        id="dropdown-package-button-{{ $package->package_id }}">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                        </svg>
                                                    </button>
                                                    <!-- Dropdown Menu -->
                                                    <div id="dropdown-package-menu-{{ $package->package_id }}"
                                                        class="hidden absolute right-0 top-full z-50 min-w-[180px] bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-2">
                                                        <div class="py-1" role="menu">
                                                            <!-- Edit -->
                                                            <button
                                                                onclick="openPackageModal({{ $roomType->room_type_id }}, {{ $package->package_id }}, 'edit'); closePackageDropdown({{ $package->package_id }})"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                                Sửa
                                                            </button>
                                                            <!-- Manage Services -->
                                                            <button
                                                                onclick="managePackageServices({{ $roomType->room_type_id }}, {{ $package->package_id }}); closePackageDropdown({{ $package->package_id }})"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
                                                                role="menuitem">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12h6m-3 3v-3m-3-3h6m2-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Quản lý dịch vụ
                                                            </button>
                                                            <!-- Divider -->
                                                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                                            <!-- Delete -->
                                                            <form action="{{ route('admin.room-types.packages.destroy', [$roomType->room_type_id, $package->package_id]) }}" method="POST" class="inline"
                                                                onsubmit="return confirmDeletePackage(this, '{{ $package->name }}');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="delete-btn flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                                                        role="menuitem">
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                    </svg>
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Expandable Details Row -->
                                        <tr id="package-details-{{ $package->package_id }}"
                                            class="hidden bg-gray-50 dark:bg-gray-700/50">
                                            <td colspan="6" class="px-6 py-4">
                                                <div class="space-y-4">
                                                    <div>
                                                        <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">
                                                            Chi tiết gói dịch vụ</h4>
                                                        <div class="space-y-2">
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Tên:</span>
                                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                                    {{ $package->name }}
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Phụ thu:</span>
                                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                                    {{ number_format($package->price_modifier_vnd, 0, ',', '.') }} VND
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Bao gồm tất cả dịch vụ:</span>
                                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                                    {{ $package->include_all_services ? 'Có' : 'Không' }}
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Mô tả:</span>
                                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                                    {{ $package->description ?? 'Chưa có mô tả' }}
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Trạng thái:</span>
                                                                <p class="text-sm text-green-600 dark:text-green-400">
                                                                    {{ $package->is_active ? 'Hoạt động' : 'Ngưng hoạt động' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Chưa có gói dịch vụ nào</p>
                        </div>
                    @endif
                </div>
            </div>

                <!-- Package Modal -->
                <div id="packageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closePackageModal(event)">
                    <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md mx-auto mt-20">
                        <h2 id="modalTitle" class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4"></h2>
                        <form id="packageForm" method="POST">
                            @csrf
                            <input type="hidden" name="package_id" id="packageId">
                            <input type="hidden" name="_method" id="packageMethod" value="POST">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tên gói</label>
                                <input type="text" name="name" id="packageName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phụ thu (VND)</label>
                                <input type="number" name="price_modifier_vnd" id="packagePrice" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bao gồm tất cả dịch vụ</label>
                                <select name="include_all_services" id="packageIncludeAll" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="1">Có</option>
                                    <option value="0">Không</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mô tả</label>
                                <textarea name="description" id="packageDescription" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" rows="3"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                                <select name="is_active" id="packageIsActive" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Ngưng hoạt động</option>
                                </select>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="closePackageModal()" class="btn bg-gray-500 text-white hover:bg-gray-600 dark:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 px-4 py-2 rounded">
                                    Hủy
                                </button>
                                <button type="submit" class="btn bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200 px-4 py-2 rounded">
                                    Lưu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <!-- Sidebar -->
            <div class="space-y-6">



                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Hành động nhanh</h2>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3">
                            <button onclick="viewRoomsList({{ $roomType->room_type_id }})"
                                class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    width="24" height="24">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                    <path fill-rule="evenodd"
                                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110-2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Xem danh sách phòng
                            </button>
                            <button onclick="duplicateRoomType({{ $roomType->room_type_id }})"
                                class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    width="24" height="24">
                                    <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"></path>
                                    <path
                                        d="M3 5a2 2 0 012-2 3 3 0 003 3h6a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11.586l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L11.586 11H15z">
                                    </path>
                                </svg>
                                Sao chép loại phòng
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Related Rooms Preview -->
                @if ($roomType->rooms->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Phòng liên quan</h2>
                        </div>
                        <div class="p-5">
                            <div class="space-y-3">
                                @foreach ($roomType->rooms->take(3) as $room)
                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div class="flex-shrink-0">
                                            @if ($room->image)
                                                <img class="w-10 h-10 rounded-lg object-cover"
                                                    src="{{ $room->image }}" alt="{{ $room->name }}">
                                            @else
                                                <div
                                                    class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3 flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                {{ $room->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ number_format($room->base_price_vnd, 0, ',', '.') }} VND/đêm
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($roomType->rooms->count() > 3)
                                    <button onclick="viewRoomsList({{ $roomType->room_type_id }})"
                                        class="w-full text-center py-2 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        Xem thêm {{ $roomType->rooms->count() - 3 }} phòng khác
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Image Viewer Modal -->
    <div id="imageViewerModal"
        class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center">

            <!-- Close Button -->
            <button onclick="closeImageViewer()"
                class="absolute top-6 right-6 text-white hover:text-gray-300 z-20 bg-black bg-opacity-50 rounded-full p-2 transition-all duration-200 hover:bg-opacity-70">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Image Container -->
            <div class="relative max-w-full max-h-full flex items-center justify-center">
                <img id="viewerImage" src="" alt=""
                    class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">

                <!-- Loading Spinner -->
                <div id="imageLoader"
                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg hidden">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                </div>
            </div>

            <!-- Image Info -->
            <div
                class="absolute bottom-6 left-6 bg-black bg-opacity-70 text-white px-6 py-3 rounded-lg backdrop-blur-sm">
                <p id="viewerTitle" class="text-lg font-medium"></p>
                <p id="viewerDescription" class="text-sm text-gray-300 mt-1"></p>
            </div>

            <!-- Navigation Arrows (for future gallery navigation) -->
            <button id="prevImageBtn" onclick="previousImage()"
                class="absolute left-6 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-3 transition-all duration-200 hover:bg-opacity-70 hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button id="nextImageBtn" onclick="nextImage()"
                class="absolute right-6 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-3 transition-all duration-200 hover:bg-opacity-70 hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- All Images Gallery Modal -->
    <div id="allImagesModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden">
        <div class="h-full flex flex-col">

            <!-- Header -->
            <div class="flex justify-between items-center p-6 bg-black bg-opacity-50">
                <div>
                    <h2 class="text-2xl font-bold text-white">Tất cả ảnh - {{ $roomType->name }}</h2>
                    <p class="text-gray-300">{{ $roomType->images->count() }} ảnh</p>
                </div>
                <button onclick="closeAllImagesModal()"
                    class="text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Images Grid -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach ($roomType->images as $index => $image)
                        <div class="relative group cursor-pointer"
                            onclick="viewImageFromGallery({{ $index }})">
                            <img src="{{ $image->image_path }}"
                                alt="Ảnh {{ $index + 1 }} - {{ $roomType->name }}"
                                class="w-full h-48 object-cover rounded-lg shadow-lg group-hover:shadow-xl transition-all duration-300">

                            @if ($image->is_main)
                                <div
                                    class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    Ảnh chính
                                </div>
                            @endif

                            <!-- Hover Overlay -->
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="bg-white bg-opacity-90 text-gray-800 px-3 py-2 rounded-lg">
                                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Xem
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    

    <!-- Scripts -->
    <script>
        // SweetAlert2 confirm delete
        function confirmDeletePackage(form, packageName = '') {
            event.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc muốn xóa????    ?',
                text: packageName ? `Xóa gói: ${packageName}` : "Bạn sẽ không thể hoàn tác thao tác này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }

        // Image gallery logic
        let currentImageIndex = 0;
        let allImages = @json($roomType->images->values());

        function viewImage(imageUrl, title, description = '') {
            const modal = document.getElementById('imageViewerModal');
            const image = document.getElementById('viewerImage');
            const titleElement = document.getElementById('viewerTitle');
            const descriptionElement = document.getElementById('viewerDescription');
            const loader = document.getElementById('imageLoader');
            loader.classList.remove('hidden');
            image.onload = function() { loader.classList.add('hidden'); };
            image.src = imageUrl;
            titleElement.textContent = title;
            descriptionElement.textContent = description;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeImageViewer() {
            const modal = document.getElementById('imageViewerModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        function openAllImagesModal() {
            document.getElementById('allImagesModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeAllImagesModal() {
            document.getElementById('allImagesModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function viewImageFromGallery(index) {
            if (allImages && allImages[index]) {
                currentImageIndex = index;
                const image = allImages[index];
                const title = `{{ $roomType->name }} - Ảnh ${index + 1}`;
                const description = image.is_main ? 'Ảnh chính' : `Ảnh ${index + 1}`;
                closeAllImagesModal();
                setTimeout(() => {
                    viewImage(image.image_path, title, description);
                }, 100);
            }
        }

        // Navigation functions for future use
        function previousImage() {
            if (currentImageIndex > 0) {
                currentImageIndex--;
                const image = allImages[currentImageIndex];
                const title = `{{ $roomType->name }} - Ảnh ${currentImageIndex + 1}`;
                const description = image.is_main ? 'Ảnh chính' : `Ảnh ${currentImageIndex + 1}`;

                document.getElementById('viewerImage').src = image.image_path;
                document.getElementById('viewerTitle').textContent = title;
                document.getElementById('viewerDescription').textContent = description;
            }
        }

        function nextImage() {
            if (currentImageIndex < allImages.length - 1) {
                currentImageIndex++;
                const image = allImages[currentImageIndex];
                const title = `{{ $roomType->name }} - Ảnh ${currentImageIndex + 1}`;
                const description = image.is_main ? 'Ảnh chính' : `Ảnh ${currentImageIndex + 1}`;

                document.getElementById('viewerImage').src = image.image_path;
                document.getElementById('viewerTitle').textContent = title;
                document.getElementById('viewerDescription').textContent = description;
            }
        }

        // Image Manager Functions
        function openImageManager(roomTypeId) {
            // TODO: Implement image management modal
            alert(`Chức năng quản lý ảnh cho loại phòng ID: ${roomTypeId} đang được phát triển!`);
        }

        // Thêm function hiển thị loading overlay
        function showLoadingOverlay(message = 'Đang tải...') {
            // Tạo overlay nếu chưa có



            let overlay = document.getElementById('pageLoadingOverlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'pageLoadingOverlay';
                overlay.className = 'fixed h-full inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
                overlay.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3 shadow-xl">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">${message}</span>
                    </div>
                `;
                document.body.appendChild(overlay);
            }

            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Cập nhật function trong Quick Actions sidebar
        function openAmenityManager(roomTypeId) {
            showLoadingOverlay('Đang chuyển đến quản lý tiện ích...');
            setTimeout(() => {
                window.location.href = `/admin/room-types/${roomTypeId}/amenities`;
                console.log(`Redirecting to /admin/room-types/${roomTypeId}/amenities`);

            }, 500);
        }

        // Room Management Functions
        function viewRoomsList(roomTypeId) {
            // TODO: Navigate to rooms list filtered by room type
            window.location.href = `/admin/rooms?room_type_id=${roomTypeId}`;
        }

        function duplicateRoomType(roomTypeId) {
            if (confirm('Bạn có chắc chắn muốn sao chép loại phòng này không?')) {
                // TODO: Implement room type duplication
                alert(`Chức năng sao chép loại phòng ID: ${roomTypeId} đang được phát triển!`);
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Close modals when clicking outside
            document.getElementById('imageViewerModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageViewer();
                }
            });

            document.getElementById('allImagesModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAllImagesModal();
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                const imageViewerOpen = !document.getElementById('imageViewerModal').classList.contains(
                    'hidden');
                const allImagesOpen = !document.getElementById('allImagesModal').classList.contains(
                    'hidden');

                if (e.key === 'Escape') {
                    if (imageViewerOpen) {
                        closeImageViewer();
                    } else if (allImagesOpen) {
                        closeAllImagesModal();
                    }
                }

                if (imageViewerOpen) {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        previousImage();
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        nextImage();
                    }
                }
            });

            // Show navigation arrows if there are multiple images
            if (allImages && allImages.length > 1) {
                document.getElementById('prevImageBtn').classList.remove('hidden');
                document.getElementById('nextImageBtn').classList.remove('hidden');
            }

            // Xử lý lỗi ảnh
            document.querySelectorAll('img').forEach(function(img) {
                img.addEventListener('error', function() {
                    this.src = '/images/placeholder-room.jpg';
                    this.alt = 'Ảnh không tải được';
                });
            });
        });

        // Utility function to preload images for better performance
        function preloadImages() {
            if (allImages && allImages.length > 0) {
                allImages.forEach(function(image) {
                    const img = new Image();
                    img.src = image.image_path;
                });
            }
        }

        // Preload images when page loads
        window.addEventListener('load', preloadImages);

        // Touch/swipe support for mobile devices
        let touchStartX = 0;
        let touchEndX = 0;

        document.getElementById('imageViewerModal').addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.getElementById('imageViewerModal').addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next image
                    nextImage();
                } else {
                    // Swipe right - previous image
                    previousImage();
                }
            }
        }

        // Image error handling
        function handleImageError(img) {
            img.src = '/images/placeholder-room.jpg'; // Fallback image
            img.alt = 'Ảnh không tải được';
        }

        // Add error handling to all images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(function(img) {
                img.addEventListener('error', function() {
                    handleImageError(this);
                });
            });
        });
    </script>
    <!-- JavaScript for Image Modals -->
    <script>
        // Single Image Modal Functions
        function viewSingleImage(imageUrl, title) {
            const modal = document.getElementById('singleImageModal');
            const image = document.getElementById('singleImageSrc');
            const titleElement = document.getElementById('singleImageTitle');

            image.src = imageUrl;
            titleElement.textContent = title;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSingleImageModal() {
            const modal = document.getElementById('singleImageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // All Images Modal Functions
        function openAllImagesModal() {
            const modal = document.getElementById('allImagesModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAllImagesModal() {
            const modal = document.getElementById('allImagesModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function viewSingleImageFromGallery(imageUrl, title) {
            // Close gallery modal first
            closeAllImagesModal();

            // Then open single image modal
            setTimeout(() => {
                viewSingleImage(imageUrl, title);
            }, 100);
        }

        // Image Manager Function (placeholder)
        function openImageManager(roomTypeId) {
            alert(`Chức năng quản lý ảnh cho loại phòng ID: ${roomTypeId} đang được phát triển!`);
        }

        // Keyboard Event Listeners
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close single image modal if open
                const singleModal = document.getElementById('singleImageModal');
                if (!singleModal.classList.contains('hidden')) {
                    closeSingleImageModal();
                    return;
                }

                // Close all images modal if open
                const allModal = document.getElementById('allImagesModal');
                if (!allModal.classList.contains('hidden')) {
                    closeAllImagesModal();
                    return;
                }
            }
        });

        // Click outside to close modals
        document.getElementById('singleImageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSingleImageModal();
            }
        });

        document.getElementById('allImagesModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAllImagesModal();
            }
        });

        // Image Error Handling
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(function(img) {
                img.addEventListener('error', function() {
                    this.src =
                        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik02MCA2MEgxNDBWMTQwSDYwVjYwWiIgZmlsbD0iI0Q1RDlERCIvPgo8cGF0aCBkPSJNODAgODBIMTIwVjEyMEg4MFY4MFoiIGZpbGw9IiNBN0E5QUMiLz4KPC9zdmc+';
                    this.alt = 'Ảnh không tải được';
                });
            });
        });

        function openImageManager(roomTypeId) {
            window.location.href = `/admin/room-types/${roomTypeId}/images`;
        }

        function openServiceManager(roomTypeId) {
            window.location.href = '{{ route("admin.room-types.services", [":id"]) }}'.replace(':id', roomTypeId);
        }

        function openPackageModal(roomTypeId, packageId = null, mode = 'create') {
            const form = document.getElementById('packageForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodInput = document.getElementById('packageMethod');

            // Đặt tiêu đề và trạng thái mặc định
            modalTitle.textContent = mode === 'create' ? 'Thêm gói dịch vụ' : 'Sửa gói dịch vụ';
            document.getElementById('packageId').value = packageId || '';
            document.getElementById('packageName').value = '';
            document.getElementById('packagePrice').value = '';
            document.getElementById('packageIncludeAll').value = '0';
            document.getElementById('packageDescription').value = '';
            document.getElementById('packageIsActive').value = '1';
            methodInput.value = mode === 'create' ? 'POST' : 'PUT';

            if (mode === 'edit' && packageId) {
                fetch(`/admin/room-types/packages/${packageId}/edit`)
                    .then(response => {
                        if (!response.ok) throw new Error('Lỗi phản hồi từ server');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Dữ liệu từ server (edit):', data);
                        document.getElementById('packageId').value = data.package_id;
                        document.getElementById('packageName').value = data.name || '';
                        document.getElementById('packagePrice').value = data.price_modifier_vnd || '';
                        document.getElementById('packageIncludeAll').value = data.include_all_services ? '1' : '0';
                        document.getElementById('packageDescription').value = data.description || '';
                        document.getElementById('packageIsActive').value = data.is_active ? '1' : '0';
                        form.action = `/admin/room-types/packages/${data.package_id}`;
                        document.getElementById('packageModal').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    })
                    .catch(error => console.error('Lỗi khi tải dữ liệu gói:', error));
            } else {
                form.action = `/admin/room-types/${roomTypeId}/packages`;
                document.getElementById('packageModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closePackageModal() {
            const modal = document.getElementById('packageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('packageModal');
            const modalContent = modal.querySelector('.modal-content');

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closePackageModal();
                }
            });

            modalContent.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            document.getElementById('packageForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                console.log('Dữ liệu gửi đi:', Object.fromEntries(formData));
                console.log('Action URL:', this.action);
                console.log('Method:', this.querySelector('[name="_method"]').value);

                fetch(this.action, {
                    method: 'POST', // Sử dụng POST với _method để xử lý PUT
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Phản hồi từ server:', data);
                    if (data.success) {
                        closePackageModal();
                        location.reload();
                    } else {
                        alert(data.message || 'Cập nhật thất bại');
                    }
                })
                .catch(error => console.error('Lỗi khi gửi request:', error));
            });
        });

        // Package Management Functions
        function togglePackageDetails(packageId) {
            const detailsRow = document.getElementById(`package-details-${packageId}`);
            const allDetails = document.querySelectorAll('[id^="package-details-"]');
            allDetails.forEach(detail => {
                if (detail.id !== `package-details-${packageId}`) {
                    detail.classList.add('hidden');
                }
            });
            detailsRow.classList.toggle('hidden');
            if (!detailsRow.classList.contains('hidden')) {
                setTimeout(() => {
                    detailsRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        }

        function togglePackageDropdown(packageId) {
            const dropdown = document.getElementById(`dropdown-package-menu-${packageId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-package-menu-"]');
            allDropdowns.forEach(menu => {
                if (menu.id !== `dropdown-package-menu-${packageId}`) {
                    menu.classList.add('hidden');
                }
            });
            dropdown.classList.toggle('hidden');
        }

        function closePackageDropdown(packageId) {
            const dropdown = document.getElementById(`dropdown-package-menu-${packageId}`);
            dropdown.classList.add('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-package-menu-"]');
            const buttons = document.querySelectorAll('[id^="dropdown-package-button-"]');
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

        function managePackageServices(roomTypeId, packageId) {
            showLoadingOverlay('Đang chuyển đến quản lý dịch vụ...');
            setTimeout(() => {
                window.location.href = `/admin/room-types/${roomTypeId}/packages/${packageId}/manage-services`;
            }, 500);
        }

        function togglePackageStatus(packageId, isActive, packageName) {
            Swal.fire({
                title: isActive ? 'Ngưng hoạt động gói này??????' : 'Kích hoạt gói này?',
                text: `Bạn có chắc muốn ${isActive ? 'ngưng hoạt động' : 'kích hoạt'} gói: ${packageName}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: isActive ? 'Ngưng hoạt động' : 'Kích hoạt',
                cancelButtonText: 'Hủy',
                confirmButtonColor: isActive ? '#d33' : '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/room-types/packages/${packageId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: data.message || 'Đã cập nhật trạng thái.',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false
                            });
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            Swal.fire('Lỗi', data.message || 'Không thể cập nhật trạng thái.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Lỗi', 'Không thể kết nối server.', 'error'));
                }
            });
        }

        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-app-layout>


