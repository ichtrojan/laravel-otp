# Laravel OTP ▲

[![Latest Stable Version](https://poser.pugx.org/ichtrojan/laravel-otp/v/stable)](https://packagist.org/packages/ichtrojan/laravel-otp) [![Total Downloads](https://poser.pugx.org/ichtrojan/laravel-otp/downloads)](https://packagist.org/packages/ichtrojan/laravel-otp) [![License](https://poser.pugx.org/ichtrojan/laravel-otp/license)](https://packagist.org/packages/ichtrojan/laravel-otp)

## Introduction 🖖

This is a simple package to generate and validate OTPs (One Time Passwords). This can be implemented mostly in Authentication.

## Installation 💽

Install via composer

```bash
composer require ichtrojan/laravel-otp
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

use Ichtrojan\Otp\Otp;

(new Otp)->generate(string $identifier, string $type, int $length = 4, int $validity = 10);
```

* `$identifier`: The identity that will be tied to the OTP.
* `$type`: The type of token to be generated, supported types are `numeric` and `alpha_numeric`
* `$length (optional | default = 4)`: The length of token to be generated.
* `$validity (optional | default = 10)`: The validity period of the OTP in minutes.

#### Sample

```php
<?php

use Ichtrojan\Otp\Otp;

(new Otp)->generate('michael@okoh.co.uk', 'numeric', 6, 15);
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

use Ichtrojan\Otp\Otp;

(new Otp)->validate(string $identifier, string $token)
```

* `$identifier`: The identity that is tied to the OTP.
* `$token`: The token tied to the identity.

#### Sample

```php
<?php

use Ichtrojan\Otp\Otp;

(new Otp)->validate('michael@okoh.co.uk', '282581');
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

In Laravel 11, the `app/Console/Kernel.php` file has been removed. To schedule a task, you can now add it directly to `routes/console.php` as follows:
```php
<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('otp:clean')->daily();
```

You can check if your task has been added to the schedule by using the following artisan command:
```bash
php artisan schedule:list
```


## Contribution

If you find an issue with this package or you have any suggestion please help out. I am not perfect.
