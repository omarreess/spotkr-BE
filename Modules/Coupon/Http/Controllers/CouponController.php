<?php

namespace Modules\Coupon\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Http\Requests\CouponRequest;
use Modules\Coupon\Services\CouponService;
use Modules\Coupon\Transformers\CouponResource;

class CouponController extends Controller
{
    use HttpResponse;

    private CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index()
    {
        $coupons = $this->couponService->index();

        return $this->paginatedResponse($coupons, CouponResource::class);
    }

    public function show($coupon)
    {
        $coupon = $this->couponService->show($coupon);

        return $this->resourceResponse(CouponResource::make($coupon));
    }

    public function store(CouponRequest $request)
    {
        $this->couponService->store($request->validated());

        return $this->createdResponse(message: translate_success_message('coupon', 'created'));
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return $this->okResponse(message: translate_success_message('coupon', 'deleted'));
    }
}
