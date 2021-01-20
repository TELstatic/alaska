<?php

namespace App\Admin\Controllers;

use App\Models\Account;
use App\Models\Automatic;
use App\Models\Catalog;
use App\Models\Currency;
use App\Models\Project;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AutomaticController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(Automatic::with(['project', 'project.currency']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('project.name', '项目');
            $grid->column('project.currency.name', '货币');
            $grid->column('price')->display(function ($name) {
                return str_replace('.0000', '', $name);
            });

            $grid->column('type')->display(function ($name) {
                return Automatic::$typeMap[$name];
            })->filter(
                Grid\Column\Filter\In::make(Automatic::$typeMap)
            );

            $grid->column('day', '定投日')->display(function ($name, $grid) {
                return Automatic::$dayMap[$grid->getOriginalModel()->type][$name];
            });

            $grid->column('created_at')->sortable();

            $grid->quickSearch(['id', 'project.name', 'project.code'])->placeholder('请输入名称或项目代码');

            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->selectOne('project.catalog_id', '分类', Catalog::query()->pluck('name', 'id'));
                $selector->selectOne('project.account_id', '帐号', Account::query()->pluck('name', 'id'));
                $selector->selectOne('project.currency_id', '货币', Currency::query()->pluck('name', 'id'));
                $selector->selectOne('type', '类型', Automatic::$typeMap);

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
        return Show::make($id, Automatic::with(['project', 'project.currency']), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('project.name', '项目');
            $show->field('project.currency.name', '货币');
            $show->field('price')->as(function ($value) {
                return str_replace('.0000', '', $value);
            });
            $show->field('type')->using(Automatic::$typeMap);
            $show->field('day')->using(Automatic::$dayMap[$show->model()->type]);
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
        return Form::make(Automatic::with('project'), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->select('project_id', '项目')
                ->options(Project::query()
                    ->whereNotNull('url')
                    ->pluck('name', 'id'))
                ->required();
            $form->decimal('price')->required();

            $form->select('type')
                ->when([Automatic::TYPE_WEEK, Automatic::TYPE_FORTNIGHT], function (Form $form) {
                    $form->select('day')
                        ->options(Automatic::$dayMap[Automatic::TYPE_WEEK])
                        ->default(1)->required();
                })
                ->when(Automatic::TYPE_MONTH, function (Form $form) {
                    $form->select('day')
                        ->options(Automatic::$dayMap[Automatic::TYPE_MONTH])
                        ->default(1)->required();
                })
                ->options(Automatic::$typeMap)
                ->default(Automatic::TYPE_WEEK)->required();

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
