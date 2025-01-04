<?php

use Illuminate\Support\Facades\Route;
use Package\XLimit\Controllers\LimitController;

Route::group(['middleware' => ['web', 'auth', 'user']], function () {

    Route::prefix('/user/limits')->group(function () {

        Route::get('/', [LimitController::class, 'index'])->name('limit.index');

        Route::post('/save', [LimitController::class, 'store'])->name('limit.store');
        Route::get('/get-limits', [LimitController::class, 'getLimits'])->name('limit.get');
        Route::delete('/delete-limit/{id}', [LimitController::class, 'deleteLimit'])->name('limit.delete');

    });
});
