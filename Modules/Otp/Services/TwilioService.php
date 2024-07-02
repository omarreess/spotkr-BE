<?php

namespace Modules\Otp\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Otp\Contracts\OtpContract;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioService implements OtpContract
{
    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    public function send(string $phoneNumber, string $message): bool
    {
        $sid    = config('services.otp.twilio.account_sid');
        $token  = config('services.otp.twilio.auth_token');
        $messagingSid = config('services.otp.twilio.messaging_sid');
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create('+'.Str::replace('+', '', $phoneNumber),
                [
                    "messagingServiceSid" => $messagingSid,
                    "body" => $message
               ]
            );

        return $message->status == 'accepted';
    }
}
