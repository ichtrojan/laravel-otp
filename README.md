# Laravel OTP ▲

## Introduction 🖖

This is a simple package to generate and validate OTPs (One Time Passwords). This can be implemented mostly in Authentication.
This is a fork from [ichtrojan/laravel-otp](https://github.com/ichtrojan/laravel-otp).
This version users the `App\User` as the default `$identifier` type instead of `string` type.

## Installation 💽

Install via composer

```bash
composer require kenkioko/laravel-otp
```

Add service provider to the `config/app.php` file

```php
<?php
   /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        ...
        Kenkioko\OTP\OTPServiceProvider::class,
    ];
...
```

Add alias to the `config/app.php` file

```php
<?php

   /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [
        ...
        'OTP' => Kenkioko\OTP\OTP::class,
    ];
...
```

Run Migrations

```bash
php artisan migrate
```

## Usage 🧨

>**NOTE**</br>
>Response are returned as objects. You can access its attributes with the arrow operator (`->`)

### Generate OTP

```php
<?php

OTP::generate(App\User $identifier, int $digits = 4, int $validity = 10)
```

* `$identifier`: The identity that will be tied to the OTP of type `\App\User::class`.
* `$digit (optional | default = 4)`: The amount of digits to be generated, can be any of 4, 5 and 6.
* `$validity (optional | default = 10)`: The validity period of the OTP in minutes.

#### Sample

```php
<?php

$otp = OTP::generate('michael@okoh.co.uk', 6, 15);
```

This will generate a six digit OTP that will be valid for 15 minutes and the success response will be:

```object
{
  "status": true,
  "token": "282581",
  "message": "OTP generated"
}
```

### Validate OTP

```php
<?php

OTP::validate(App\User $identifier, string $token)
```

* `$identifier`: The identity that is tied to the OTP of type `\App\User::class`.
* `$token`: The token tied to the identity.

#### Sample

```php
<?php

$otp = OTP::generate('michael@okoh.co.uk', '282581');
```

#### Responses

**On Success**

```object
{
  "status": true,
  "message": "OTP is valid"
}
```

**Does not exist**

```object
{
  "status": false,
  "message": "OTP does not exist"
}
```

**Not Valid***

```object
{
  "status": false,
  "message": "OTP is not valid"
}
```

**Expired**

```object
{
  "status": false,
  "message": "OTP Expired"
}
```

## Contribution

If you find an issue with this package or you have any suggestion please help out. I am not perfect.
