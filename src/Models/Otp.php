<?php

namespace Ichtrojan\Otp\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';

    protected $fillable = [
        'identifier', 'token', 'validity', 'valid'
    ];
}
