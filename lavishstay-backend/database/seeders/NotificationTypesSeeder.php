<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationType;

class NotificationTypesSeeder extends Seeder
{
    public function run()
    {
        $notificationTypes = [
            // Booking related
            [
                'name' => 'booking_new',
                'title' => 'Đặt phòng mới',
                'message_template' => 'Có đặt phòng mới #{booking_id} từ khách hàng {customer_name}. Phòng: {room_number}, Check-in: {checkin_date}',
                'priority' => 'high',
                'icon' => '🏨',
                'color' => '#10B981',
                'target_roles' => ['admin', 'hotel_manager', 'receptionist'],
            ],
            [
                'name' => 'booking_cancelled',
                'title' => 'Hủy đặt phòng',
                'message_template' => 'Đặt phòng #{booking_id} đã bị hủy bởi {customer_name}. Lý do: {reason}',
                'priority' => 'normal',
                'icon' => '❌',
                'color' => '#EF4444',
                'target_roles' => ['admin', 'hotel_manager', 'receptionist'],
            ],
            [
                'name' => 'booking_modified',
                'title' => 'Thay đổi đặt phòng',
                'message_template' => 'Đặt phòng #{booking_id} đã được thay đổi. Khách hàng: {customer_name}',
                'priority' => 'normal',
                'icon' => '✏️',
                'color' => '#F59E0B',
                'target_roles' => ['admin', 'hotel_manager', 'receptionist'],
            ],
            [
                'name' => 'checkin_reminder',
                'title' => 'Nhắc nhở check-in',
                'message_template' => 'Khách hàng {customer_name} sẽ check-in hôm nay. Phòng: {room_number}',
                'priority' => 'normal',
                'icon' => '🔔',
                'color' => '#3B82F6',
                'target_roles' => ['receptionist', 'housekeeping'],
            ],
            [
                'name' => 'checkout_completed',
                'title' => 'Hoàn tất check-out',
                'message_template' => 'Khách hàng {customer_name} đã check-out khỏi phòng {room_number}',
                'priority' => 'normal',
                'icon' => '🚪',
                'color' => '#6B7280',
                'target_roles' => ['receptionist', 'housekeeping'],
            ],

            // Payment related
            [
                'name' => 'payment_success',
                'title' => 'Thanh toán thành công',
                'message_template' => 'Thanh toán thành công {amount} VND cho đặt phòng #{booking_id}',
                'priority' => 'normal',
                'icon' => '💰',
                'color' => '#10B981',
                'target_roles' => ['admin', 'hotel_manager', 'finance'],
            ],
            [
                'name' => 'payment_failed',
                'title' => 'Thanh toán thất bại',
                'message_template' => 'Thanh toán thất bại cho đặt phòng #{booking_id}. Số tiền: {amount} VND. Lý do: {reason}',
                'priority' => 'high',
                'icon' => '❗',
                'color' => '#EF4444',
                'target_roles' => ['admin', 'finance', 'receptionist'],
            ],
            [
                'name' => 'refund_requested',
                'title' => 'Yêu cầu hoàn tiền',
                'message_template' => 'Khách hàng {customer_name} yêu cầu hoàn tiền {amount} VND cho đặt phòng #{booking_id}',
                'priority' => 'high',
                'icon' => '💸',
                'color' => '#F59E0B',
                'target_roles' => ['admin', 'hotel_manager', 'finance'],
            ],

            // Room/Housekeeping related
            [
                'name' => 'room_maintenance',
                'title' => 'Bảo trì phòng',
                'message_template' => 'Phòng {room_number} cần bảo trì. Vấn đề: {issue}',
                'priority' => 'high',
                'icon' => '🔧',
                'color' => '#F59E0B',
                'target_roles' => ['admin', 'hotel_manager', 'housekeeping'],
            ],
            [
                'name' => 'room_cleaning_urgent',
                'title' => 'Dọn phòng khẩn cấp',
                'message_template' => 'Phòng {room_number} cần dọn dẹp khẩn cấp trước {time}',
                'priority' => 'urgent',
                'icon' => '🧹',
                'color' => '#EF4444',
                'target_roles' => ['housekeeping', 'receptionist'],
            ],

            // Review related
            [
                'name' => 'review_new',
                'title' => 'Đánh giá mới',
                'message_template' => 'Có đánh giá mới từ khách hàng {customer_name}. Rating: {rating}/5',
                'priority' => 'normal',
                'icon' => '⭐',
                'color' => '#F59E0B',
                'target_roles' => ['marketing', 'hotel_manager'],
            ],
            [
                'name' => 'review_negative',
                'title' => 'Đánh giá tiêu cực',
                'message_template' => 'Đánh giá tiêu cực ({rating}/5) từ khách hàng {customer_name}. Cần xử lý ngay!',
                'priority' => 'urgent',
                'icon' => '😞',
                'color' => '#EF4444',
                'target_roles' => ['admin', 'hotel_manager', 'marketing'],
            ],

            // System related
            [
                'name' => 'system_error',
                'title' => 'Lỗi hệ thống',
                'message_template' => 'Phát hiện lỗi hệ thống: {error_message}',
                'priority' => 'urgent',
                'icon' => '🚨',
                'color' => '#EF4444',
                'target_roles' => ['admin'],
            ],
            [
                'name' => 'system_maintenance',
                'title' => 'Bảo trì hệ thống',
                'message_template' => 'Hệ thống sẽ bảo trì từ {start_time} đến {end_time}',
                'priority' => 'high',
                'icon' => '⚙️',
                'color' => '#6B7280',
                'target_roles' => ['admin', 'hotel_manager'],
            ],

            // Staff related
            [
                'name' => 'staff_shift_reminder',
                'title' => 'Nhắc nhở ca làm việc',
                'message_template' => 'Ca làm việc của bạn sẽ bắt đầu trong {minutes} phút',
                'priority' => 'normal',
                'icon' => '⏰',
                'color' => '#3B82F6',
                'target_roles' => ['receptionist', 'housekeeping'],
            ],
        ];

        foreach ($notificationTypes as $type) {
            NotificationType::create($type);
        }
    }
}