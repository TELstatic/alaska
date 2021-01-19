<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\FundForm;
use App\Admin\Forms\LoanForm;
use App\Admin\Forms\StockForm;
use App\Admin\Repositories\Fund;
use App\Admin\Repositories\Holiday;
use App\Admin\Repositories\Loan;
use App\Admin\Repositories\Stock;
use App\Models\Catalog;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Markdown;
use Illuminate\Support\Str;

class StockController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('股票查询')
            ->body(new Card(new StockForm()))
            ->body($this->detail(0));
    }


    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    protected function detail($id)
    {
        return Show::make($id, new Stock(), function (Show $show) {
            $show->field('code', '股票代码');
            $show->field('name', '股票名称');
            $show->field('type', '类型')->as(function ($name) {
                if (Str::startsWith($name, 'GP')) {
                    return '股票';
                }

                if (Str::startsWith($name, 'ZS')) {
                    return '指数';
                }

                return $name;
            });
            $show->field('open', '今日开票价');
            $show->field('close', '昨日收盘价');
            $show->field('price', '实时价格');
            $show->field('high', '开盘截止目前最高价');
            $show->field('low', '开盘截止目前最低价');
            $show->field('totalWorth', '总市值');
            $show->field('pb', '市净率');
            $show->field('pe', '市赢率');
            $show->disableDeleteButton();
            $show->disableListButton();
            $show->disableEditButton();
            $show->disableQuickEdit();
        });
    }
}
