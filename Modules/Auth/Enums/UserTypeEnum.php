<?php

namespace Modules\Auth\Enums;

use App\Models\User;

enum UserTypeEnum
{
    const ADMIN = 'admin';
    const CLIENT = 'client';
    const THIRD_PARTY = 'third_party';

    public static function availableTypes(): array
    {
        return [
            self::ADMIN,
            self::CLIENT,
            self::THIRD_PARTY,
        ];
    }

    public static function keyValueTypes(): array
    {
        return array_flip(self::availableTypes());
    }

    public static function getNumericTypes(): array
    {
        return array_values(self::keyValueTypes());
    }

    public static function numericType(string $alphaType): string
    {
        return self::keyValueTypes()[$alphaType].'';
    }

    public static function getUserType(?User $user = null)
    {
        $user = ! is_null($user) ? $user : auth()->user();

        return $user?->type;
    }

    public static function getCurrentUserAlphaType(?User $user = null): string
    {
        $user = $user ?: auth()->user();

        return $user->type;
    }
}
