<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Package\XCalendar\Controllers\CalendarController;

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::prefix('/user')->group(function () {

       Route::prefix('/calendar')->group(function () {

            Route::get('/', function () {
                return Inertia::render('XCalendar/Index');
            });

            Route::get('/get-pnls', [CalendarController::class, 'getPnlsForCalendar'])->name('get.pnls');

            Route::get('/{timestamp}', [CalendarController::class, 'index'])->name('calendar.index');
            Route::post('/{timestamp}', [CalendarController::class, 'store'])->name('calendar.store');
            Route::post('/{timestamp}/get-trades', [CalendarController::class, 'getTrades'])->name('calendar.getTrades');
            Route::delete('/{timestamp}/{id}/delete-trade', [CalendarController::class, 'deleteTrades'])->name('calendar.deleteTrades');

            Route::post('/{timestamp}/{id}/save-note', [CalendarController::class, 'saveNote'])->name('calendar.saveNote');
            Route::get('/{timestamp}/{id}/get-note', [CalendarController::class, 'getNote'])->name('calendar.getNote');
       });
   });

});
