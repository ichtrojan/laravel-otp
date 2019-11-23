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
     * @return object
     */
    protected function generate(string $identifier, int $digits = 4, int $validity = 10) : object
    {
        Model::identifier($identifier)->valid()->delete();
        $token = str_pad($this->generatePin(), 4, '0', STR_PAD_LEFT);
        if (5 === $digits) {
            $token = str_pad($this->generatePin(5), 5, '0', STR_PAD_LEFT);
        }
        if (6 === $digits) {
            $token = str_pad($this->generatePin(6), 6, '0', STR_PAD_LEFT);
        }
        Model::create([
            'identifier' => $identifier,
            'token' => $token,
            'validity' => $validity
        ]);
        
        return sendJson('OTP is valid', [
            'token' => $token
        ], 201);
        
    }

    /**
     * @param string $identifier
     * @param string $token
     * @return object
     */
    protected function validate(string $identifier, string $token) : object
    {
        $otp = Model::identifier($identifier)->token($token)->first();

        if (null === $otp) {
            return abortJson(404, 'OTP does not exist');
        }

        if (true == $otp->valid) {
            $carbon = new Carbon;
            $now = $carbon->now();
            $validity = $otp->created_at->addMinutes($otp->validity);

            if (strtotime($validity) < strtotime($now)) {
                $otp->valid = false;
                $otp->save();
                
                return abortJson(401, 'OTP Expired');
            }

            $otp->valid = false;
            $otp->save();

            return sendJson('OTP is valid');
        }

        return abortJson(400, 'OTP is not valid');
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
