<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'SUMMER10',
                'type' => 'percent',
                'value' => 10,
                'description' => 'Giảm 10% cho mùa hè 2025',
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addMonths(3),
                'usage_limit' => 1000,
                'per_user_limit' => 2,
                'min_booking_amount_vnd' => 500000,
                'stackable' => false,
                'active' => true,
            ],
            [
                'code' => 'WELCOME50K',
                'type' => 'fixed',
                'value' => 50000,
                'description' => 'Giảm 50,000 VND cho khách hàng mới',
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addYear(),
                'usage_limit' => 500,
                'per_user_limit' => 1,
                'min_booking_amount_vnd' => 300000,
                'stackable' => true,
                'active' => true,
            ],
            [
                'code' => 'WEEKEND15',
                'type' => 'percent',
                'value' => 15,
                'description' => 'Giảm 15% cho booking cuối tuần',
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addMonths(6),
                'usage_limit' => null, // Unlimited
                'per_user_limit' => 4,
                'min_booking_amount_vnd' => 1000000,
                'stackable' => false,
                'active' => true,
            ],
            [
                'code' => 'DELUXE100K',
                'type' => 'fixed',
                'value' => 100000,
                'description' => 'Giảm 100,000 VND cho phòng Deluxe',
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addMonths(2),
                'usage_limit' => 200,
                'per_user_limit' => 1,
                'min_booking_amount_vnd' => 800000,
                'applicable_room_type_ids' => [1], // Deluxe room type ID
                'stackable' => true,
                'active' => true,
            ],
            [
                'code' => 'LOYALTY20',
                'type' => 'percent',
                'value' => 20,
                'description' => 'Giảm 20% cho khách hàng thân thiết',
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addYear(),
                'usage_limit' => 100,
                'per_user_limit' => 3,
                'min_booking_amount_vnd' => 1500000,
                'stackable' => false,
                'active' => true,
            ],
            [
                'code' => 'EXPIRED10',
                'type' => 'percent',
                'value' => 10,
                'description' => 'Mã đã hết hạn (để test)',
                'start_at' => Carbon::now()->subMonth(),
                'end_at' => Carbon::now()->subWeek(),
                'usage_limit' => 100,
                'per_user_limit' => 1,
                'min_booking_amount_vnd' => 500000,
                'stackable' => false,
                'active' => true,
            ],
            [
                'code' => 'INACTIVE25',
                'type' => 'percent',
                'value' => 25,
                'description' => 'Mã không active (để test)',
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addMonth(),
                'usage_limit' => 50,
                'per_user_limit' => 1,
                'min_booking_amount_vnd' => 1000000,
                'stackable' => false,
                'active' => false,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }

        $this->command->info('Đã tạo ' . count($coupons) . ' mã giảm giá mẫu');
    }
}
