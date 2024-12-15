<?php

use Illuminate\Support\Facades\Route;
use Package\XReport\Controllers\ReportController;

Route::group(['middleware' => ['web', 'auth', 'user']], function () {
    Route::prefix('/user/{uuid}/report')->group(function () {
        Route::get('/daily_pnl', [ReportController::class, 'indexDailyReport'])->name('report.daily');
        Route::get('/get-daily-report-data/{date}', [ReportController::class, 'dailyReportData'])->name('get.report.daily');
        Route::get('/monthly_pnl', [ReportController::class, 'indexMonthlyReport'])->name('report.monthly');
        Route::get('/get-monthly-report-data/{monthwithyear}', [ReportController::class, 'monthlyReportData'])->name('get.report.monthly');
        Route::get('/yearly_pnl', [ReportController::class, 'indexYearlyReport'])->name('report.yearly');
        Route::get('/get-yearly-report-data/{year}', [ReportController::class, 'yearlyReportData'])->name('get.report.yearly');
    });
});
