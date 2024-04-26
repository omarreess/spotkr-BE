<?php

namespace Modules\Activity\Enums;

enum ActivityStatusEnum
{
    const PENDING = 'pending';

    const ACCEPTED = 'accepted';

    const REJECTED = 'rejected';

    public static function availableTypes(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::REJECTED,
        ];
    }
}
