<?php

return [
    'otp' => [
        'default' => env('DEFAULT_OTP_PROVIDER', 'twilio'),
        'from' => env('OTP_FROM'),
        'twilio' => [
            'concrete' => Modules\Otp\Services\TwilioService::class,
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'twilio_number' => env('TWILIO_PHONE_NUMBER'),
            'messaging_sid' => env('TWILIO_MESSAGING_SERVICE_SID'),
        ],
        'log' => [
            'concrete' => Modules\Otp\Services\OtpLogService::class,
        ],

        // future providers
    ],
];
