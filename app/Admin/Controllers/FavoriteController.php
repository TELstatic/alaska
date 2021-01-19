<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Favorite;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Str;

class FavoriteController extends AdminController
{
    protected function grid()
    {
        return Grid::make(new Favorite(), function (Grid $grid) {
            $type = request('_selector.type', \App\Models\Favorite::TYPE_FUND);

            if ($type == \App\Models\Favorite::TYPE_FUND) {
                $grid->column('info.code', '基金代码');
                $grid->column('info.name', '基金名称');
                $grid->column('info.type', '类型');
                $grid->column('info.manager', '基金经理');
                $grid->column('info.netWorth', '最新净值');
                $grid->column('info.netWorthDate', '最新净值时间');
                $grid->column('info.fundScale', '基金规模');
                $grid->column('info.lastWeekGrowth', '最近一周涨幅');
                $grid->column('info.lastMonthGrowth', '最近一个月涨幅');
                $grid->column('info.lastThreeMonthsGrowth', '最近三个月涨幅');
                $grid->column('info.lastSixMonthsGrowth', '最近半年涨幅');
                $grid->column('info.lastYearGrowth', '最近一年涨幅');
                $grid->column('info.buyMin', '最少购买金额');
                $grid->column('info.buySourceRate', '场外购买费率');
                $grid->column('info.buyRate', '场内购买费率');
            } else {
                $grid->column('info.code', '股票代码');
                $grid->column('info.name', '股票名称');
                $grid->column('info.type', '类型')->display(function ($name) {
                    if (Str::startsWith($name, 'GP')) {
                        return '股票';
                    }

                    if (Str::startsWith($name, 'ZS')) {
                        return '指数';
                    }

                    return $name;
                });
                $grid->column('info.open', '今日开票价');
                $grid->column('info.close', '昨日收盘价');
                $grid->column('info.price', '实时价格');
                $grid->column('info.high', '开盘截止目前最高价');
                $grid->column('info.low', '开盘截止目前最低价');
                $grid->column('info.totalWorth', '总市值');
                $grid->column('info.pb', '市净率');
                $grid->column('info.pe', '市赢率');
            }

            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->selectOne('type', '类型', \App\Models\Favorite::$typeMap);
            });

            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->disableViewButton();
        });
    }

    protected function form()
    {
        return Form::make(new \App\Models\Favorite(), function (Form $form) {
            $form->display('id');
        });
    }
}
