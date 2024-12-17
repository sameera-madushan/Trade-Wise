<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Package\XAuth\Controllers\Auth\AuthenticatedSessionController;
use Package\XAuth\Controllers\Auth\ConfirmablePasswordController;
use Package\XAuth\Controllers\Auth\EmailVerificationNotificationController;
use Package\XAuth\Controllers\Auth\EmailVerificationPromptController;
use Package\XAuth\Controllers\Auth\NewPasswordController;
use Package\XAuth\Controllers\Auth\PasswordController;
use Package\XAuth\Controllers\Auth\PasswordResetLinkController;
use Package\XAuth\Controllers\Auth\RegisteredUserController;
use Package\XAuth\Controllers\Auth\VerifyEmailController;
use Package\XAuth\Controllers\PermissionController;
use Package\XAuth\Controllers\ProfileController;
use Package\XAuth\Controllers\RoleController;
use Package\XAuth\Controllers\UserController;

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

});

Route::group(['middleware' => ['web', 'auth', 'admin']], function () {

    Route::prefix('/admin')->group(function () {

        Route::get('/', function () {
            return Inertia::render('XAuth/Admin/Dashboard');
        });

        Route::post('/get-permissions', [PermissionController::class, 'getPermissions']);
        Route::get('/get-permissions', [PermissionController::class, 'getPermissionsAsResource']);

        Route::post('/get-roles', [RoleController::class, 'getRoles']);
        Route::post('/get-users', [UserController::class, 'getUsers']);

        Route::get('/my-profile', [ProfileController::class, 'edit']);
        Route::patch('/my-profile', [ProfileController::class, 'update']);
        Route::delete('/my-profile', [ProfileController::class, 'destroy']);
        Route::post('/change-profile-picture', [ProfileController::class, 'changeProfilePicture']);

        Route::prefix('/settings')->group(function () {
            Route::resource('permissions', PermissionController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('users', UserController::class);
        });
    });

});

Route::group(['middleware' => ['web', 'auth', 'user']], function () {

    Route::prefix('/user')->group(function () {

        Route::get('/', function () {
            return Inertia::render('XAuth/User/Dashboard');
        });

        Route::post('/get-permissions', [PermissionController::class, 'getPermissions']);
        Route::get('/get-permissions', [PermissionController::class, 'getPermissionsAsResource']);

        Route::post('/get-roles', [RoleController::class, 'getRoles']);
        Route::post('/get-users', [UserController::class, 'getUsers']);

        Route::get('/my-profile', [ProfileController::class, 'edit']);
        Route::patch('/my-profile', [ProfileController::class, 'update']);
        Route::delete('/my-profile', [ProfileController::class, 'destroy']);
        Route::post('/change-profile-picture', [ProfileController::class, 'changeProfilePicture']);

        Route::prefix('/settings')->group(function () {
            Route::resource('permissions', PermissionController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('users', UserController::class);
        });
    });

});

Route::group(['middleware' => ['web', 'guest']], function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});
