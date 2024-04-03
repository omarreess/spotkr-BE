<?php

return [
    'otp' => [
        'default' => env('DEFAULT_OTP_PROVIDER', 'twilio'),
        'from' => env('OTP_FROM'),
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'twilio_number' => env('TWILIO_PHONE_NUMBER'),
        ],

        // future providers
    ],
];
