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

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminProgramController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\AdminProfileController;

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

Route::middleware(['auth', 'prevent-back-history', 'inactivity'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('role:Lecturer')->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('lecturer.home');
        Route::get('/dosen/{id}', [DosenController::class, 'show'])->name('dosen.profile');

        Route::middleware(['posisi:Kaprodi'])->group(function () {
            Route::get('/kaprodi', [KaprodiController::class, 'index'])->name('kaprodi');
            Route::get('/kaprodi/stamp/{id}', [KaprodiController::class, 'showStampPage'])->name('kaprodi.stamp.show');
            Route::post('/kaprodi/stamp/{id}/confirm', [KaprodiController::class, 'confirmStamp'])->name('kaprodi.stamp.confirm');
        });

        Route::middleware(['posisi:Dekan'])->group(function () {
            Route::get('/dekan', [DekanController::class, 'index'])->name('dekan');
            Route::get('/dekan/review/{program_id}', [DekanController::class, 'showReviewPage'])->name('dekan.review');
            Route::post('/dekan/review/{program_id}', [DekanController::class, 'submitReview'])->name('dekan.submitReview');
        });

        Route::get('/program', [ProgramController::class, 'index'])->name('program');
        Route::get('/program/create', [ProgramController::class, 'createProgram'])->name('program.createProgram');
        Route::post('/program/create', [ProgramController::class, 'store'])->name('program.create');
        Route::get('/program/search-dosen', [ProgramController::class, 'searchDosen'])->name('program.searchDosen');
        Route::get('/program/view/{id}', [ProgramController::class, 'view'])->name('program.view');
        Route::get('/program/edit/{id}', [ProgramController::class, 'edit'])->name('program.edit');
        Route::put('/program/update/{id}', [ProgramController::class, 'update'])->name('program.update');
        Route::get('/program/delete/{id}', [ProgramController::class, 'confirmDelete'])->name('program.confirmDelete');
        Route::delete('/program/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');
        Route::get('/program/restore', [ProgramController::class, 'restoreProgram'])->name('program.restoreProgram');
        Route::post('/program/restore/{id}', [ProgramController::class, 'restore'])->name('program.restore');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/remove-picture', [ProfileController::class, 'removePicture'])
        ->name('profile.remove-picture');
        Route::post('/user/matkul', [ProfileController::class, 'assignToDosen'])->name('user.matkul.store');
        Route::delete('/user/matkul/{id}', [ProfileController::class, 'removeMatkul'])->name('user.matkul.destroy');
        Route::post('/user/matkul/bulk-store', [ProfileController::class, 'bulkStore'])->name('user.matkul.bulkStore');
        Route::post('/user/prestasi/store', [ProfileController::class, 'store'])->name('prestasi.store');
        Route::delete('/user/prestasi/{id}', [ProfileController::class, 'destroy'])->name('prestasi.destroy');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/security/send-code', [ProfileController::class, 'sendSecurityCode'])->name('profile.sendSecurityCode');
        Route::post('/profile/security/verify-code', [ProfileController::class, 'verifySecurityCode'])->name('profile.verifySecurityCode');
        Route::post('/profile/security/update', [ProfileController::class, 'updateSecurity'])->name('profile.updateSecurity');
    });

    Route::middleware(['role:Admin', 'prevent-back-history'])->group(function () {
        Route::get('/admin', [AdminHomeController::class, 'index'])->name('admin.home');
        Route::get('/admin/announcement', [AnnouncementController::class, 'index'])->name('admin.announcement');
        Route::post('/admin/announcement/update', [AnnouncementController::class, 'update'])->name('admin.announcement.update');
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::get('/admin/programs', [AdminProgramController::class, 'index'])->name('admin.programs');
        Route::get('/admin/programs/{id}/view', [AdminProgramController::class, 'view'])->name('admin.programs.view');
        Route::get('/admin/programs/{id}/edit', [AdminProgramController::class, 'edit'])->name('admin.programs.edit');
        Route::post('/admin/programs/{id}/update', [AdminProgramController::class, 'update'])->name('admin.programs.update');
        Route::get('/admin/logs', [LogController::class, 'index'])->name('admin.logs');
        Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
        Route::post('/admin/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::post('/admin/profile/remove-picture', [AdminProfileController::class, 'removePicture'])->name('admin.profile.remove-picture');
        Route::post('/admin/profile/send-code', [AdminProfileController::class, 'sendSecurityCode'])->name('admin.profile.sendSecurityCode');
        Route::post('/admin/profile/verify-code', [AdminProfileController::class, 'verifySecurityCode'])->name('admin.profile.verifySecurityCode');
        Route::post('/admin/profile/update-security', [AdminProfileController::class, 'updateSecurity'])->name('admin.profile.updateSecurity');
    });
});