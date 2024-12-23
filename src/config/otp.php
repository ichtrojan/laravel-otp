<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Use Hashing
    |--------------------------------------------------------------------------
    |
    | This option controls whether OTPs should be hashed before storage.
    | When set to true, OTPs will be hashed using Laravel's Hash facade.
    |
    */
    'use_hashing' => env('OTP_USE_HASHING', false),
];
