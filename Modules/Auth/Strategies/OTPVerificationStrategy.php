<?php

namespace Modules\Auth\Strategies;

use App\Exceptions\ValidationErrorsException;
use Modules\Auth\Abstracts\AbstractVerifyUser;
use Modules\Auth\Enums\VerifyTokenTypeEnum;
use Modules\Auth\Traits\VerifiableTrait;
use Modules\Otp\Contracts\OtpContract;

class OTPVerificationStrategy extends AbstractVerifyUser implements Verifiable
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
    public function verifyCode($handle, $code): bool
    {
        $this->generalVerifyCode($handle, $code);

        return true;
    }

    /**
     * @throws ValidationErrorsException
     */
    public function sendCode($handle): bool
    {
        [$user, $code] = $this->generalSendCode($handle);

        $this->otpContract->send(
            $user->getUniqueColumnValue(),
            $this->generateVerifyMessage($code)
        );

        return true;
    }

    private function generateVerifyMessage($code): string
    {
        return "Your OTP code is $code";
    }

    public function forgetPassword($handle)
    {

    }

    public function resetPassword($handle, $code, $newPassword)
    {

    }
}
