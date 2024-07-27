<?php

namespace Ichtrojan\Otp;

use Carbon\Carbon;
use Exception;
use Ichtrojan\Otp\Models\Otp as Model;
use Illuminate\Support\Facades\Hash;

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

        $useHashing = config('otp.use_hashing', false);

        Model::create([
            'identifier' => $identifier,
            'token' => $useHashing ? Hash::make($token) : $token,
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
        $otp = Model::where('identifier', $identifier)
            ->where('valid', true)
            ->latest('created_at')
            ->first();

        if ($otp instanceof Model) {
            if ($otp->valid) {
                $now = Carbon::now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                if (strtotime($validity) < strtotime($now)) {
                    $otp->update(['valid' => false]);
                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                }

                $useHashing = config('otp.use_hashing', false);
                $isValid = $useHashing ? Hash::check($token, $otp->token) : $token === $otp->token;

                if (!$isValid) {
                    return (object)[
                        'status' => false,
                        'message' => 'OTP is not valid'
                    ];
                }

                $otp->update(['valid' => false]);

                return (object)[
                    'status' => true,
                    'message' => 'OTP is valid'
                ];

            }

            return (object)[
                'status' => false,
                'message' => 'OTP is not valid'
            ];
        }

        return (object)[
            'status' => false,
            'message' => 'OTP not found'
        ];
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
