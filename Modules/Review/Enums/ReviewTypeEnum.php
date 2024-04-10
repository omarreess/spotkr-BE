<?php

namespace Modules\Review\Enums;

enum ReviewTypeEnum
{
    const ACTIVITY = 'activity';

    public static function toArray()
    {
        return [
            self::ACTIVITY,
        ];
    }
}
