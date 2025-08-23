<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Audit Logging Enabled
    |--------------------------------------------------------------------------
    |
    | This option controls whether audit logging is enabled for your application.
    | You may disable this for testing or when you don't need audit trails.
    |
    */
    'enabled' => env('AUDIT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Queue Enabled
    |--------------------------------------------------------------------------
    |
    | When enabled, audit logs will be processed asynchronously using queues.
    | This improves performance but requires a queue worker to be running.
    |
    */
    'queue_enabled' => env('AUDIT_QUEUE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Queue Connection
    |--------------------------------------------------------------------------
    |
    | The queue connection to use for processing audit logs.
    | Set to null to use the default queue connection.
    |
    */
    'queue_connection' => env('AUDIT_QUEUE_CONNECTION', null),

    /*
    |--------------------------------------------------------------------------
    | Queue Name
    |--------------------------------------------------------------------------
    |
    | The queue name to use for audit log jobs.
    |
    */
    'queue_name' => env('AUDIT_QUEUE_NAME', 'audit'),

    /*
    |--------------------------------------------------------------------------
    | Console Enabled
    |--------------------------------------------------------------------------
    |
    | Enable audit logging for console commands (artisan commands).
    | This might generate a lot of logs, so use with caution.
    |
    */
    'console_enabled' => env('AUDIT_CONSOLE_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Retention Days
    |--------------------------------------------------------------------------
    |
    | Number of days to keep audit logs. Set to 0 to keep logs indefinitely.
    | Old logs will be automatically cleaned up by the ProcessAuditLog job.
    |
    */
    'retention_days' => env('AUDIT_RETENTION_DAYS', 365),

    /*
    |--------------------------------------------------------------------------
    | Excluded Models
    |--------------------------------------------------------------------------
    |
    | Models that should be excluded from audit logging.
    | Add the full class name of models you don't want to audit.
    |
    */
    'excluded_models' => [
        'App\Models\AuditLog',
    // Notifications are stored in `user_notifications` with UUID ids.
    // Excluding them prevents audit_logs.model_id (integer) from failing on UUIDs.
    'App\Models\UserNotification',
        'App\Models\Session',
        'App\Models\Cache',
        'App\Models\FailedJob',
        'App\Models\PersonalAccessToken',
        'Laravel\Sanctum\PersonalAccessToken',
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Fields
    |--------------------------------------------------------------------------
    |
    | Fields that should be excluded from audit logging across all models.
    |
    */
    'excluded_fields' => [
        'updated_at',
        'created_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
        'password_reset_token',
        'email_verification_token',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sensitive Fields
    |--------------------------------------------------------------------------
    |
    | Fields that contain sensitive data and should be encrypted in audit logs.
    |
    */
    'sensitive_fields' => [
        'password',
        'password_confirmation',
        'token',
        'api_key',
        'secret',
        'private_key',
        'access_token',
        'refresh_token',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'stripe_secret',
        'paypal_secret',
    ],

    /*
    |--------------------------------------------------------------------------
    | Critical Actions
    |--------------------------------------------------------------------------
    |
    | Actions that are considered critical and should trigger notifications.
    |
    */
    'critical_actions' => [
        'delete',
        'bulk_delete',
        'restore',
    ],

    /*
    |--------------------------------------------------------------------------
    | Critical Models
    |--------------------------------------------------------------------------
    |
    | Models that are considered critical and any changes should trigger notifications.
    |
    */
    'critical_models' => [
        'User',
        'Payment',
        'Booking',
        'Role',
        'Permission',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Configuration for audit notifications.
    |
    */
    'notifications' => [
        'enabled' => env('AUDIT_NOTIFICATIONS_ENABLED', false),
        'channels' => ['mail', 'slack'], // Available: mail, slack, database
        'recipients' => [
            // Add email addresses for critical action notifications
        ],
        'slack_webhook' => env('AUDIT_SLACK_WEBHOOK'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Translations
    |--------------------------------------------------------------------------
    |
    | Human-readable names for models in Vietnamese.
    |
    */
    'model_translations' => [
        'User' => 'Người dùng',
        'Hotel' => 'Khách sạn',
        'Room' => 'Phòng',
        'Booking' => 'Đặt phòng',
        'Payment' => 'Thanh toán',
        'Review' => 'Đánh giá',
        'Setting' => 'Cài đặt',
        'Role' => 'Vai trò',
        'Permission' => 'Quyền hạn',
        'Team' => 'Nhóm',
        'Category' => 'Danh mục',
        'Tag' => 'Thẻ',
        'Media' => 'Phương tiện',
        'Notification' => 'Thông báo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Action Translations
    |--------------------------------------------------------------------------
    |
    | Human-readable names for actions in Vietnamese.
    |
    */
    'action_translations' => [
        'create' => 'Tạo mới',
        'update' => 'Cập nhật',
        'delete' => 'Xóa',
        'restore' => 'Khôi phục',
        'login' => 'Đăng nhập',
        'logout' => 'Đăng xuất',
        'bulk_update' => 'Cập nhật hàng loạt',
        'bulk_delete' => 'Xóa hàng loạt',
        'other' => 'Khác',
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the audit log user interface.
    |
    */
    'ui' => [
        'per_page' => 25,
        'max_export_records' => 10000,
        'date_format' => 'd/m/Y H:i:s',
        'timezone' => 'Asia/Ho_Chi_Minh',
        'show_ip_address' => true,
        'show_user_agent' => false,
        'show_url' => true,
        'enable_restore' => true,
        'enable_export' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    |
    | Settings to optimize audit log performance.
    |
    */
    'performance' => [
        'batch_size' => 1000,
        'cleanup_batch_size' => 1000,
        'max_old_values_size' => 65535, // Max size for old_values JSON field
        'max_new_values_size' => 65535, // Max size for new_values JSON field
        'enable_compression' => false,
    ],
];