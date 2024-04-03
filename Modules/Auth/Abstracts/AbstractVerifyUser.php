<?php

namespace Modules\Auth\Abstracts;

use App\Exceptions\ValidationErrorsException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\VerifyTokenTypeEnum;
use Modules\Auth\Traits\VerifiableTrait;

abstract class AbstractVerifyUser
{
    private function userShouldBeVerified(User $user): bool
    {
        return AuthEnum::userShouldBeVerified($user);
    }

    /**
     * @throws ValidationErrorsException
     */
    protected function validateAlreadyVerified(User $user): void
    {
        if (! $this->userShouldBeVerified($user)) {
            throw new ValidationErrorsException([
                'user' => translate_word('user_already_verified'),
            ]);
        }
    }

    public function updateUserVerificationStatus(User $user, bool $enableUser = true): void
    {
        $user->forceFill([
            AuthEnum::VERIFIED_AT => now(),
            'status' => $enableUser,
        ])
            ->save();
    }
}
