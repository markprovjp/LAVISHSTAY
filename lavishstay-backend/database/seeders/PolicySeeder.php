<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CancellationPolicy;
use App\Models\DepositPolicy;
use App\Models\CheckOutPolicy;
use App\Models\PolicyApplication;

class PolicySeeder extends Seeder
{
    public function run()
    {
        // Tạo Cancellation Policies
        $cancelPolicy1 = CancellationPolicy::create([
            'name' => 'Hủy miễn phí 7 ngày',
            'free_cancellation_days' => 7,
            'penalty_percentage' => 0,
            'penalty_fixed_amount_vnd' => 200000,
            'description' => 'Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k',
            'is_active' => true,
            'priority' => 10
        ]);

        $cancelPolicy2 = CancellationPolicy::create([
            'name' => 'Hủy miễn phí 3 ngày - Lễ tết',
            'free_cancellation_days' => 3,
            'penalty_percentage' => 50,
            'penalty_fixed_amount_vnd' => 0,
            'description' => 'Áp dụng cho ngày lễ tết, hủy trước 3 ngày',
            'is_active' => true,
            'priority' => 20
        ]);

        // Tạo Deposit Policies
        $depositPolicy1 = DepositPolicy::create([
            'name' => 'Đặt cọc 30%',
            'deposit_percentage' => 30,
            'deposit_fixed_amount_vnd' => 0,
            'description' => 'Đặt cọc 30% giá trị booking',
            'is_active' => true
        ]);

        $depositPolicy2 = DepositPolicy::create([
            'name' => 'Đặt cọc 50% - Lễ tết',
            'deposit_percentage' => 50,
            'deposit_fixed_amount_vnd' => 0,
            'description' => 'Đặt cọc 50% cho ngày lễ tết',
            'is_active' => true
        ]);

        // Tạo Check Out Policies
        $checkoutPolicy1 = CheckOutPolicy::create([
            'name' => 'Check-out tiêu chuẩn',
            'early_check_out_fee_vnd' => 0,
            'late_check_out_fee_vnd' => 500000,
            'early_check_out_max_hours' => 4,
            'late_check_out_max_hours' => 2,
            'description' => 'Check-out tiêu chuẩn 12:00',
            'is_active' => true
        ]);

        // Tạo Policy Applications
        
        // Chính sách mặc định cho tất cả room types
        PolicyApplication::create([
            'room_type_id' => null, // Áp dụng cho tất cả
            'policy_type' => 'cancellation',
            'policy_id' => $cancelPolicy1->policy_id,
            'applies_to_holiday' => false,
            'min_occupancy_percent' => null,
            'max_occupancy_percent' => null,
            'min_days_before_checkin' => null,
            'date_from' => null,
            'date_to' => null,
            'priority' => 10,
            'is_active' => true
        ]);

        // Chính sách cho ngày lễ
        PolicyApplication::create([
            'room_type_id' => null,
            'policy_type' => 'cancellation',
            'policy_id' => $cancelPolicy2->policy_id,
            'applies_to_holiday' => true,
            'min_occupancy_percent' => null,
            'max_occupancy_percent' => null,
            'min_days_before_checkin' => null,
            'date_from' => null,
            'date_to' => null,
            'priority' => 20, // Priority cao hơn
            'is_active' => true
        ]);

        // Deposit policies
        PolicyApplication::create([
            'room_type_id' => null,
            'policy_type' => 'deposit',
            'policy_id' => $depositPolicy1->policy_id,
            'applies_to_holiday' => false,
            'priority' => 10,
            'is_active' => true
        ]);

        PolicyApplication::create([
            'room_type_id' => null,
            'policy_type' => 'deposit',
            'policy_id' => $depositPolicy2->policy_id,
            'applies_to_holiday' => true,
            'priority' => 20,
            'is_active' => true
        ]);

        // Check out policies
        PolicyApplication::create([
            'room_type_id' => null,
            'policy_type' => 'check_out',
            'policy_id' => $checkoutPolicy1->policy_id,
            'applies_to_holiday' => null,
            'priority' => 10,
            'is_active' => true
        ]);
    }
}
