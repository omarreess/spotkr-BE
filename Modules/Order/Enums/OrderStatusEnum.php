<?php

namespace Modules\Order\Enums;

enum OrderStatusEnum
{
    const PENDING = 0;

    const PAYMENT_DONE = 1;

    const CANCELED = 2;

    const COMPLETED = 3;

    const REFUNDED = 4;

    public static function toArray(): array
    {
        return [
            self::PENDING,
            self::PAYMENT_DONE,
            self::CANCELED,
            self::COMPLETED,
            self::REFUNDED,
        ];
    }
}
