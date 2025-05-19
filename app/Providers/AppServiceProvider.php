<?php

namespace App\Providers;

use App\Services\SchemeService;
use App\Services\UnitService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SchemeService::class,
        function ($app) {
            return new SchemeService();
        });

        $this->app->singleton(UnitService::class,
        function ($app) {
            return new UnitService();
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
