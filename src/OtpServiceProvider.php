<?php

namespace Ichtrojan\Location;

use Carbon\Carbon;
use Ichtrojan\Otp\Otp\Otp;
use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider implements Otp
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
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
    }
}
