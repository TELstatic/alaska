<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome')->name('welcome');

// Install Routes
Route::prefix('install')->group(function () {
    Route::middleware('install')->group(function () {
        Route::get('/', 'InstallController@index')->name('install');
        Route::get('requirements', 'InstallController@requirements')->name('install.requirements');
        Route::get('permissions', 'InstallController@permissions')->name('install.permissions');
        Route::get('database', 'InstallController@database')->name('install.database');
        Route::get('qiniu', 'InstallController@qiniu')->name('install.qiniu');
        Route::get('account', 'InstallController@account')->name('install.account');

        Route::post('database', 'InstallController@saveConfig');
        Route::post('qiniu', 'InstallController@saveQiniu');
        Route::post('account', 'InstallController@saveDatabase');
    });

    Route::get('complete', 'InstallController@complete')->name('install.complete');
});
