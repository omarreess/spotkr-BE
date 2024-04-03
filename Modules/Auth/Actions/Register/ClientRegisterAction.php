<?php

namespace Modules\Auth\Actions\Register;

use App\Exceptions\ValidationErrorsException;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Strategies\Verifiable;

class ClientRegisterAction
{
    /**
     * @throws \Throwable
     * @throws ValidationErrorsException
     */
    public function handle(array $data, Verifiable $verifiable): bool
    {
        $data['type'] = UserTypeEnum::CLIENT;

        (new BaseRegisterAction)->handle($data, $verifiable);

        return true;
    }
}
