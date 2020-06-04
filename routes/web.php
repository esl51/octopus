<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$routes = function () {

    // Admin routes
    Route::get('admin/{path?}', function () {
        return view('admin');
    })->where([
        'path' => '(.*?)',
    ]);

    //

    // Other routes
    Route::get('{slug?}', 'PageController@render');

};

Route::group([
    'prefix' => '{locale?}',
    'middleware' => 'localization',
    'where' => [
        'locale' => '(' . implode('|', array_keys(config('app.locales'))) . ')',
    ],
], $routes);

Route::group([
    'middleware' => 'localization',
], $routes);
