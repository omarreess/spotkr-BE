<?php

namespace Modules\Auth\Actions\Register;

use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Strategies\Verifiable;

class ThirdPartyRegisterAction
{
    public function handle(array $data, Verifiable $verifiable): void
    {
        $data['type'] = UserTypeEnum::THIRD_PARTY;

        (new BaseRegisterAction)->handle($data, $verifiable);
    }
}
