@props(['alerts' => []])

<div class="col-span-full xl:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Cảnh báo & Thông báo</h2>
    </header>
    <div class="p-3">
        <div class="space-y-3">
            @if(($alerts['overdue_payments'] ?? 0) > 0)
            <x-dashboard.alert-card 
                type="error"
                title="thanh toán quá hạn"
                message="Cần xử lý ngay"
                :count="$alerts['overdue_payments']"
            />
            @endif

            @if(($alerts['rooms_need_cleaning'] ?? 0) > 0)
            <x-dashboard.alert-card 
                type="warning"
                title="phòng cần dọn dẹp"
                message="Chuẩn bị cho khách tiếp theo"
                :count="$alerts['rooms_need_cleaning']"
            />
            @endif

            @if(($alerts['arriving_tomorrow'] ?? 0) > 0)
            <x-dashboard.alert-card 
                type="info"
                title="khách đến ngày mai"
                message="Chuẩn bị đón tiếp"
                :count="$alerts['arriving_tomorrow']"
            />
            @endif

            @if(($alerts['maintenance_rooms'] ?? 0) > 0)
            <x-dashboard.alert-card 
                type="warning"
                title="phòng đang bảo trì"
                message="Không thể cho thuê"
                :count="$alerts['maintenance_rooms']"
            />
            @endif

            @if(empty(array_filter($alerts)))
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-green-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Không có cảnh báo nào</p>
            </div>
            @endif
        </div>
    </div>
</div>
