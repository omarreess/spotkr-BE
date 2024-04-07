<?php

use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Activity\Http\Controllers\AdminActivityController;
use Modules\Activity\Http\Controllers\ClientActivityController;
use Modules\Activity\Http\Controllers\ThirdPartyActivityController;
use Modules\Auth\Enums\UserTypeEnum;

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
    'user_type_in:'. UserTypeEnum::THIRD_PARTY,
])], function(){
    Route::post('{activity}', [ThirdPartyActivityController::class, 'update']);
    Route::apiResource('third_parties/activities', ThirdPartyActivityController::class)->except(['update']);
});

Route::group(['prefix' => 'clients/activities'], function(){
    Route::get('', [ClientActivityController::class, 'index']);
    Route::get('{activity}', [ClientActivityController::class, 'show']);
});

Route::group(['prefix' => 'third_parties/{thirdParty}/activities', 'middleware' => array_merge(GeneralHelper::getDefaultLoggedUserMiddlewares(), [
//    'user_type_in:'. UserTypeEnum::ADMIN,
])], function(){
    Route::get('', [AdminActivityController::class, 'index']);
    Route::get('{id}', [AdminActivityController::class, 'show']);
    Route::patch('{id}', [AdminActivityController::class, 'changeStatus']);
    Route::post('{id}/toggle_carousel', [AdminActivityController::class, 'toggleCarousel']);
    Route::post('{id}/toggle_adrenaline_rush', [AdminActivityController::class, 'toggleAdrenalineRush']);
});