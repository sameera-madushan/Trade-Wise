<?php

use Illuminate\Support\Facades\Route;
use Package\XDashboard\Controllers\DashboardController;

Route::group(['middleware' => ['web', 'auth', 'user']], function () {

    Route::prefix('/user')->group(function () {

        Route::get('/', [DashboardController::class, 'userIndex'])->name('user.dashboard');
        Route::get('/get-monthly-pnls', [DashboardController::class, 'getMonthlyPnL'])->name('get.monthly.pnls');
        Route::get('/get-daily-pnls', [DashboardController::class, 'getDailyPnL'])->name('get.daily.pnls');
        Route::get('/get-today-trades', [DashboardController::class, 'getTodayTrades'])->name('get.today.trades');
    });

});

Route::group(['middleware' => ['web', 'auth', 'admin']], function () {

    Route::prefix('/admin')->group(function () {

        Route::get('/', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
        Route::get('/get-users-count', [DashboardController::class, 'userCount'])->name('get.users.count');
    });

});
