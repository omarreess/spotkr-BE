<?php

namespace Modules\Coupon\Services;

use App\Exceptions\ValidationErrorsException;
use Illuminate\Database\Eloquent\Builder;
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

    public function show($coupon)
    {
        return $this->couponModel::findOrFail($coupon);
    }

    public function store(array $data)
    {
        $this->couponModel::create($data);
    }

    /**
     * @throws ValidationErrorsException
     */
    public function getValidCouponByName($code, string $errorKey = 'coupon')
    {
        $coupon = $this->couponModel::query()
            ->where('code', $code)
            ->where('status', true)
            ->where(function (Builder $builder) {
                $builder->whereNull('valid_till')
                    ->orWhere('valid_till', '>=', now());
            })
            ->where(function (Builder $builder) {
                $builder->whereNull('number_of_users')
                    ->orWhereColumn('used_by_users', '>', 'number_of_users');
            })
            ->first();

        if (! $coupon) {
            throw new ValidationErrorsException([
                $errorKey => translate_error_message('coupon', 'not_exists'),
            ]);
        }

        return $coupon;
    }
}
