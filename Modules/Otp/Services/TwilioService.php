<?php

namespace Modules\Otp\Services;

use Illuminate\Support\Facades\Log;
use Modules\Otp\Contracts\OtpContract;

class TwilioService implements OtpContract
{
    public function send(string $phoneNumber, string $message): bool
    {
        Log::info("Sending OTP to $phoneNumber with message: $message");

        return true;
    }
}
