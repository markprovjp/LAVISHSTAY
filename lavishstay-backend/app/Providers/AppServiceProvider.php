<?php

namespace App\Providers;

use App\Services\ChatBotService;
use Illuminate\Support\ServiceProvider;
use App\Services\RoomAvailabilityService;
use App\Services\RoomTypeOverviewService;
use App\Services\RoomTypeDetailService;
use App\Models\Booking;
use App\Observers\BookingObserver;
class AppServiceProvider extends ServiceProvider
{    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(RoomAvailabilityService::class, function ($app) {
            return new RoomAvailabilityService();
        });

        $this->app->singleton(RoomTypeOverviewService::class, function ($app) {
            return new RoomTypeOverviewService();
        });

        $this->app->singleton(RoomTypeDetailService::class, function ($app) {
            return new RoomTypeDetailService();
        });

          // Register Gmail Service - use mock if Google Client is not available
        $this->app->bind(\App\Contracts\GmailServiceInterface::class, function ($app) {
            if (class_exists('Google_Client')) {
                return new \App\Services\GmailService();
            } else {
                return new \App\Services\MockGmailService();
            }
        });
        
        $this->app->bind(\App\Services\GmailService::class, function ($app) {
            return $app->make(\App\Contracts\GmailServiceInterface::class);
        });
        $this->app->singleton(ChatBotService::class, function ($app) {
            return new ChatBotService();
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register BookingObserver
        Booking::observe(BookingObserver::class);
    }
}
