<?php

namespace App\Helpers;

class SpecialRequestHelper
{
    /**
     * Get request type icon SVG
     */
    public static function getRequestTypeIcon(string $type): string
    {
        $icons = [
            'checkout_compensation' => '<svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>',
            'booking_reschedule' => '<svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>',
            'checkin_special' => '<svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>',
            'cancellation_special' => '<svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>',
            'extension_special' => '<svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>',
            'room_transfer' => '<svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>',
            'other' => '<svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>'
        ];

        return $icons[$type] ?? $icons['other'];
    }

    /**
     * Get request type icon background class
     */
    public static function getRequestTypeIconBg(string $type): string
    {
        $backgrounds = [
            'checkout_compensation' => 'bg-red-100 dark:bg-red-400/30',
            'booking_reschedule' => 'bg-blue-100 dark:bg-blue-400/30',
            'checkin_special' => 'bg-green-100 dark:bg-green-400/30',
            'cancellation_special' => 'bg-red-100 dark:bg-red-400/30',
            'extension_special' => 'bg-purple-100 dark:bg-purple-400/30',
            'room_transfer' => 'bg-orange-100 dark:bg-orange-400/30',
            'other' => 'bg-gray-100 dark:bg-gray-400/30'
        ];

        return $backgrounds[$type] ?? $backgrounds['other'];
    }

    /**
     * Get status badge CSS class
     */
    public static function getStatusBadgeClass(string $status): string
    {
        $classes = [
            'pending' => 'bg-yellow-100 dark:bg-yellow-400/30 text-yellow-800 dark:text-yellow-400',
            'approved' => 'bg-green-100 dark:bg-green-400/30 text-green-800 dark:text-green-400',
            'rejected' => 'bg-red-100 dark:bg-red-400/30 text-red-800 dark:text-red-400',
            'applied' => 'bg-blue-100 dark:bg-blue-400/30 text-blue-800 dark:text-blue-400',
        ];

        return $classes[$status] ?? 'bg-gray-100 dark:bg-gray-400/30 text-gray-800 dark:text-gray-400';
    }

    /**
     * Format currency amount
     */
    public static function formatCurrency(?float $amount): string
    {
        if ($amount === null) {
            return 'N/A';
        }
        
        return number_format($amount, 0, ',', '.') . ' ₫';
    }

    /**
     * Get status label in Vietnamese
     */
    public static function getStatusLabel(string $status): string
    {
        $labels = [
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            'applied' => 'Đã áp dụng',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Get request type label in Vietnamese
     */
    public static function getRequestTypeLabel(string $type): string
    {
        $labels = [
            'checkout_compensation' => 'Bồi thường checkout',
            'booking_reschedule' => 'Thay đổi lịch đặt',
            'checkin_special' => 'Check-in đặc biệt',
            'cancellation_special' => 'Hủy đặc biệt',
            'extension_special' => 'Gia hạn đặc biệt',
            'room_transfer' => 'Chuyển phòng',
            'other' => 'Khác',
        ];

        return $labels[$type] ?? $type;
    }

    /**
     * Get priority badge class and text
     */
    public static function getPriorityBadge(int $priority): array
    {
        if ($priority <= 1) {
            return [
                'class' => 'bg-red-100 dark:bg-red-400/30 text-red-800 dark:text-red-400',
                'text' => 'Khẩn cấp'
            ];
        } elseif ($priority <= 2) {
            return [
                'class' => 'bg-orange-100 dark:bg-orange-400/30 text-orange-800 dark:text-orange-400',
                'text' => 'Ưu tiên'
            ];
        } else {
            return [
                'class' => 'bg-gray-100 dark:bg-gray-400/30 text-gray-800 dark:text-gray-400',
                'text' => 'Bình thường'
            ];
        }
    }

    /**
     * Format date time for display
     */
    public static function formatDateTime($dateTime): string
    {
        if (!$dateTime) {
            return 'N/A';
        }

        if (is_string($dateTime)) {
            $dateTime = \Carbon\Carbon::parse($dateTime);
        }

        return $dateTime->format('d/m/Y H:i');
    }

    /**
     * Format date for display
     */
    public static function formatDate($date): string
    {
        if (!$date) {
            return 'N/A';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date->format('d/m/Y');
    }

    /**
     * Get time ago in Vietnamese
     */
    public static function timeAgo($dateTime): string
    {
        if (!$dateTime) {
            return 'N/A';
        }

        if (is_string($dateTime)) {
            $dateTime = \Carbon\Carbon::parse($dateTime);
        }

        return $dateTime->diffForHumans();
    }

    /**
     * Check if request is high priority
     */
    public static function isHighPriority(int $priority): bool
    {
        return $priority <= 2;
    }

    /**
     * Get attachment icon based on file type
     */
    public static function getAttachmentIcon(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'pdf':
                return '<svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 18h12V6l-4-4H4v16zm8-14l2 2h-2V4z"/>
                </svg>';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return '<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                </svg>';
            case 'doc':
            case 'docx':
                return '<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 18h12V6l-4-4H4v16zm8-14l2 2h-2V4z"/>
                </svg>';
            default:
                return '<svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 18h12V6l-4-4H4v16zm8-14l2 2h-2V4z"/>
                </svg>';
        }
    }
}