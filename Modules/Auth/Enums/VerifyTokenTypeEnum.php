<?php

namespace Modules\Auth\Enums;

enum VerifyTokenTypeEnum
{
    const VERIFICATION = 0;

    const PASSWORD_RESET = 1;

    const ONE_TIME_PASSWORD = 2;

    public static function availableTypes(): array
    {
        return [
            self::VERIFICATION,
            self::PASSWORD_RESET,
            self::ONE_TIME_PASSWORD,
        ];
    }
}
