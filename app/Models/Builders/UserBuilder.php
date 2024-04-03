<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Traits\AuthTrait;
use Modules\Auth\Traits\VerificationBuilderTrait;

class UserBuilder extends Builder
{
    use AuthTrait, VerificationBuilderTrait;

    public function whereIsThirdParty(): UserBuilder
    {
        return $this->where('type', UserTypeEnum::THIRD_PARTY);
    }
}
