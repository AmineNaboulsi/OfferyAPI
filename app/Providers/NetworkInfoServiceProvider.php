<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NetworkInfoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('network.info', function ($app) {
            return new \App\Services\NetworkInfoService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
