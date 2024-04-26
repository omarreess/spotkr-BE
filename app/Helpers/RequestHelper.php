<?php

namespace App\Helpers;

use Illuminate\Auth\AuthenticationException;

class RequestHelper extends \Elattar\Prepare\Helpers\RequestHelper
{
    public static function loginIfHasToken($thisValue, array $additionalMiddlewares = [], array $onlyMethods = []): void
    {
        $token = request()->bearerToken();

        if ($token && ! auth()->check()) {
            try {
                if ($onlyMethods) {
                    $thisValue->middleware(self::authMiddlewares())->only($onlyMethods);
                } else {
                    $thisValue->middleware(self::authMiddlewares());
                }
            } catch (AuthenticationException $e) {
            }

        }
    }
}
