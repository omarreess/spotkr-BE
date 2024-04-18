<?php

namespace Modules\Order\Enums;

enum OrderStatusEnum
{
    const PENDING = 0;
    const CANCELED = 1;
    const COMPLETED = 2;
    const REFUNDED = 3;

    public static function toArray()
    {
        return [
            self::PENDING,
            self::CANCELED,
            self::COMPLETED,
            self::REFUNDED,
        ];
    }
}
