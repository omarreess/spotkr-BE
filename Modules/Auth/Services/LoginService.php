<?php

namespace Modules\Auth\Services;

use App\Exceptions\ValidationErrorsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserStatusEnum;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Enums\VerifyTokenTypeEnum;
use Modules\Auth\Traits\VerifiableTrait;
use Psr\SimpleCache\InvalidArgumentException;

class LoginService
{
    use VerifiableTrait;

    /**
     * @throws InvalidArgumentException
     */
    public function loginSpa(array $validatedData): bool|User|array
    {
        return $this->loginUser($validatedData);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function loginMobile(array $validatedData): User|bool|array
    {
        return $this->loginUser($validatedData, true);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ValidationErrorsException
     */
    protected function loginUser(array $validatedData, bool $isMobile = false): bool|User|array
    {
        $errors = [];
        $user = User::query()
            ->where(AuthEnum::UNIQUE_COLUMN, $validatedData[AuthEnum::UNIQUE_COLUMN])
            ->whereValidType($isMobile)
            ->with(AuthEnum::AVATAR_RELATIONSHIP_NAME)
            ->first();

        if($isMobile)
        {
            if(! $user)
            {
                User::create([
                    'name' => $validatedData['type'],
                    'phone' => $validatedData['phone'],
                    'type' => $validatedData['type'],
                ]);

                $user = User::where('phone', $validatedData['phone'])->with('avatar')->first();
            }

            $this->generalVerifyCode(
                $user,
                $validatedData['one_time_password'],
                VerifyTokenTypeEnum::ONE_TIME_PASSWORD,
            );
        } else {
            if (! $user) {
                return false;
            }

            if ($user->type == UserTypeEnum::ADMIN && $this->userNotFoundOrHaveWrongPassword($user, $validatedData['password'], $user->password ?? null)) {
                return false;
            }

            if (! $this->isVerified($user)) {
                $errors['not_verified'] = true;

                return $errors;
            }

            if (UserStatusEnum::isInActive($user)) {
                $errors['frozen'] = 1;

                return $errors;
            }
        }

        auth()->login($user);

        //UserHelper::loadAdditionalRelations($user);
        //$user->forceFill(['fcm_token' => $validatedData['fcm_token']]);
        //$user->save();

        $this->addTokenIfMobile($user, $isMobile);

        return $user;
    }

    private function userNotFoundOrHaveWrongPassword($user, string $requestPassword, ?string $existingUserPassword = null): bool
    {
        return ! $user || ! Hash::check($requestPassword, $existingUserPassword);
    }

    private function addTokenIfMobile(User $user, bool $isMobile): void
    {
        $expiresAt = config('sanctum.expiration');

        if ($expiresAt) {
            $expiresAt = now()->addMinutes($expiresAt);
        }

        $user->token = $user->createToken(
            $user->name ?: 'Sample User',
            expiresAt: $expiresAt
        )
            ->plainTextToken;
    }

    public function isVerified($user): bool
    {
        return (bool) $user->{AuthEnum::VERIFIED_AT};
    }
}
