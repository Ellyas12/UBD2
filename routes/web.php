<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\HomeController;
use App\Http\Controllers\Auth\PenelitianController;
use App\Http\Controllers\Auth\PkmController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\SettingsController;
use App\Http\Controllers\ForgotPasswordController;

Route::get('/', fn() => redirect()->route('login.form'));

Route::middleware(['guest', 'prevent-back-history'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    Route::get('/forgot', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot.form');
    Route::post('/forgot', [ForgotPasswordController::class, 'sendCode'])->name('forgot.submit');

    Route::middleware('ensure.email.submitted')->group(function () {
        Route::get('/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('verify.form');
        Route::post('/verify', [ForgotPasswordController::class, 'verifyCode'])->name('verify.code');
    });

    Route::middleware(['ensure.email.submitted', 'ensure.code.verified'])->group(function () {
        Route::get('/reset', [ForgotPasswordController::class, 'showResetForm'])->name('reset.form');
        Route::post('/reset', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password');
    });
});


Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::get('/home', fn() => view('lecturer.home'))->name('lecturer.home')->middleware('role:Lecturer');
    Route::get('/admin', fn() => view('admin.home'))->name('admin.home')->middleware('role:Admin');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/penelitian', [PenelitianController::class, 'index'])->name('penelitian');
Route::get('/pkm', [PkmController::class, 'index'])->name('pkm');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');