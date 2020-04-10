<?php

namespace KenKioko\OTP;

use App\User;
use Carbon\Carbon;
use KenKioko\OTP\Models\OTP as Model;
use Illuminate\Support\Facades\Facade;

class OTP extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'OTP';
    }

    /**
     * @param string $identifier
     * @param int $digits
     * @param int $validity
     * @return mixed
     */
    public function generate(User $identifier, int $digits = 4, int $validity = 10) : object
    {
        dd($identifier);


        Model::where('identifier', $identifier)->where('valid', true)->delete();

        $token = str_pad($this->generatePin(), 4, '0', STR_PAD_LEFT);

        if ($digits == 5)
            $token = str_pad($this->generatePin(5), 5, '0', STR_PAD_LEFT);

        if ($digits == 6)
            $token = str_pad($this->generatePin(6), 6, '0', STR_PAD_LEFT);

        Model::create([
            'identifier' => $identifier,
            'token' => $token,
            'validity' => $validity
        ]);

        return (object)[
            'status' => true,
            'token' => $token,
            'message' => 'OTP generated'
        ];
    }

    /**
     * @param string $identifier
     * @param string $token
     * @return mixed
     */
    public function validate(User $identifier, string $token) : object
    {
        dd($identifier);

        $otp = Model::where('identifier', $identifier)->where('token', $token)->first();

        if ($otp == null) {
            return (object)[
                'status' => false,
                'message' => 'OTP does not exist'
            ];
        } else {
            if ($otp->valid == true) {
                $carbon = new Carbon;
                $now = $carbon->now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                if (strtotime($validity) < strtotime($now)) {
                    $otp->valid = false;
                    $otp->save();

                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                } else {
                    $otp->valid = false;
                    $otp->save();

                    return (object)[
                        'status' => true,
                        'message' => 'OTP is valid'
                    ];
                }
            } else {
                return (object)[
                    'status' => false,
                    'message' => 'OTP is not valid'
                ];
            }
        }
    }

    /**
     * @param int $digits
     * @return string
     */
    private function generatePin($digits = 4)
    {
        $i = 0;
        $pin = "";

        while ($i < $digits) {
            $pin .= mt_rand(0, 9);
            $i++;
        }

        return $pin;
    }
}
