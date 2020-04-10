<?php

namespace KenKioko\OTP;

use Illuminate\Support\ServiceProvider;

class OTPServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/translations', 'laravel-otp');

        $this->publishes([__DIR__ . '/translations' => resource_path('lang/vendor/laravel-otp')]);
    }
}
