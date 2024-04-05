<?php

use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Coupon\Http\Controllers\CouponController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => array_merge(GeneralHelper::getDefaultLoggedUserMiddlewares(), [
    'user_type_in:'. UserTypeEnum::ADMIN
])], function(){
    Route::patch('admin/coupons/{coupon}', [CouponController::class, 'changeStatus']);
    Route::apiResource('admin/coupons', CouponController::class)->except(['update']);
});

