<?php

namespace Ichtrojan\Otp;

use Carbon\Carbon;
use Ichtrojan\Otp\Models\Otp as Model;
use Illuminate\Support\Facades\Facade;

class Otp extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'Otp';
    }

    /**
     * @param string $identifier
     * @param int $digits
     * @param int $validity
     *
     * @return mixed
     */
    protected function generate(string $identifier, int $digits = 4, int $validity = 10)
    {
        Model::where('identifier', $identifier)->where('valid', true)->delete();
        $token = str_pad($this->generatePin(), 4, '0', STR_PAD_LEFT);
        if ($digits === 5) {
            $token = str_pad($this->generatePin(5), 5, '0', STR_PAD_LEFT);
        }
        if ($digits === 6) {
            $token = str_pad($this->generatePin(6), 6, '0', STR_PAD_LEFT);
        }
        Model::create([
            'identifier' => $identifier,
            'token' => $token,
            'validity' => $validity
        ]);
        return response()->json([
            'status' => true,
            'token' => $token,
            'message' => 'OTP generated'
        ], 201);
    }

    /**
     * @param string $identifier
     * @param string $token
     * @return mixed
     */
    protected function validate(string $identifier, string $token)
    {
        $otp = Model::where('identifier', $identifier)->where('token', $token)->first();

        if ($otp === null) {
            return response()->json([
                'status' => false,
                'message' => 'OTP does not exist'
            ]);
        }

        if ($otp->valid === true) {
            $carbon = new Carbon;
            $now = $carbon->now();
            $validity = $otp->created_at->addMinutes($otp->validity);

            if (strtotime($validity) < strtotime($now)) {
                $otp->valid = false;
                $otp->save();
                return response()->json([
                    'status' => false,
                    'message' => 'OTP Expired'
                ]);
            }

            $otp->valid = false;
            $otp->save();

            return response()->json([
                'status' => true,
                'message' => 'OTP is valid'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'OTP is not valid'
        ]);
    }

    /**
     * @param int $digits
     *
     * @return string
     */
    private function generatePin($digits = 4): string
    {
        $i = 0;
        $pin = '';
        while ($i < $digits) {
            $pin .= random_int(0, 9);
            $i++;
        }
        return $pin;
    }
}
