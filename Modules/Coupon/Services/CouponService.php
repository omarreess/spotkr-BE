<?php

namespace Modules\Coupon\Services;

use Modules\Coupon\Entities\Coupon;

class CouponService
{
    private Coupon $couponModel;

    public function __construct(Coupon $couponModel)
    {
        $this->couponModel = $couponModel;
    }

    public function index()
    {
        return $this->couponModel::query()
            ->latest()
            ->searchable(['code'])
            ->paginatedCollection();
    }

    public function show($coupon) {
        return $this->couponModel::findOrFail($coupon);
    }

    public function store(array $data)
    {
        $this->couponModel::create($data);
    }
}
