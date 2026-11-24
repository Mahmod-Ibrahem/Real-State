<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Enums\UserPermission;
use Modules\Users\Http\Controllers\Admin\UserAccountStateController;
use Modules\Users\Http\Controllers\Admin\UserController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index')
        ->middleware(['auth', UserPermission::View->middleware()]);
    Route::post('users', [UserController::class, 'store'])->name('users.store')
        ->middleware(['auth', UserPermission::Create->middleware()]);
    Route::match(['put', 'patch'], 'users/{user}', [UserController::class, 'update'])->name('users.update')
        ->middleware(['auth', UserPermission::Edit->middleware()]);
    Route::patch('users/{user}/account', UserAccountStateController::class)->name('users.account')
        ->middleware(['auth', UserPermission::Edit->middleware()]);
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')
        ->middleware(['auth', UserPermission::Delete->middleware()]);
});
