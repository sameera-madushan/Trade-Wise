<?php

use Illuminate\Support\Facades\Route;
use Package\XConfig\Controllers\ConfigCategoriesController;
use Package\XConfig\Controllers\ConfigController;

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::prefix('/admin')->group(function () {

       Route::prefix('/settings')->group(function () {

           Route::post('/get-config-categories', [ConfigCategoriesController::class, 'getConfigCategories']);
           Route::post('/get-config', [ConfigController::class, 'getConfig']);

           Route::resource('config-categories', ConfigCategoriesController::class);
           Route::resource('configurations', ConfigController::class);
       });
   });
});
