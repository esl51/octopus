<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\JoditController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Access\RoleController;
use App\Http\Controllers\Access\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Access\PermissionController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api', 'verified'], function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [UserController::class, 'current']);

    // Settings
    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);

    // Roles
    Route::get('access/roles', [RoleController::class, 'index']);
    Route::get('access/roles/{role}', [RoleController::class, 'show']);

    // Manage access
    Route::group(['middleware' => ['permission:manage access']], function () {

        // Permissions
        Route::get('access/permissions', [PermissionController::class, 'index']);
        Route::get('access/permissions/{permission}', [PermissionController::class, 'show']);

        // Permissions
        Route::post('access/permissions', [PermissionController::class, 'store']);
        Route::put('access/permissions/{permission}', [PermissionController::class, 'update']);
        Route::delete('access/permissions/{permission}', [PermissionController::class, 'destroy']);

        // Roles
        Route::post('access/roles', [RoleController::class, 'store']);
        Route::put('access/roles/{role}', [RoleController::class, 'update']);
        Route::delete('access/roles/{role}', [RoleController::class, 'destroy']);
    });

    // Users
    Route::get('access/users', [UserController::class, 'index']);
    Route::get('access/users/{user}', [UserController::class, 'show']);

    // Manage users
    Route::group(['middleware' => ['permission:manage users']], function () {

        // Users
        Route::post('access/users', [UserController::class, 'store']);
        Route::put('access/users/{user}', [UserController::class, 'update']);
        Route::delete('access/users/{user}', [UserController::class, 'destroy']);
    });

    // Statuses
    Route::get('statuses', [StatusController::class, 'index']);
    Route::get('statuses/{status}', [StatusController::class, 'show']);

    // Manage directories
    Route::group(['middleware' => ['permission:manage directories']], function () {

        // Statuses
        Route::post('statuses', [StatusController::class, 'store']);
        Route::put('statuses/{status}', [StatusController::class, 'update']);
        Route::delete('statuses/{status}', [StatusController::class, 'destroy']);
    });

    Route::get('pages', [PageController::class, 'index']);
    Route::get('pages/{page}', [PageController::class, 'show']);

    // Manage pages
    Route::group(['middleware' => ['permission:manage pages']], function () {
        Route::get('pages/slug', [PageController::class, 'slug']);
        Route::post('pages', [PageController::class, 'store']);
        Route::put('pages/{page}', [PageController::class, 'update']);
        Route::delete('pages/{page}', [PageController::class, 'destroy']);
    });

    Route::post('jodit', [JoditController::class, 'index']);
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});
