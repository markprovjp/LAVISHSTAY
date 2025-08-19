<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Notifications\CheckoutCompletedNotification;

echo "=== NOTIFICATION SYSTEM TEST ===\n";

// 1. Tìm user để test
$user = User::first();
if (!$user) {
    echo "❌ No user found\n";
    exit(1);
}

echo "✅ Testing with user: {$user->name} (ID: {$user->id})\n";

// 2. Tạo fake booking object
$fakeBooking = (object)[
    'id' => 999,
    'booking_code' => 'TEST-' . date('Ymd-His')
];

echo "✅ Created fake booking: {$fakeBooking->booking_code}\n";

// 3. Gửi notification
try {
    $user->notify(new CheckoutCompletedNotification($fakeBooking));
    echo "✅ Notification sent successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to send notification: " . $e->getMessage() . "\n";
    exit(1);
}

// 4. Kiểm tra notification đã được lưu
$notificationCount = $user->notifications()->count();
$unreadCount = $user->unreadNotifications()->count();

echo "✅ Total notifications for user: {$notificationCount}\n";
echo "✅ Unread notifications: {$unreadCount}\n";

// 5. Lấy notification mới nhất
$latestNotification = $user->notifications()->first();
if ($latestNotification) {
    echo "✅ Latest notification data: " . json_encode($latestNotification->data, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "❌ No notifications found\n";
}

echo "\n=== TEST COMPLETED ===\n";
