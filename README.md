# Laravel OTP ▲

## Introduction 🖖

This is a simple package to generate and validate OTPs (One Time Passwords). This can be implemented mostly in Authentication.

## Installation 💽

Install via composer

```bash
composer require ichtrojan/laravel-otp
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
        Ichtrojan\Otp\OtpServiceProvider::class,
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
        'Otp' => Ichtrojan\Otp\Otp::class,
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

Otp::generate(string $identifier, int $digits = 4, int $validity = 10)
```

* `$identifier`: The identity that will be tied to the OTP.
* `$digit (optional | default = 4)`: The amount of digits to be generated, can be any of 4, 5 and 6.
* `$validity (optional | default = 10)`: The validity period of the OTP in minutes.

#### Sample

```php
<?php

$otp = Otp::generate('michael@okoh.co.uk', 6, 15);
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

Otp::validate(string $identifier, string $token)
```

* `$identifier`: The identity that is tied to the OTP.
* `$token`: The token tied to the identity.

#### Sample

```php
<?php

$otp = Otp::validate('michael@okoh.co.uk', '282581');
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

### Delete expired tokens
You can delete expired tokens by running the following artisan command:
```bash
php artisan otp:clean
```
You can also add this artisan command to `app/Console/Kernel.php` to automatically clean on scheduled 
```php
<?php

protected function schedule(Schedule $schedule)
{
    $schedule->command('otp:clean')->daily();
}
```

## Contribution

If you find an issue with this package or you have any suggestion please help out. I am not perfect.
