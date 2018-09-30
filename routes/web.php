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
    Route::get('/sites', 'SitesController@index');
});

/*
 * Main Page
 */
Route::get('{all}', 'DashboardController@index')->where('all', '.*');

/*
 * Sites
 */
//Route::get('/sites/create', 'SitesController@create');
//Route::post('/sites', 'SitesController@store');
//Route::get('/sites/{site}', 'SitesController@show');
//Route::delete('/sites/{site}', 'SitesController@destroy');
//Route::post('/sites/{site}/deploy', 'SitesController@deploy');
