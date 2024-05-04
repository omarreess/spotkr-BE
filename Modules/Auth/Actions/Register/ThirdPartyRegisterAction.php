<?php

namespace Modules\Auth\Actions\Register;

use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Strategies\Verifiable;
use Modules\Country\Services\CountryService;

class ThirdPartyRegisterAction
{
    public function handle(array $data, Verifiable $verifiable, CountryService $countryService): void
    {
        $data['type'] = UserTypeEnum::THIRD_PARTY;

        (new BaseRegisterAction)->handle($data, $verifiable, $countryService);
    }
}
