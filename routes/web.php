<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// App-level routes only; every domain registers its own in Modules/<Name>/routes/web.php (CLAUDE.md, Routing)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/change-locale', [LanguageController::class, 'changeLocale'])->name('change.locale');

Route::redirect('/admin', '/admin/dashboard');

Route::admin()->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(fn () => Inertia::render('404'));

require __DIR__ . '/auth.php';
