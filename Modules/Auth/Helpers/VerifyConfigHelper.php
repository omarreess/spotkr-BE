<?php

namespace Modules\Auth\Helpers;

use Illuminate\Support\Facades\Log;

class VerifyConfigHelper
{
    public static function enabled()
    {
        $value =  config('auth.verify.enabled', true);

        return in_array($value, ['true', '1', true], true);
    }

    public static function defaultStrategy()
    {
        return config('auth.verify.strategy');
    }

    public static function getStrategyClass($strategy)
    {
        return config('auth.verify.strategies.'.$strategy.'.class');
    }
}
