<?php

namespace Modules\Auth\Traits;

use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Helpers\UserVerificationHelper;

trait HasVerifyTokens
{
    public function getUniqueColumnValue()
    {
        return $this->{static::getUniqueColumnName()};
    }

    public static function getUniqueColumnName(): string
    {
        return AuthEnum::getUniqueColumn();
    }
}
