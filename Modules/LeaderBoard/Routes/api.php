<?php

use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\LeaderBoard\Http\Controllers\AchievementController;
use Modules\LeaderBoard\Http\Controllers\AdminLeaderboardController;
use Modules\LeaderBoard\Http\Controllers\ClientLeaderBoardController;

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

Route::group(['prefix' => 'clients/leaderboard'], function(){
    Route::get('', [ClientLeaderBoardController::class, 'index']);
});

Route::group(['prefix' => 'clients/{client}/achievements'], function(){
    Route::get('', [AchievementController::class, 'index']);
});

Route::group(['prefix' => 'admin/leaderboard'], function(){
    Route::get('', [AdminLeaderboardController::class, 'index']);
    Route::patch('mark_as_winner', [AdminLeaderboardController::class, 'markAsWinner']);
});
