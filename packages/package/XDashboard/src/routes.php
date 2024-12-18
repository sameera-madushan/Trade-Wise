<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Package\XDashboard\Controllers\DashboardController;

Route::group(['middleware' => ['web', 'auth', 'user']], function () {

    Route::prefix('/user')->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index.dashboard');
        Route::get('/get-monthly-pnls', [DashboardController::class, 'getMonthlyPnL'])->name('get.monthly.pnls');
        Route::get('/get-daily-pnls', [DashboardController::class, 'getDailyPnL'])->name('get.daily.pnls');
        Route::get('/get-today-trades', [DashboardController::class, 'getTodayTrades'])->name('get.today.trades');
    });

});
