<?php

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/UserRegistration', [UserController::class, 'register'])->withoutMiddleware(VerifyCsrfToken::class);
Route::post('/UserLogin', [UserController::class, 'UserLogin'])->withoutMiddleware(VerifyCsrfToken::class); 