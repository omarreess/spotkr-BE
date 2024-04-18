<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\CardController;

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

Route::group(['prefix' => 'payments', 'middleware' => GeneralHelper::getDefaultLoggedUserMiddlewares()], function () {
    Route::group(['prefix' => 'cards'], function () {
        Route::get('', [CardController::class, 'index']);
        Route::post('', [CardController::class, 'store']);
        Route::delete('{card}', [CardController::class, 'destroy']);
    });
});
