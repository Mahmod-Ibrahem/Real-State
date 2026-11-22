<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Enums\DashboardPermission;
use Modules\Dashboard\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard')
        ->middleware(['auth', DashboardPermission::View->middleware()]);
});
