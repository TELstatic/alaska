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

Route::view('/', 'welcome');


Route::get('demo', function () {
    $date = \Carbon\Carbon::parse('2021-09-21');

    $holidayService = new \App\Services\HolidayService($date);

    if ($holidayService->isLegalDay()) {
        dd($date->toDateTimeString());
    } else {
        dd($holidayService->getNextWeekday()->toDateTimeString());
    }
});
