<?php

namespace Modules\Auth\Helpers;

use Modules\Auth\Enums\UserTypeEnum;

class UserVerificationHelper
{
    public static function allowedUsersTypes(): array
    {
        return [
            UserTypeEnum::THIRD_PARTY,
            UserTypeEnum::CLIENT,
        ];
    }
}
