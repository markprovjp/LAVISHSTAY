<?php

namespace App\Providers;

use App\Services\NotificationService;
use App\Models\NotificationType;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind NotificationService as singleton
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });

        // Register notification service alias
        $this->app->alias(NotificationService::class, 'notification.service');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register notification gates
        $this->registerGates();

        // Share notification data with views
        $this->shareViewData();

        // Register event listeners
        $this->registerEventListeners();

        // Register custom validation rules
        $this->registerValidationRules();

        // Register notification macros
        $this->registerMacros();
    }

    /**
     * Register authorization gates for notifications
     */
    protected function registerGates(): void
    {
        Gate::define('view-notification', function ($user, $notification) {
            // User can view their own notifications
            if ($notification->notifiable_id == $user->id && 
                $notification->notifiable_type == get_class($user)) {
                return true;
            }

            // Admins can view all notifications
            if ($user->hasRole('admin')) {
                return true;
            }

            // Hotel managers can view notifications for their property
            if ($user->hasRole('hotel_manager')) {
                return true;
            }

            return false;
        });

        Gate::define('manage-notifications', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('send-notifications', function ($user) {
            return $user->hasAnyRole(['admin', 'hotel_manager']);
        });

        Gate::define('configure-notification-types', function ($user) {
            return $user->hasRole('admin');
        });
    }

    /**
     * Share notification data with views
     */
    protected function shareViewData(): void
    {
        // Share unread notification count with all views
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $unreadCount = auth()->user()->unreadNotifications()->count();
                $view->with('unreadNotificationCount', $unreadCount);
            }
        });

        // Share notification types with admin views
        View::composer(['admin.notifications.*', 'admin.settings.*'], function ($view) {
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                $notificationTypes = NotificationType::active()->get();
                $view->with('notificationTypes', $notificationTypes);
            }
        });
    }

    /**
     * Register custom event listeners
     */
    protected function registerEventListeners(): void
    {
        // Listen for user login to send welcome notification
        Event::listen('Illuminate\Auth\Events\Login', function ($event) {
            if ($event->user->last_login_at === null) {
                // First time login - send welcome notification
                $notificationService = app(NotificationService::class);
                $notificationService->sendToUsers(
                    [$event->user->id],
                    'Welcome to ' . config('app.name'),
                    'Welcome! We\'re excited to have you on board.',
                    ['user_id' => $event->user->id],
                    '/dashboard'
                );
            }
        });

        // Listen for failed login attempts
        Event::listen('Illuminate\Auth\Events\Failed', function ($event) {
            // You could send security notifications here
        });
    }

    /**
     * Register custom validation rules
     */
    protected function registerValidationRules(): void
    {
        \Validator::extend('notification_type', function ($attribute, $value, $parameters, $validator) {
            return NotificationType::where('name', $value)->where('is_active', true)->exists();
        });

        \Validator::extend('notification_priority', function ($attribute, $value, $parameters, $validator) {
            return in_array($value, ['low', 'normal', 'high', 'urgent']);
        });

        \Validator::extend('notification_data', function ($attribute, $value, $parameters, $validator) {
            // Validate notification data structure
            if (!is_array($value)) {
                return false;
            }

            // Add custom validation logic for notification data
            return true;
        });
    }

    /**
     * Register helpful macros
     */
    protected function registerMacros(): void
    {
        // Add macro to User model for easy notification sending
        if (class_exists(\App\Models\User::class)) {
            \App\Models\User::macro('notifyWithType', function ($typeName, $data = []) {
                $notificationService = app(NotificationService::class);
                return $notificationService->sendByType($typeName, $data, [$this->id]);
            });

            \App\Models\User::macro('getNotificationSettings', function () {
                return $this->notificationSettings()
                    ->with('notificationType')
                    ->get()
                    ->keyBy('notification_type_id');
            });
        }

        // Add collection macro for bulk notification operations
        \Illuminate\Support\Collection::macro('notifyAll', function ($typeName, $data = []) {
            $userIds = $this->pluck('id')->toArray();
            $notificationService = app(NotificationService::class);
            return $notificationService->sendByType($typeName, $data, $userIds);
        });
    }
}