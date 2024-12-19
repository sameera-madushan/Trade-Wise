<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Package\XLimit\Controllers\LimitController;

Route::group(['middleware' => ['web', 'auth', 'user']], function () {

    Route::prefix('/user/limits')->group(function () {

        Route::get('/', function () {
            return Inertia::render('XLimit/Index');
        });

        Route::post('/save', [LimitController::class, 'store'])->name('limit.store');
        Route::get('/get-limits', [LimitController::class, 'getLimits'])->name('limit.get');

    });
});
