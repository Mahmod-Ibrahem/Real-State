<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\Owner\OwnerRegistrationController;
use App\Http\Controllers\Auth\Owner\OwnerSessionController;
use App\Http\Controllers\Auth\Owner\SendEmailCodeController;
use App\Http\Controllers\Auth\Owner\SendPhoneCodeController;
use App\Http\Controllers\Auth\Owner\VerifyEmailCodeController;
use App\Http\Controllers\Auth\Owner\VerifyPhoneCodeController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login')
        ->middleware('guest');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(['guest', 'throttle:5,1']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request')
        ->middleware('guest');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email')
        ->middleware('guest');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset')
        ->middleware('guest');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store')
        ->middleware('guest');

    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice')
        ->middleware('auth');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->name('verification.verify')
        ->middleware(['auth', 'signed', 'throttle:6,1']);
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('verification.send')
        ->middleware(['auth', 'throttle:6,1']);
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm')
        ->middleware('auth');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware('auth');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update')
        ->middleware(['auth', 'not-impersonating']);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')
        ->middleware('auth');
});

Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('login', [OwnerSessionController::class, 'create'])->name('login')
        ->middleware('guest');
    Route::post('login', [OwnerSessionController::class, 'store'])
        ->middleware(['guest', 'throttle:5,1']);
    Route::get('register', [OwnerRegistrationController::class, 'create'])->name('register')
        ->middleware('guest');
    Route::post('register', [OwnerRegistrationController::class, 'store'])
        ->middleware('guest');

    Route::post('phone/code', SendPhoneCodeController::class)->name('phone.code')
        ->middleware(['auth', 'not-impersonating', 'throttle:3,10']);
    Route::post('phone/verify', VerifyPhoneCodeController::class)->name('phone.verify')
        ->middleware(['auth', 'not-impersonating', 'throttle:5,10']);
    Route::post('email/code', SendEmailCodeController::class)->name('email.code')
        ->middleware(['auth', 'not-impersonating', 'throttle:3,10']);
    Route::post('email/verify', VerifyEmailCodeController::class)->name('email.verify')
        ->middleware(['auth', 'not-impersonating', 'throttle:5,10']);
});
