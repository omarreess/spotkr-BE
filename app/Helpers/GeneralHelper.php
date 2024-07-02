<?php

namespace App\Helpers;

class GeneralHelper extends \Elattar\Prepare\Helpers\GeneralHelper
{
    public static function getDefaultLoggedUserMiddlewares(?string $permissions = null): array
    {
        $permissions = $permissions ? 'permission:'.$permissions : null;

        $middlewares = [
            'auth:sanctum',
            'account_must_be_active',
            'must_be_verified',
        ];

        if ($permissions) {
            $middlewares[] = $permissions;
        }

        return $middlewares;
    }

    public static function mobileMiddlewares(bool $mustCompleteProfile = true, array $additionalMiddlewares = [])
    {
        return array_merge(
            self::getDefaultLoggedUserMiddlewares(),
            array_filter([
                $mustCompleteProfile ? 'must_complete_profile' : null
            ]),
            $additionalMiddlewares
        );
    }
}
