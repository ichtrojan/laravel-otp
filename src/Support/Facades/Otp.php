<?php

namespace Ichtrojan\Otp\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Otp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'otp';
    }
}
