<?php

namespace Database\Seeders;

use App\Models\MediaFile;
use Illuminate\Database\Seeder;

class MediaFileSeeder extends Seeder
{
    public function run()
    {
        $mediaFiles = [
            [
                'filename' => 'hotel-lobby.jpg',
                'filepath' => '/storage/media/hotel-lobby.jpg',
                'alt_text' => 'Sảnh khách sạn sang trọng với thiết kế hiện đại',
                'title' => 'Sảnh Khách Sạn LavishStay',
                'type' => 'image/jpeg',
                'size' => 2048576,
                'used_in' => 'news',
            ],
            [
                'filename' => 'deluxe-room.jpg',
                'filepath' => '/storage/media/deluxe-room.jpg',
                'alt_text' => 'Phòng deluxe với view biển tuyệt đẹp',
                'title' => 'Phòng Deluxe Sea View',
                'type' => 'image/jpeg',
                'size' => 1876543,
                'used_in' => 'news',
            ],
            [
                'filename' => 'restaurant-dining.jpg',
                'filepath' => '/storage/media/restaurant-dining.jpg',
                'alt_text' => 'Nhà hàng với không gian ấm cúng và món ăn tinh tế',
                'title' => 'Nhà Hàng LavishStay',
                'type' => 'image/jpeg',
                'size' => 1654321,
                'used_in' => 'news',
            ],
            [
                'filename' => 'swimming-pool.jpg',
                'filepath' => '/storage/media/swimming-pool.jpg',
                'alt_text' => 'Hồ bơi infinity với view toàn cảnh thành phố',
                'title' => 'Hồ Bơi Infinity',
                'type' => 'image/jpeg',
                'size' => 2234567,
                'used_in' => 'news',
            ],
            [
                'filename' => 'spa-treatment.jpg',
                'filepath' => '/storage/media/spa-treatment.jpg',
                'alt_text' => 'Phòng spa với không gian thư giãn và massage',
                'title' => 'Spa & Massage',
                'type' => 'image/jpeg',
                'size' => 1987654,
                'used_in' => 'news',
            ],
            [
                'filename' => 'beach-view.jpg',
                'filepath' => '/storage/media/beach-view.jpg',
                'alt_text' => 'Bãi biển tuyệt đẹp với cát trắng và nước trong xanh',
                'title' => 'Bãi Biển Paradise',
                'type' => 'image/jpeg',
                'size' => 2345678,
                'used_in' => 'news',
            ],
            [
                'filename' => 'conference-room.jpg',
                'filepath' => '/storage/media/conference-room.jpg',
                'alt_text' => 'Phòng hội nghị hiện đại với thiết bị công nghệ cao',
                'title' => 'Phòng Hội Nghị',
                'type' => 'image/jpeg',
                'size' => 1765432,
                'used_in' => 'news',
            ],
            [
                'filename' => 'fitness-center.jpg',
                'filepath' => '/storage/media/fitness-center.jpg',
                'alt_text' => 'Phòng gym với thiết bị tập luyện hiện đại',
                'title' => 'Trung Tâm Thể Dục',
                'type' => 'image/jpeg',
                'size' => 1456789,
                'used_in' => 'news',
            ],
        ];

        foreach ($mediaFiles as $mediaFile) {
            MediaFile::create($mediaFile);
        }
    }
}
