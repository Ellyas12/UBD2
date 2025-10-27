<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\HomeController;
use App\Http\Controllers\Auth\PenelitianController;
use App\Http\Controllers\Auth\PkmController;
use App\Http\Controllers\Auth\KaprodiController;
use App\Http\Controllers\Auth\DekanController;
use App\Http\Controllers\Auth\DosenController;
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
    Route::get('/dosen/{id}', [DosenController::class, 'show'])->name('dosen.profile');

    Route::get('/kaprodi', [KaprodiController::class, 'index'])->name('kaprodi');
    Route::get('/kaprodi/stamp/{id}', [KaprodiController::class, 'showStampPage'])->name('kaprodi.stamp.show');
    Route::post('/kaprodi/stamp/{id}/confirm', [KaprodiController::class, 'confirmStamp'])->name('kaprodi.stamp.confirm');

    Route::get('/dekan', [DekanController::class, 'index'])->name('dekan');
    Route::get('/dekan/review/{program_id}', [DekanController::class, 'showReviewPage'])->name('dekan.review');
    Route::post('/dekan/review/{program_id}', [DekanController::class, 'submitReview'])->name('dekan.submitReview');

    // 🧩 List all programs
    Route::get('/program', [ProgramController::class, 'index'])->name('program');

    // 🧩 Show create form (GET)
    Route::get('/program/create', [ProgramController::class, 'createProgram'])->name('program.createProgram');

    // 🧩 Submit create form (POST)
    Route::post('/program/create', [ProgramController::class, 'store'])->name('program.create');

    // 🧩 View / edit / delete actions
    Route::get('/program/view/{id}', [ProgramController::class, 'view'])->name('program.view');
    Route::get('/program/edit/{id}', [ProgramController::class, 'edit'])->name('program.edit');
    Route::put('/program/update/{id}', [ProgramController::class, 'update'])->name('program.update');

    Route::delete('/program/file/{file_id}', [ProgramController::class, 'deleteFile'])->name('program.file.delete');
    Route::get('/program/delete/{id}', [ProgramController::class, 'confirmDelete'])->name('program.confirmDelete');
    Route::delete('/program/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');


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