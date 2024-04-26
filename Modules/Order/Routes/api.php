<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Order\Http\Controllers\AdminOrderController;
use Modules\Order\Http\Controllers\ClientOrderController;
use Modules\Order\Http\Controllers\ThirdPartyOrderController;

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

Route::group(['middleware' => GeneralHelper::getDefaultLoggedUserMiddlewares()], function () {
    Route::group(['prefix' => 'clients/orders', 'middleware' => ['user_type_in:'.UserTypeEnum::CLIENT]], function () {
        Route::get('', [ClientOrderController::class, 'index']);
        Route::get('{order}', [ClientOrderController::class, 'show']);
        Route::post('', [ClientOrderController::class, 'store']);
        Route::post('{order}/cancel', [ClientOrderController::class, 'cancel']);
        Route::post('{order}/pay', [ClientOrderController::class, 'pay']);
        Route::post('{order}/review', [ClientOrderController::class, 'review']);
    });

    Route::group(['prefix' => 'third_parties/orders', 'middleware' => ['user_type_in:'.UserTypeEnum::THIRD_PARTY]], function () {
        Route::get('', [ThirdPartyOrderController::class, 'index']);
        Route::get('{order}', [ThirdPartyOrderController::class, 'index']);
    });

    Route::group(['prefix' => 'admin/orders', 'middleware' => ['user_type_in:'.UserTypeEnum::ADMIN]], function () {
        Route::get('', [AdminOrderController::class, 'index']);
        Route::get('{order}', [AdminOrderController::class, 'show']);
        Route::post('{order}/refund', [AdminOrderController::class, 'refund']);
        Route::post('{order}/finish', [AdminOrderController::class, 'finish']);
    });
});
