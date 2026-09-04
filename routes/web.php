<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
    // return view('welcome');
// });

Route::get('/user-profile',[UserController::class, 'UserProfile']);//->middleware([TokenVerificationMiddleware::class]);
Route::post('/userUpdate', [UserController::class, 'UpdateProfile'])->withoutMiddleware([VerifyCsrfToken::class]);//->middleware([TokenVerificationMiddleware::class]);

Route::post('/user-registration', [UserController::class, 'register'])->withoutMiddleware(VerifyCsrfToken::class);

Route::get('/UserLogin', [UserController::class, 'UserLoginView']); 
Route::post('/UserLogin', [UserController::class, 'UserLogin'])->withoutMiddleware(VerifyCsrfToken::class); 

Route::post('/send-otp', [UserController::class, 'SendOTPCode'])->withoutMiddleware(VerifyCsrfToken::class);
// Route::post('/verify-otp', [UserController::class, 'VerifyOTPCode'])->withoutMiddleware(VerifyCsrfToken::class);(This always comment)

Route::post('/verify-otp', [UserController::class, 'VerifyOTP'])->withoutMiddleware(VerifyCsrfToken::class);

Route::post('/Reset-Password', [UserController::class, 'ResetPass'])->withoutMiddleware(VerifyCsrfToken::class);

Route::post('/reset-password',[UserController::class, 'ResetPass'])->withoutMiddleware(VerifyCsrfToken::class)->middleware([TokenVerificationMiddleware::class]);


// User Logout
Route::get('/logout',[UserController::class, 'UserLogout'])->name('logout');

// Route::view('/verify-otp', 'email.OTPMail');

// video 21
// mailertrap.io

// Page Routes
Route::view('/','pages.home');
// Route::view('/userLogin','pages.auth.login-page')->name('login');
Route::view('/userRegistration','pages.auth.registration-page');
Route::view('/sendOtp','pages.auth.send-otp-page');
Route::view('/verifyOtp','pages.auth.verify-otp-page');
Route::view('/resetPassword','pages.auth.reset-pass-page');//->middleware([TokenVerificationMiddleware::class])
Route::view('/userProfile','pages.dashboard.profile-page')->middleware([TokenVerificationMiddleware::class]);

// Category Routes
Route::get('/categoryPage', [CategoryController::class, 'CategoryPage'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/create-category",[CategoryController::class, 'CategoryCreate'])->withoutMiddleware(VerifyCsrfToken::class)->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-category",[CategoryController::class, 'CategoryList'])->withoutMiddleware(VerifyCsrfToken::class)->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-category",[CategoryController::class, 'CategoryDelete'])->withoutMiddleware(VerifyCsrfToken::class)->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-category",[CategoryController::class, 'CategoryUpdate'])->withoutMiddleware(VerifyCsrfToken::class)->middleware([TokenVerificationMiddleware::class]);
// 26