<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\Admin\DashboardController;

Route::admin()->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});
