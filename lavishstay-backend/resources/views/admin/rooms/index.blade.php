<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Tất cả các phòng</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <!-- Filter button -->
                <x-dropdown-filter align="right" />

                <!-- Datepicker built with flatpickr -->
                <x-datepicker />

<<<<<<< HEAD
=======

                
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
                <!-- Add view button -->
                <button
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Add View</span>
                </button>

            </div>

        </div>

        <!-- Cards -->
        <div class="grid grid-cols-12 gap-6">
            @foreach ($allrooms as $room)
                <div
                    class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-xs rounded-xl">
                    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ $room->name }}</h2>
                    </header>
                    <div class=" px-5 py-4 grow flex flex-col justify-center">
                        <div><span>Tổng số phòng:</span> <span class="text-gray-800 dark:text-gray-100 font-semibold">
                                {{ $room->rooms_count }}</span></div>
                        <div><span>Số phòng trống:</span> <span class="text-gray-800 dark:text-gray-100 font-semibold">
                                {{ $room->rooms_count - $room->bookings_count }}</span></div>
                        <div><span>Số phòng đã đặt:</span> <span class="text-gray-800 dark:text-gray-100 font-semibold">
                                {{ $room->bookings_count }}</span></div>
                      
                            <button class="btn mt-4 cursor-pointer bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                                <a href="{{ route('admin.rooms.by-type', $room->room_type_id) }}" class="flex flex-center justify-center w-full ">
                                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                                    </svg>
                                    <span class="max-xs:sr-only content-center">Xem chi tiết</span>
                                </a>
                            </button>

                      
                    </div>
                    
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
