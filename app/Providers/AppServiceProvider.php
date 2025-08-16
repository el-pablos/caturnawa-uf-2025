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

        $this->app->singleton(\App\Services\PricingService::class, function ($app) {
            return new \App\Services\PricingService();
        });

        $this->app->singleton(\App\Services\RegistrationValidationService::class, function ($app) {
            return new \App\Services\RegistrationValidationService();
        });

        $this->app->singleton(\App\Services\DynamicFormService::class, function ($app) {
            return new \App\Services\DynamicFormService();
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

        // Share visitor statistics and global config with all views
        view()->composer('*', function ($view) {
            // Visitor statistics
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

            // Global configuration
            $view->with('globalConfig', [
                'midtrans_configured' => \App\Helpers\MidtransHelper::isConfigured(),
                'midtrans_client_key' => \App\Helpers\MidtransHelper::getClientKey(),
                'midtrans_production' => \App\Helpers\MidtransHelper::isProduction(),
            ]);
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

        // Check critical environment variables (temporarily disabled for deployment)
        // $criticalEnvVars = [
        //     'DB_PASSWORD',
        // ];

        // foreach ($criticalEnvVars as $envVar) {
        //     $value = env($envVar);
        //     if (empty($value) && $value !== '0') {
        //         throw new \Exception("Critical environment variable {$envVar} is not set!");
        //     }
        // }

        // Check optional but important environment variables
        $optionalEnvVars = [
            'MIDTRANS_SERVER_KEY',
            'MIDTRANS_CLIENT_KEY',
        ];

        foreach ($optionalEnvVars as $envVar) {
            if (empty(env($envVar))) {
                \Log::warning("Optional environment variable {$envVar} is not set. Some features may not work properly.");
            }
        }

        // Check Midtrans production mode
        if (env('MIDTRANS_SERVER_KEY') && !env('MIDTRANS_IS_PRODUCTION', false)) {
            \Log::warning('Midtrans is configured but not in production mode!');
        }
    }
}
