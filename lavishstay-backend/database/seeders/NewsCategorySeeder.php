<?php

namespace Database\Seeders;

use App\Models\News\NewsCategory;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Tin Tức Khách Sạn',
                'slug' => 'tin-tuc-khach-san',
                'description' => 'Các tin tức mới nhất về khách sạn và dịch vụ',
            ],
            [
                'name' => 'Ưu Đãi & Khuyến Mãi',
                'slug' => 'uu-dai-khuyen-mai',
                'description' => 'Thông tin về các chương trình ưu đãi, khuyến mãi đặc biệt',
            ],
            [
                'name' => 'Hướng Dẫn Du Lịch',
                'slug' => 'huong-dan-du-lich',
                'description' => 'Các bài viết hướng dẫn du lịch, địa điểm tham quan',
            ],
            [
                'name' => 'Sự Kiện',
                'slug' => 'su-kien',
                'description' => 'Thông tin về các sự kiện, lễ hội, hoạt động tại khách sạn',
            ],
            [
                'name' => 'Ẩm Thực',
                'slug' => 'am-thuc',
                'description' => 'Giới thiệu về ẩm thực, nhà hàng và các món ăn đặc sắc',
            ],
            [
                'name' => 'Tips & Tricks',
                'slug' => 'tips-tricks',
                'description' => 'Các mẹo và kinh nghiệm hữu ích cho khách du lịch',
            ],
        ];

        foreach ($categories as $category) {
            NewsCategory::create($category);
        }
    }
}
