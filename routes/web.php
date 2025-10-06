<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\HomeController;
use App\Http\Controllers\Auth\PenelitianController;
use App\Http\Controllers\Auth\PkmController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\ProgramController;
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
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('role:Lecturer')->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('lecturer.home');


        Route::get('/program', [ProgramController::class, 'index'])->name('program');
        Route::post('/program/create', [ProgramController::class, 'store'])->name('program.create');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/remove-picture', [ProfileController::class, 'removePicture'])
        ->name('profile.remove-picture');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/security/send-code', [ProfileController::class, 'sendSecurityCode'])->name('profile.sendSecurityCode');
        Route::post('/profile/security/verify-code', [ProfileController::class, 'verifySecurityCode'])->name('profile.verifySecurityCode');
        Route::post('/profile/security/update', [ProfileController::class, 'updateSecurity'])->name('profile.updateSecurity');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    });

    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin', fn() => view('admin.home'))->name('admin.home');
    });
});