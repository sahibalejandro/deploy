<?php

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

// TODO: Remove this autologin, this is temporal just for development purposes.
use Illuminate\Support\Facades\Auth;

if (!app()->runningInConsole()) {
    if (!Auth::check()) {
        Auth::login(App\User::first(), true);
    }
}

/*
 * API
 * For simplicity purposes we will use the web routes as api routes.
 */
Route::group(['prefix' => 'api'], function () {
    /*
     * Sites.
     */
    Route::get('/sites', 'SitesController@index');
    Route::post('/sites', 'SitesController@store');
    Route::get('/sites/{site}', 'SitesController@show');
    Route::get('/sites/{site}/status', 'SitesController@status');

    /*
     * Databases.
     */
    Route::get('/databases', 'DatabasesController@index');
    Route::post('/databases', 'DatabasesController@store');
    Route::delete('/databases/{database}', 'DatabasesController@destroy');
});

/*
 * Main Page
 */
Route::get('{all}', 'DashboardController@index')->where('all', '.*');
