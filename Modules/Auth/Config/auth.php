<?php

return [
    'verify' => [
        'enabled' => env('VERIFY_ENABLED', true),
        'strategy' => env('VERIFY_STRATEGY', 'otp'),
        'strategies' => [
            'email' => [
                'class' => \Modules\Auth\Strategies\EmailVerificationStrategy::class,
            ],
            'otp' => [
                'class' => \Modules\Auth\Strategies\OTPVerificationStrategy::class,
            ],
        ]
    ]
];
