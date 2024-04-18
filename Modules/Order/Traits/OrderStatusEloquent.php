<?php

namespace Modules\Order\Traits;

use Modules\Order\Enums\OrderStatusEnum;

trait OrderStatusEloquent
{
    public function wherePending(): static
    {
        return $this->where('status', OrderStatusEnum::PENDING);
    }

    public function whereCompleted(): static
    {
        return $this->where('status', OrderStatusEnum::COMPLETED);
    }
}
