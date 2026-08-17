<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('subcategories', SubCategoryController::class);
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/quick-logout', [UserController::class, 'logout'])->name('user.logout');
});

Route::middleware('guest')->group(function () {
    Route::get('otp/request', [OtpController::class, 'showRequestForm'])->name('otp.request');
    Route::get('otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('otp/send', [OtpController::class, 'sendOtp'])->name('otp.send');

    Route::get('/quick-register', [UserController::class, 'showRegistrationForm'])->name('user.register.form');
    Route::post('/quick-register', [UserController::class, 'register'])->name('user.register');
    Route::get('/quick-login', [UserController::class, 'showLoginForm'])->name('user.login.form');
    Route::post('/quick-login', [UserController::class, 'login'])->name('user.login');
});

require __DIR__.'/auth.php';