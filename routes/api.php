<?php

use Illuminate\Http\Request;

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
    Route::post('logout', 'Auth\LoginController@logout');
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        $user->append('can', 'all_permissions');
        $user->makeVisible('roles');
        return $user;
    });

    // Settings
    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');

    // Roles
    Route::get('access/roles', 'Access\RoleController@index');
    Route::get('access/roles/{role}', 'Access\RoleController@show');

    // Manage access
    Route::group(['middleware' => ['permission:manage access']], function () {

        // Permissions
        Route::get('access/permissions', 'Access\PermissionController@index');
        Route::get('access/permissions/{permission}', 'Access\PermissionController@show');

        // Permissions
        Route::post('access/permissions', 'Access\PermissionController@store');
        Route::put('access/permissions/{permission}', 'Access\PermissionController@update');
        Route::delete('access/permissions/{permission}', 'Access\PermissionController@destroy');

        // Roles
        Route::post('access/roles', 'Access\RoleController@store');
        Route::put('access/roles/{role}', 'Access\RoleController@update');
        Route::delete('access/roles/{role}', 'Access\RoleController@destroy');
    });

    // Users
    Route::get('access/users', 'Access\UserController@index');
    Route::get('access/users/{user}', 'Access\UserController@show');

    // Manage users
    Route::group(['middleware' => ['permission:manage users']], function () {

        // Users
        Route::post('access/users', 'Access\UserController@store');
        Route::put('access/users/{user}', 'Access\UserController@update');
        Route::delete('access/users/{user}', 'Access\UserController@destroy');
    });

    // Statuses
    Route::get('statuses', 'StatusController@index');
    Route::get('statuses/{status}', 'StatusController@show');

    // Manage directories
    Route::group(['middleware' => ['permission:manage directories']], function () {

        // Statuses
        Route::post('statuses', 'StatusController@store');
        Route::put('statuses/{status}', 'StatusController@update');
        Route::delete('statuses/{status}', 'StatusController@destroy');
    });

    // Manage pages
    Route::group(['middleware' => ['permission:manage pages']], function () {
        Route::get('pages', 'PageController@index');
        Route::get('pages/slug', 'PageController@slug');
        Route::get('pages/{page}', 'PageController@show');
        Route::post('pages', 'PageController@store');
        Route::put('pages/{page}', 'PageController@update');
        Route::delete('pages/{page}', 'PageController@destroy');
    });

    Route::post('jodit', 'JoditController@index');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});
