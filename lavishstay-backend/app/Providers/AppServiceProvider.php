<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RoomAvailabilityService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(RoomAvailabilityService::class, function ($app) {
            return new RoomAvailabilityService();
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
