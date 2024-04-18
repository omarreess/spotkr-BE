<?php

namespace Modules\Order\Entities\Builders;

use Illuminate\Database\Eloquent\Builder;
use Modules\Order\Traits\OrderStatusEloquent;

class OrderBuilder extends Builder
{
    use OrderStatusEloquent;
}
