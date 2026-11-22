<?php

use Illuminate\Support\Facades\Route;
use Modules\Location\Enums\LocationPermission;
use Modules\Location\Http\Controllers\Admin\AreaController;
use Modules\Location\Http\Controllers\Admin\CityController;
use Modules\Location\Http\Controllers\Admin\CountryController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('countries', [CountryController::class, 'index'])->name('countries.index')
        ->middleware(['auth', LocationPermission::ViewCountries->middleware()]);
    Route::post('countries', [CountryController::class, 'store'])->name('countries.store')
        ->middleware(['auth', LocationPermission::CreateCountries->middleware()]);
    Route::match(['put', 'patch'], 'countries/{country}', [CountryController::class, 'update'])->name('countries.update')
        ->middleware(['auth', LocationPermission::EditCountries->middleware()]);
    Route::delete('countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy')
        ->middleware(['auth', LocationPermission::DeleteCountries->middleware()]);

    Route::get('cities', [CityController::class, 'index'])->name('cities.index')
        ->middleware(['auth', LocationPermission::ViewCities->middleware()]);
    Route::post('cities', [CityController::class, 'store'])->name('cities.store')
        ->middleware(['auth', LocationPermission::CreateCities->middleware()]);
    Route::match(['put', 'patch'], 'cities/{city}', [CityController::class, 'update'])->name('cities.update')
        ->middleware(['auth', LocationPermission::EditCities->middleware()]);
    Route::delete('cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy')
        ->middleware(['auth', LocationPermission::DeleteCities->middleware()]);

    Route::get('areas', [AreaController::class, 'index'])->name('areas.index')
        ->middleware(['auth', LocationPermission::ViewAreas->middleware()]);
    Route::post('areas', [AreaController::class, 'store'])->name('areas.store')
        ->middleware(['auth', LocationPermission::CreateAreas->middleware()]);
    Route::match(['put', 'patch'], 'areas/{area}', [AreaController::class, 'update'])->name('areas.update')
        ->middleware(['auth', LocationPermission::EditAreas->middleware()]);
    Route::delete('areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy')
        ->middleware(['auth', LocationPermission::DeleteAreas->middleware()]);
});
