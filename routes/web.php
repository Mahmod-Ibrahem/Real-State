<?php

use App\Enums\NotificationPermission;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MarkNotificationsReadController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Dashboard\Enums\DashboardPermission;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/change-locale', [LanguageController::class, 'changeLocale'])->name('change.locale');

Route::redirect('/admin', '/admin/dashboard');
Route::redirect('/supplier', '/supplier/dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit')
        ->middleware(['auth', DashboardPermission::View->middleware()]);
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update')
        ->middleware(['auth', DashboardPermission::View->middleware()]);
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy')
        ->middleware(['auth', DashboardPermission::View->middleware()]);
});

Route::patch('supplier/notifications/read', MarkNotificationsReadController::class)->name('supplier.notifications.read')
    ->middleware(['auth', NotificationPermission::Receive->middleware()]);

Route::fallback(fn () => Inertia::render('404'));

require __DIR__ . '/auth.php';
