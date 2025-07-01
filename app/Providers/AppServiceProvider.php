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
        // Share visitor statistics with all views
        view()->composer('*', function ($view) {
            if (class_exists(\App\Models\VisitorStatistic::class)) {
                try {
                    $visitorStats = \App\Models\VisitorStatistic::getFooterStats();
                    $view->with('visitorStats', $visitorStats);
                } catch (\Exception $e) {
                    // Fallback to default values if database is not ready
                    $view->with('visitorStats', [
                        'today' => 0,
                        'this_week' => 0,
                        'total' => 0,
                    ]);
                }
            }
        });
    }
}
