<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8 flex justify-between items-center">
            <div class=" space-x-3 ">
                
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Trang phân tích</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Tạo một chính sách trả phòng mới cho hệ thống đặt phòng</p>

            </div>
            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Back Button -->
                <a href="{{ route('admin.checkout-policies') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white">
                    <i class="fa-solid fa-backward"></i><span class="ml-2">Back to List</span>
                </a>
            </div>
        </div>

        
    </div>
</x-app-layout>