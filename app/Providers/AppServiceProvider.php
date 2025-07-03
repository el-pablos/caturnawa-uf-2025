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
        // Production environment validation
        if (app()->environment('production')) {
            $this->validateProductionEnvironment();
        }

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

    /**
     * Validate production environment settings
     */
    private function validateProductionEnvironment(): void
    {
        // Check debug mode
        if (config('app.debug')) {
            throw new \Exception('Debug mode must be disabled in production!');
        }

        // Check required environment variables
        $requiredEnvVars = [
            'DB_PASSWORD',
            'MIDTRANS_SERVER_KEY',
            'MIDTRANS_CLIENT_KEY',
        ];

        foreach ($requiredEnvVars as $envVar) {
            if (empty(env($envVar))) {
                throw new \Exception("Required environment variable {$envVar} is not set!");
            }
        }

        // Check Midtrans production mode
        if (!env('MIDTRANS_IS_PRODUCTION', false)) {
            \Log::warning('Midtrans is not in production mode!');
        }
    }
}
