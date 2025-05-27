<?php

namespace App\Providers;

use App\Services\SchemeService;
use App\Services\UnitService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SchemeService::class,
        function () {
            return new SchemeService();
        });

        $this->app->singleton(UnitService::class,
        function () {
            return new UnitService();
        });

        $this->app->singleton(UserService::class,
        function () {
            return new UserService();
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
