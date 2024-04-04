<?php

namespace Modules\Category\Enums;

enum CategoryTypeEnum
{
    const EVENT = 'event';
    const TRIP = 'trip';
    const COURSE = 'course';
    const SPORT = 'sport';

    public static function availableTypes(): array
    {
        return [
            self::EVENT,
            self::TRIP,
            self::COURSE,
            self::SPORT,
        ];
    }
}
