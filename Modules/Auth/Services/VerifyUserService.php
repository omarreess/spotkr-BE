<?php

namespace Modules\Auth\Services;

use App\Exceptions\ValidationErrorsException;
use Modules\Auth\Enums\UserStatusEnum;
use Modules\Auth\Enums\VerifyTokenTypeEnum;
use Modules\Auth\Traits\VerifiableTrait;
use Modules\Otp\Contracts\OtpContract;

class VerifyUserService
{
    use VerifiableTrait;

    private OtpContract $otpContract;

    public function __construct(OtpContract $otpContract)
    {
        $this->otpContract = $otpContract;
    }
    /**
     * @throws ValidationErrorsException
     */
    public function sendOneTimePassword($handle)
    {
        [$user, $code] = $this->generalSendCode(
            $handle,
            VerifyTokenTypeEnum::ONE_TIME_PASSWORD,
        );

        $this->otpContract->send(
            $user->phone,
            "Your one time password is $code and will expires after ". self::verificationTokenExpiryHours()
        );
    }
}
