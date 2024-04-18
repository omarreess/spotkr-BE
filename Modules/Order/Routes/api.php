<?php

use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Order\Http\Controllers\ClientOrderController;

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

Route::group(['middleware' => GeneralHelper::getDefaultLoggedUserMiddlewares()], function(){
    Route::group(['prefix' => 'clients/orders', 'middleware' => 'user_type_in:'. UserTypeEnum::CLIENT], function(){
        Route::get('', [ClientOrderController::class, 'index']);
        Route::get('{order}', [ClientOrderController::class, 'show']);
        Route::post('', [ClientOrderController::class, 'store']);
        Route::post('{order}/cancel', [ClientOrderController::class, 'cancel']);
    });
});
