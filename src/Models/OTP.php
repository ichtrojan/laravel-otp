<?php

namespace KenKioko\OTP\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
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
      'token', 'validity'
    ];

    /**
     * The OTP owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
