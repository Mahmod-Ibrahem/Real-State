<?php

use Illuminate\Support\Facades\Route;
use Modules\Location\Http\Controllers\Admin\AreaController;
use Modules\Location\Http\Controllers\Admin\CityController;
use Modules\Location\Http\Controllers\Admin\CountryController;

// Created and edited in modals on their list pages, so no create/edit routes (ADMIN_UI.md §5)
Route::admin()->group(function () {
    $modal = ['index', 'store', 'update', 'destroy'];

    Route::resource('countries', CountryController::class)->only($modal);
    Route::resource('cities', CityController::class)->only($modal);
    Route::resource('areas', AreaController::class)->only($modal);
});
