<?php

namespace Ichtrojan\Otp\Otp;

use Carbon\Carbon;
use Ichtrojan\Otp\Models;

class Otp
{
    public function generate(string $identifier, Otp $otp, int $digits = 4, int $validity = 10)
    {
        $token = mt_rand(0000, 9999);

        if ($digits == 5)
            $token = mt_rand(00000, 99999);

        if ($digits == 6)
            $token = mt_rand(000000, 999999);

        $otp->create([
            'identifier' => $identifier,
            'token' => $token,
            'validity' => $validity
        ]);

        return response()->json([
            'status' => true,
            'message' => 'OTP generated'
        ], 201);
    }

    public function validate(string $identifier, int $token)
    {
        $otp = Otp::where('identifier', $identifier)->where('token', $token);

        if (!$otp->exist()) {
            return response()->json([
                'status' => false,
                'message' => 'OTP does not exist'
            ], 201);
        } else {
            if ($otp->valid == true) {
                $cabon = new Carbon;
                $now = $cabon->now();
                $validity = $cabon->create($otp->created_at)->addMinutes($otp->validity);

                if (strtotime($validity) > strtotime($now)) {
                    $otp->valid = false;
                    $otp->save();

                    return response()->json([
                        'status' => false,
                        'message' => 'OTP Expired'
                    ], 200);
                } else {
                    $otp->valid = false;
                    $otp->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'OTP is Valid'
                    ], 201);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP has already been used'
                ], 201);
            }
        }
    }
}