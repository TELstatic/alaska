<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('readme', 'MarkdownController@readme');

    $router->get('/', 'HomeController@index');

    $router->resource('catalog', 'CatalogController');

    $router->get('project/{id}/stats', 'ProjectController@stats');
    $router->resource('project', 'ProjectController');

    $router->resource('account', 'AccountController');

    $router->resource('order', 'OrderController');

    $router->resource('currency', 'CurrencyController');

    $router->resource('automatic', 'AutomaticController');

    $router->get('loan/export', 'LoanController@export')->name('loan.export');
    $router->resource('loan', 'LoanController');
    $router->resource('fund', 'FundController');
    $router->get('stock/board', 'StockController@board');
    $router->resource('stock', 'StockController');

    $router->get('holiday/template', 'HolidayController@template')->name('holiday.template');
    $router->resource('holiday', 'HolidayController');

    $router->get('interest/export', 'InterestController@export')->name('interest.export');
    $router->resource('interest', 'InterestController');

    $router->resource('annualized', 'AnnualizedController');
    $router->resource('income', 'IncomeController');

    $router->resource('annuity', 'AnnuityController');

    $router->resource('favorite', 'FavoriteController');

    $router->resource('manager', 'ManagerController');

    $router->resource('company', 'CompanyController');

    $router->get('reward','RewardController@index');
});
