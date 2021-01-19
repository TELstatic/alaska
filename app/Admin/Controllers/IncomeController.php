<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\IncomeForm;
use App\Admin\Repositories\Income;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;

class IncomeController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('收益计算器')
            ->body(new Card(new IncomeForm()))
            ->body($this->detail(0));
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
        return Show::make($id, new Income(), function (Show $show) {
            $show->panel()->title('');
            $show->field('本金')->as(function ($value) {
                return formatNumber($value, 2);
            });
            $show->field('总利息')->as(function ($value) {
                return formatNumber($value, 2);
            });
            $show->field('本息合计')->as(function ($value) {
                return formatNumber($value, 2);
            });
            $show->field('年化')->as(function ($value) {
                return formatRate($value);
            });
            $show->disableDeleteButton();
            $show->disableListButton();
            $show->disableEditButton();
            $show->disableQuickEdit();
        });
    }
}
