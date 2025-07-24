<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table before seeding
        DB::table('hotels')->delete();

        DB::table('hotels')->insert([
            'name' => 'LavishStay',
            'description' => 'Tọa lạc tại trung tâm thành phố, LavishStay là một khách sạn sang trọng và hiện đại, mang đến cho du khách trải nghiệm nghỉ dưỡng đẳng cấp với tầm nhìn toàn cảnh thành phố.',
            'address' => 'Số 27, Đường Trần Phú, Phường Điện Biên',
            'city' => 'Thanh Hóa',
            'country' => 'Việt Nam',
            'phone_number' => '02378936888',
            'email' => 'reservations@lavishstay.com',
            'website' => 'https://www.lavishstay.com',
            'check_in_time' => '14:00',
            'check_out_time' => '12:00',
            'star_rating' => 5,
            'amenities' => json_encode([
                "Hồ bơi bốn mùa",
                "Nhà hàng & Quầy bar",
                "YHI Spa",
                "Trung tâm thể dục (Gym)",
                "Khu vui chơi trẻ em",
                "Phòng họp & sự kiện",
                "Wi-Fi miễn phí",
                "Dịch vụ phòng 24/7"
            ]),
            'policies' => json_encode([
                "Giấy tờ tùy thân" => "Du khách cần xuất trình CMND/CCCD hoặc Hộ chiếu khi nhận phòng.",
                "Vật nuôi" => "Không cho phép mang theo vật nuôi.",
                "Trẻ em" => "Chính sách phụ thu có thể được áp dụng cho trẻ em."
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}