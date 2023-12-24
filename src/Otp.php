<?php

namespace Ichtrojan\Otp;

use Carbon\Carbon;
use Exception;
use Ichtrojan\Otp\Models\Otp as Model;

class Otp
{
    /**
     * @param string $identifier
     * @param string $type
     * @param int $length
     * @param int $validity
     * @return mixed
     * @throws Exception
     */
    public function generate(string $identifier, string $type, int $length = 4, int $validity = 10) : object
    {
        Model::where('identifier', $identifier)->where('valid', true)->delete();

        switch ($type) {
            case "numeric":
                $token = $this->generateNumericToken($length);
                break;
            case "alpha_numeric":
                $token = $this->generateAlphanumericToken($length);
                break;
            default:
                throw new Exception("{$type} is not a supported type");
        }

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
    public function validate(string $identifier, string $token): object
    {
        $otp = Model::where('identifier', $identifier)->where('token', $token)->first();

        if ($otp instanceof Model) {
            if ($otp->valid) {
                $now = Carbon::now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                $otp->update(['valid' => false]);

                if (strtotime($validity) < strtotime($now)) {
                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                }

                $otp->update(['valid' => false]);

                return (object)[
                    'status' => true,
                    'message' => 'OTP is valid'
                ];
            }

            $otp->update(['valid' => false]);

            return (object)[
                'status' => false,
                'message' => 'OTP is not valid'
            ];
        } else {
            return (object)[
                'status' => false,
                'message' => 'OTP does not exist'
            ];
        }
    }

    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    private function generateNumericToken(int $length = 4): string
    {
        $i = 0;
        $token = "";

        while ($i < $length) {
            $token .= random_int(0, 9);
            $i++;
        }

        return $token;
    }

    /**
     * @param int $length
     * @return string
     */
    private function generateAlphanumericToken(int $length = 4): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($characters), 0, $length);
    }
}
