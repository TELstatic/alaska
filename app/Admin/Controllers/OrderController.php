<?php

namespace App\Admin\Controllers;

use App\Models\Account;
use App\Models\Catalog;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Project;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class OrderController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(Order::with(['project', 'project.catalog', 'project.account', 'project.currency']),
            function (Grid $grid) {
                $grid->column('id')->sortable();
                $grid->column('price')->display(function ($name) {
                    return str_replace('.0000', '', $name);
                });
                $grid->column('type')->display(function ($name) {
                    return Order::$typeMap[$name];
                })->filter(
                    Grid\Column\Filter\In::make(Order::$typeMap)
                );

                $grid->column('project.name', '项目名称');
                $grid->column('project.currency.name', '货币');
                $grid->column('确认金额')->display(function ($name) {
                    return str_replace('.0000', '', $name);
                });
                $grid->column('确认份额')->display(function ($name) {
                    return str_replace('.0000', '', $name);
                });
                $grid->column('确认净值')->display(function ($name) {
                    return str_replace('.0000', '', $name);
                });
                $grid->column('手续费')->display(function ($name) {
                    return str_replace('.0000', '', $name);
                });
                $grid->column('confirmed_at');
                $grid->column('created_at')->sortable();

                $grid->selector(function (Grid\Tools\Selector $selector) {
                    $selector->selectOne('project.catalog_id', '分类', Catalog::query()->pluck('name', 'id'));
                    $selector->selectOne('project.account_id', '帐号', Account::query()->pluck('name', 'id'));
                    $selector->selectOne('project.currency_id', '货币', Currency::query()->pluck('name', 'id'));
                    $selector->selectOne('type', '类型', Order::$typeMap);

                    $selector->selectOne('price', '投入金额',
                        [
                            '0~1000',
                            '1000~2000',
                            '2000~3000',
                            '3000~4000',
                            '4000~5000',
                            '5000~6000',
                            '6000~7000',
                        ],
                        function ($query, $value) {
                            $between = [
                                ['0', '1000'],
                                ['1000', '2000'],
                                ['2000', '3000'],
                                ['3000', '4000'],
                                ['4000', '5000'],
                                ['5000', '6000'],
                                ['6000', '7000'],
                            ];

                            $query->whereBetween('price', $between[$value]);
                        });
                });

                $grid->filter(function (Grid\Filter $filter) {
                    $filter->equal('id');

                });
            });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, Order::with(['project', 'project.currency']), function (Show $show) {
            $show->field('id');
            $show->field('price')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('type')->using(Order::$typeMap);
            $show->field('project.name', '项目');
            $show->field('project.currency.name', '货币');
            $show->field('确认金额')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('确认份额')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('确认净值')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('手续费')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('confirmed_at');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(Order::with(['project']), function (Form $form) {
            $form->display('id');
            $form->text('price')->required();

            $form->radio('type')
                ->options(Order::$typeMap)
                ->default(Order::TYPE_BUY)
                ->required()
                ->disable($form->isEditing());

            if ($form->isEditing()) {
                $form->select('project_id', '项目')
                    ->options(Project::query()->pluck('name', 'id'))
                    ->required()
                    ->disable();
            } else {
                $form->select('project_id', '项目')
                    ->options(Project::query()->pluck('name', 'id'))
                    ->required();
            }

            $form->decimal('确认金额')->required();
            $form->decimal('确认份额')->required();
            $form->decimal('确认净值')->required();
            $form->decimal('手续费')->required();
            $form->datetime('confirmed_at')->required();

            $form->datetime('created_at');
            $form->display('updated_at');

            $form->saved(function (Form $form) {
                $form->model()->refresh();
            });
        });
    }
}
