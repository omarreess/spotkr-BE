<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\User\Http\Controllers\ThirdPartyController;

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

Route::group([
    'prefix' => 'users',
    'middleware' => array_merge(GeneralHelper::getDefaultLoggedUserMiddlewares(), [
        'user_type_in:'.UserTypeEnum::ADMIN,
    ])], function () {
        Route::group(['prefix' => 'third_parties'], function () {
            Route::get('', [ThirdPartyController::class, 'thirdParties']);
            Route::patch('{thirdParty}/change_status', [ThirdPartyController::class, 'changeStatus']);
        });
    });
