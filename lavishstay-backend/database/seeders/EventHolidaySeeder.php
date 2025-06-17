<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventHolidaySeeder extends Seeder
{
    public function run()
    {
        // Insert Events
        DB::table('events')->insert([
            [
                'event_id' => 1,
                'name' => 'Tết Nguyên Đán 2024',
                'description' => 'Lễ Tết cổ truyền Việt Nam',
                'start_date' => '2024-02-10',
                'end_date' => '2024-02-17',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 2,
                'name' => 'Lễ 30/4 - 1/5',
                'description' => 'Ngày giải phóng miền Nam và Quốc tế lao động',
                'start_date' => '2024-04-30',
                'end_date' => '2024-05-01',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 3,
                'name' => 'Quốc Khánh 2/9',
                'description' => 'Ngày Quốc khánh Việt Nam',
                'start_date' => '2024-09-02',
                'end_date' => '2024-09-02',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert Holidays
        DB::table('holidays')->insert([
            [
                'holiday_id' => 1,
                'name' => 'Giỗ Tổ Hùng Vương',
                'description' => 'Ngày giỗ tổ Hùng Vương',
                'date' => '2024-04-18',
                'is_recurring' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'holiday_id' => 2,
                'name' => 'Rằm tháng 7',
                'description' => 'Lễ Vu Lan',
                'date' => '2024-08-22',
                'is_recurring' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'holiday_id' => 3,
                'name' => 'Tết Trung Thu',
                'description' => 'Tết Trung Thu',
                'date' => '2024-09-17',
                'is_recurring' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
