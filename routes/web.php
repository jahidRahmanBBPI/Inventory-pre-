<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/UserRegistration', [UserController::class, 'register'])->withoutMiddleware(VerifyCsrfToken::class);

Route::post('/UserLogin', [UserController::class, 'UserLogin'])->withoutMiddleware(VerifyCsrfToken::class); 

Route::post('/send-otp', [UserController::class, 'SendOTPCode'])->withoutMiddleware(VerifyCsrfToken::class);
// Route::post('/verify-otp', [UserController::class, 'VerifyOTPCode'])->withoutMiddleware(VerifyCsrfToken::class);

Route::post('/verify-otp', [UserController::class, 'VerifyOTP'])->withoutMiddleware(VerifyCsrfToken::class);

Route::post('/Reset-Password', [UserController::class, 'ResetPass'])->withoutMiddleware(VerifyCsrfToken::class);

Route::post('/reset-password',[UserController::class, 'ResetPass'])->withoutMiddleware(VerifyCsrfToken::class)->middleware([TokenVerificationMiddleware::class]);

Route::view('/verify-otp', 'email.OTPMail');
// video 11
// mailertrap.io
