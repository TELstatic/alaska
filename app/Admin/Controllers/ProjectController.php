<?php

namespace App\Admin\Controllers;

use App\Admin\Cards\TotalCurrentIncome;
use App\Admin\Cards\TotalHistoryIncome;
use App\Models\Account;
use App\Models\Catalog;
use App\Models\Currency;
use App\Models\Project;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;

class ProjectController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('投资项目')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(Project::with(['catalog', 'account', 'currency']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('code');
            $grid->column('catalog.name', '分类');
            $grid->column('account.name', '账号');
            $grid->column('currency.name', '货币');
            $grid->column('持有金额')->display(function ($name) {
                return formatNumber($name);
            });
            $grid->column('持有份额')->display(function ($name) {
                return formatNumber($name);
            });
            $grid->column('持仓成本价')->display(function ($name) {
                return formatNumber($name);
            });
            $grid->column('最新净值')->display(function ($name) {
                return formatNumber($name);
            })->editable();

            $grid->column('持有收益')->display(function ($name) {
                return formatNumber($name);
            });
            $grid->column('持有收益率')->display(function ($name) {
                return formatRate($name);
            });
            $grid->column('累计收益')->display(function ($name) {
                return formatNumber($name);
            });
            $grid->column('累计收益率')->display(function ($name) {
                return formatRate($name);
            });
            $grid->column('买入费率')->display(function ($name) {
                return formatRate($name);
            });
            $grid->column('卖出费率')->display(function ($name) {
                return formatRate($name);
            });
            $grid->column('created_at')->sortable();

            $grid->quickSearch(['id', 'name', 'code'])->placeholder('请输入名称或代码');
            $grid->showColumnSelector();
            $grid->hideColumns(['累计收益', '累计收益率']);

            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->selectOne('catalog_id', '分类', Catalog::query()->pluck('name', 'id'));
                $selector->selectOne('account_id', '帐号', Account::query()->pluck('name', 'id'));
                $selector->selectOne('currency_id', '货币', Currency::query()->pluck('name', 'id'));

                $selector->selectOne('price', '持有收益',
                    [
                        '-30%~-25%',
                        '-25%~-20%',
                        '-20%~-15%',
                        '-15%~-10%',
                        '-10%~-5%',
                        '-5%~0%',
                        '0%~5%',
                        '5%~10%',
                        '10%~15%',
                        '15%~20%',
                        '20%~25%',
                        '35%~30%',
                    ],
                    function ($query, $value) {
                        $between = [
                            ['-0.1', '-0.05'],
                            ['-0.05', '0'],
                            ['0', '0.05'],
                            ['0.05', '0.1'],
                            ['0.1', '0.15'],
                            ['0.15', '0.2'],
                            ['0.2', '0.25'],
                            ['0.25', '0.3'],
                        ];

                        $query->whereBetween('持有收益率', $between[$value]);
                    });
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('name');
                $filter->like('code');
                $filter->between('created_at', '创建日期')->date();
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->append('<a href="/admin/project/'.$actions->getKey().'/stats"><i class="fa fa-paper-plane"></i> 统计</a>');
            });
        });
    }

    public function stats(Content $content, $id)
    {
        return $content
            ->header('项目统计')
            ->description('统计')
            ->body(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->row(new TotalCurrentIncome());
                });

                $row->column(6, function (Column $column) {
                    $column->row(new TotalHistoryIncome());
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
        return Show::make($id, Project::with(['catalog', 'account', 'currency']), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('code');
            $show->field('url');
            $show->field('catalog.name', '分类');
            $show->field('account.name', '账号');
            $show->field('currency.name', '货币');
            $show->field('持有金额')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('持有份额')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('持仓成本价')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('最新净值')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('持有收益')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('持有收益率')->as(function ($value) {
                return formatRate($value);
            });
            $show->field('累计收益')->as(function ($value) {
                return formatNumber($value);
            });
            $show->field('累计收益率')->as(function ($value) {
                return formatRate($value);
            });
            $show->field('买入费率')->as(function ($value) {
                return formatRate($value);
            });
            $show->field('卖出费率')->as(function ($value) {
                return formatRate($value);
            });
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
        return Form::make(new Project(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->text('code')->required();

            $form->html('请输入获取最新净值地址,更多信息请参考 <a href="/admin/readme">Readme</a>');
            $form->url('url', '接口地址');
            $form->select('catalog_id', '分类')
                ->options(Catalog::query()->pluck('name', 'id'))->required();
            $form->select('account_id', '账号')
                ->options(Account::query()->pluck('name', 'id'))->required();
            $form->select('currency_id', '货币')
                ->options(Currency::query()->pluck('name', 'id'))->required();
            $form->decimal('持有金额')->required();
            $form->decimal('持有份额')->required();
            $form->decimal('持仓成本价')->required();
            $form->decimal('最新净值')->required();
            $form->decimal('持有收益')->required();
            $form->decimal('持有收益率')->required();
            $form->decimal('累计收益')->required();
            $form->decimal('累计收益率')->required();
            $form->decimal('买入费率')->required();
            $form->decimal('卖出费率')->required();

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
