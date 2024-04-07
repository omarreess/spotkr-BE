<?php

namespace Modules\Activity\Enums;

enum ActivityTypeEnum
{
    const EVENT = 'event';
    const SPORT = 'sport';
    const COURSE = 'course';
    const TRIP = 'trip';

    public static function availableTypes(): array
    {
        return [
            self::EVENT,
            self::SPORT,
            self::COURSE,
            self::TRIP,
        ];
    }
}
