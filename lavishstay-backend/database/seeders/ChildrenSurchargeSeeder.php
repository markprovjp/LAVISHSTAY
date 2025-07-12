<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChildrenSurchargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('children_surcharges')->truncate();

        DB::table('children_surcharges')->insert([
            [
                'min_age' => 0,
                'max_age' => 5,
                'is_free' => true,
                'count_as_adult' => false,
                'surcharge_amount_vnd' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_age' => 6,
                'max_age' => 11,
                'is_free' => false,
                'count_as_adult' => false,
                'surcharge_amount_vnd' => 100000, // ví dụ: 100k phụ thu
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_age' => 12,
                'max_age' => 99,
                'is_free' => false,
                'count_as_adult' => true,
                'surcharge_amount_vnd' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
