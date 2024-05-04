<?php

namespace Modules\Auth\Services;

use App\Exceptions\ValidationErrorsException;
use Modules\Auth\Abstracts\AbstractVerifyUser;
use Modules\Auth\Enums\VerifyTokenTypeEnum;
use Modules\Auth\Traits\VerifiableTrait;
use Modules\Otp\Contracts\OtpContract;

class VerifyUserService extends AbstractVerifyUser
{
    use VerifiableTrait;

    private OtpContract $otpContract;

    public function __construct(OtpContract $otpContract)
    {
        $this->otpContract = $otpContract;
    }

    public function sendOneTimePassword($handle)
    {
        $code = $this->generateRandomCode();

        $payload = $this->generateVerificationToken(
            $handle,
            $code,
            VerifyTokenTypeEnum::ONE_TIME_PASSWORD,
        );

        $this->updateOrCreateVerificationToken($payload);

        $this->otpContract->send(
            $handle,
            "Your one time password is $code and will expires after ".self::verificationTokenExpiryHours()
        );
    }
}
