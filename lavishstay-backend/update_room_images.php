<?php

use Illuminate\Support\Facades\DB;

// Dữ liệu ảnh từ models.ts
$roomTypeImages = [
    'deluxe' => [
        "/images/room/Deluxe_Room/1.jpg",
        "/images/room/Deluxe_Room/2.jpg",
        "/images/room/Deluxe_Room/3.webp",
        "/images/room/Deluxe_Room/4.webp",
    ],
    'premium_corner' => [
        "/images/room/Premium_Corner_Room/1.jpg",
        "/images/room/Premium_Corner_Room/2.jpg",
        "/images/room/Premium_Corner_Room/3.webp",
        "/images/room/Premium_Corner_Room/4.jpg",
        "/images/room/Premium_Corner_Room/5.webp",
        "/images/room/Premium_Corner_Room/6.webp",
    ],
    'the_level_premium' => [
        "/images/room/The_Level_Premium_Room/1.jpg",
        "/images/room/The_Level_Premium_Room/2.webp",
        "/images/room/The_Level_Premium_Room/3.jpg",
        "/images/room/The_Level_Premium_Room/4.jpg",
        "/images/room/The_Level_Premium_Room/5.jpg",
        "/images/room/The_Level_Premium_Room/6.webp",
        "/images/room/The_Level_Premium_Room/7.jpg",
        "/images/room/The_Level_Premium_Room/8.webp",
    ],
    'suite' => [
        "/images/room/Suite/1.webp",
        "/images/room/Suite/2.webp",
        "/images/room/Suite/3.webp",
        "/images/room/Suite/4.webp",
        "/images/room/Suite/5.jpg",
        "/images/room/Suite/6.jpg",
        "/images/room/Suite/7.webp",
    ],
    'the_level_premium_corner' => [
        "/images/room/The_Level_Premium_Corner_Room/1.webp",
        "/images/room/The_Level_Premium_Corner_Room/2.jpg",
        "/images/room/The_Level_Premium_Corner_Room/3.webp",
        "/images/room/The_Level_Premium_Corner_Room/4.jpg",
        "/images/room/The_Level_Premium_Corner_Room/5.webp",
        "/images/room/The_Level_Premium_Corner_Room/6.jpg"
    ],
    'the_level_suite' => [
        "/images/room/The_Level_Suite_Room/1.webp",
        "/images/room/The_Level_Suite_Room/2.webp",
        "/images/room/The_Level_Suite_Room/3.webp",
        "/images/room/The_Level_Suite_Room/4.jpg",
        "/images/room/The_Level_Suite_Room/5.jpg",
        "/images/room/The_Level_Suite_Room/6.jpg",
        "/images/room/The_Level_Suite_Room/7.webp",
        "/images/room/The_Level_Suite_Room/8.jpg",
    ],
    'presidential_suite' => [
        "/images/room/Presidential_Suite/1.webp",
        "/images/room/Presidential_Suite/2.jpg",
        "/images/room/Presidential_Suite/3.jpg",
        "/images/room/Presidential_Suite/4.jpg",
        "/images/room/Presidential_Suite/5.jpg",
        "/images/room/Presidential_Suite/6.jpg",
        "/images/room/Presidential_Suite/7.jpg",
        "/images/room/Presidential_Suite/8.jpg",
        "/images/room/Presidential_Suite/9.jpg",
        "/images/room/Presidential_Suite/10.webp",
        "/images/room/Presidential_Suite/11.jpg",
        "/images/room/Presidential_Suite/12.webp",
        "/images/room/Presidential_Suite/13.jpg",
        "/images/room/Presidential_Suite/14.webp",
        "/images/room/Presidential_Suite/15.jpg",
        "/images/room/Presidential_Suite/16.jpg",
        "/images/room/Presidential_Suite/17.jpg",
        "/images/room/Presidential_Suite/18.jpg",
    ]
];

// Cập nhật database
foreach ($roomTypeImages as $roomCode => $images) {
    $imagesJson = json_encode($images);
    
    $updated = DB::table('room_types')
        ->where('room_code', $roomCode)
        ->update(['images' => $imagesJson]);
    
    if ($updated) {
        echo "✅ Đã cập nhật {$updated} room type với room_code: {$roomCode}\n";
        echo "   Số ảnh: " . count($images) . "\n";
    } else {
        echo "❌ Không tìm thấy room type với room_code: {$roomCode}\n";
    }
}

echo "\n🎉 Hoàn thành cập nhật ảnh cho tất cả room types!\n";
