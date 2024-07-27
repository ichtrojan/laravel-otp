<?php

namespace Ichtrojan\Otp;

use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/otp.php', 'otp'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/otp.php' => config_path('otp.php'),
        ], 'otp-config');
        
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

         $this->commands([
            \Ichtrojan\Otp\Commands\CleanOtps::class,
        ]);
    }
}
