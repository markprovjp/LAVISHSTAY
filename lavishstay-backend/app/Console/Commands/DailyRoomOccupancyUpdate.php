<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PricingService;

class DailyRoomOccupancyUpdate extends Command
{
    protected $signature = 'room-occupancy:daily-update';
    protected $description = 'Tạo/cập nhật dữ liệu occupancy cho từng loại phòng mỗi ngày';

    public function handle()
    {
        app(PricingService::class)->dailyUpdateRoomOccupancy();
        $this->info('Đã cập nhật dữ liệu occupancy cho ngày hôm nay!');
    }
}