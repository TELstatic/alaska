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
//    $fundService = new \App\Services\FundService();
//
//    dd($fundService->getDetail('202015'));
//    $stockService = new \App\Services\StockService();
//
//    dd($stockService->getDetail('600900'));

//    dd(calcFutureValue(100, 0.05, 4));
//    dd();

    dd(
        calcFutureValue(61.39, 0.05, 10),
        calcPresentValue(100,0.05,10));
});

