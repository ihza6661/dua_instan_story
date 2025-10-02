<?php

namespace App\Providers;

use App\Services\BiteshipService;
use Illuminate\Support\ServiceProvider;

class BiteshipServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(BiteshipService::class, function ($app) {
            return new BiteshipService();
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
