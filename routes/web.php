<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\VerificationController;

use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use Illuminate\Support\Facades\Log;
Route::get('/clearconfig', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "clear";
});
// Route::get('/iclock/cdata', function () {
//     Log::info("test data " );
//     return response("OK");
// });
// Route::get('/iclock/getrequest', function () {
//     Log::info("test re " );
//     return response("OK");
// });
// Route::get('/iclock/devicecmd', function () {
//     Log::info("test cmd " );
//     return response("OK");
// });
Route::any('/iclock/cdata', [App\Http\Controllers\ZKDeviceController::class,'cDataResponse']);
Route::any('/iclock/getrequest', [App\Http\Controllers\ZKDeviceController::class,'cDataRequest']);
Route::any('/iclock/devicecmd', [App\Http\Controllers\ZKDeviceController::class,'cDataCmd']);


Route::prefix('business')->middleware(['auth',"is_active"])->group(function () {
    Route::get('/',[HomeController::class,'index'])->name('home');
    Route::get('dashboard', [HomeController::class,'index'])->name('dashboard');
    Route::get('sign_out',[RegisterController::class,'getLogout'])->name('sign_out');
});
//admin
Route::get('login',[LoginController::class,'adminLogin'])->name('login');
Route::post('login',[LoginController::class,'setAdminLogin'])->name('post.login');
//end admin
Route::get('sign-in',[LoginController::class,'getLogin'])->name('sign_in');
Route::post('sign-in-post',[LoginController::class,'setLogin'])->name('sign_in_post');
Route::get('sign-up',[RegisterController::class,'getSignUp'])->name('sign_up');
Route::post('register',[RegisterController::class,'postRegister'])->name('register');

    Route::get('/verification/verify/{code}', [VerificationController::class,'verifyEmail'])->name('register-verify');

    Route::get('auth/google', [SocialLoginController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/google/callback',[SocialLoginController::class, 'handleGoogleCallback']);

    Route::get('auth/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('auth/facebook/callback',[SocialLoginController::class, 'handleFacebookCallback']);
// Route::post('verification/verify',[VerificationController::class,'postRegister'])->name('verification.verify');
Route::get('/email/verify/{id}', function ($id) {
    return view('auth.verify',Compact('id'));
})->name('verification.notice');

Route::post('/email/verification-resend/{id}', [VerificationController::class,'resendEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::get('forget-passowrd',[ForgotPasswordController::class,'getForgetPass'])->name('forget_password');
Route::post('forgot-password', [ForgotPasswordController::class, 'sentMailforgotPassword'])->name('forget.send_mail');
Route::get('forgot-password/{id}', [ForgotPasswordController::class, 'getResetPassword']);
Route::post('forgot-password/{id}', [ForgotPasswordController::class, 'setResetPassword'])->name('reset.forgot_password');
//Business





