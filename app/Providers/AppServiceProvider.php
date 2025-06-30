<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SEOService::class, function ($app) {
            return new \App\Services\SEOService();
        });

        $this->app->singleton(\App\Services\ModernSEOService::class, function ($app) {
            return new \App\Services\ModernSEOService();
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
