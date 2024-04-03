<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Helpers\VerifyConfigHelper;
use Modules\Auth\Http\Controllers\CompleteSignUpController;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\LogoutController;
use Modules\Auth\Http\Controllers\PasswordController;
use Modules\Auth\Http\Controllers\PasswordResetController;
use Modules\Auth\Http\Controllers\ProfileController;
use Modules\Auth\Http\Controllers\RegisterController;
use Modules\Auth\Http\Controllers\RemoveAccountController;
use Modules\Auth\Http\Controllers\SocialAuthController;
use Modules\Auth\Http\Controllers\VerifyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'guest'], function(){
    // Social Auth
    Route::group(['prefix' => 'social/callback'], function(){
        Route::post('client', [SocialAuthController::class, 'client']);
        Route::post('third_party', [SocialAuthController::class, 'thirdParty']);
    });

    // Login
    Route::group(['prefix' => 'login'], function () {
        Route::post('dashboard', [LoginController::class, 'loginSpa']);
        Route::post('mobile', [LoginController::class, 'loginMobile']);
    });

    // Register
    Route::group(['prefix' => 'register'], function(){
        Route::post('client', [RegisterController::class, 'client']);
        Route::post('third_party', [RegisterController::class, 'thirdParty']);
    });

    // Verify User
    if(VerifyConfigHelper::enabled()) {
        Route::group(['prefix' => 'verify_user'], function () {
            Route::post('', [VerifyController::class, 'verify'])->middleware(['throttle:10,1']);
            Route::post('resend', [VerifyController::class, 'send'])->middleware(['throttle:10,1']);

            // one time password
            Route::post('one_time_password', [VerifyController::class, 'sendOneTimePassword'])->middleware(['throttle:3,1']);
        });

        // Password
//        Route::group(['prefix' => 'password'], function () {
//            Route::post('forgot_password', [PasswordResetController::class, 'forgotPassword'])->middleware(['throttle:10,1']);
//            Route::post('reset_password', [PasswordResetController::class, 'resetPassword']);
//        });
    }

});

Route::group(['middleware' => GeneralHelper::getDefaultLoggedUserMiddlewares()], function(){
    // Logout
    Route::post('logout', LogoutController::class);

    // Change User Password
    Route::put('password/change_password', [PasswordController::class, 'changePassword']);

    // Profile
    Route::group(['prefix' => 'profile'], function () {
        Route::get('', [ProfileController::class, 'show']);
        Route::post('', [ProfileController::class, 'handle']);
    });

    // Remove User Account
    Route::post('remove_account', RemoveAccountController::class);
    Route::post('complete_signup', CompleteSignUpController::class);
});
