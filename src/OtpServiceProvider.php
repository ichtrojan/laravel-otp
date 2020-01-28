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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishMigrations();
    }

    protected function publishMigrations()
    {
        $this->publishes([
             __DIR__ . "/database/migrations/2019_05_11_000000_create_otps_table.php" => database_path('migrations/' . date("Y_m_d_His", time()) . '_create_otps_table.php'),
        ], 'laravel-otp-migration');
    }
}
