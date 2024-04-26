<?php

namespace Modules\Order\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\Order\Entities\Order;

class BaseOrderService
{
    protected Order $orderModel;

    public function __construct(Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    protected function baseIndex()
    {
        return $this->orderModel::query()
            ->when(
                request()->input('status'),
                fn (Builder $builder) => $builder->where('status', request()->input('status'))
            )
            ->latest();
    }
}
