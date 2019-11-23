<?php

namespace Ichtrojan\Otp\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'otps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identifier', 'token', 'validity'
    ];

    public function scopeToken($query, $token)
    {
        return $query->where('token', $token);
    }

    public function scopeIdentifier($query, $identifier)
    {
        return $query->where('identifier', $identifier);
    }

    public function scopeValid($query)
    {
        return $query->where('valid', true);
    }
}
