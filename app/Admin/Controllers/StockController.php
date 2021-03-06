<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Show\FavorStock;
use App\Admin\Forms\StockForm;
use App\Admin\Repositories\Stock;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
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
            ->body($this->detail());
    }

    public function board(Content $content)
    {
        return $content
            ->header('工具')
            ->description('股票大盘信息')
            ->body($this->grid());
    }

    protected function grid()
    {
        return Grid::make(new Stock(), function (Grid $grid) {
            $grid->column('code', '股票代码');
            $grid->column('name', '股票名称');
            $grid->column('type', '类型')->display(function ($name) {
                if (Str::startsWith($name, 'GP')) {
                    return '股票';
                }

                if (Str::startsWith($name, 'ZS')) {
                    return '指数';
                }

                return $name;
            });
            $grid->column('open', '今日开票价');
            $grid->column('close', '昨日收盘价');
            $grid->column('price', '实时价格');
            $grid->column('priceChange', '开盘后价格变化');
            $grid->column('changePercent', '价格变化')->display(function ($name) {
                return formatRate($name, false);
            });
            $grid->column('high', '开盘截止目前最高价');
            $grid->column('low', '开盘截止目前最低价');
            $grid->column('totalWorth', '总市值');
            $grid->column('volume', '成交量(手)');
            $grid->column('turnover', '成交额(万)');
            $grid->column('date', '时间');
            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
        });
    }


    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    protected function detail()
    {
        $id = request('code', 600900);

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

            $show->tools(function (Show\Tools $tools) {
                $tools->append(new FavorStock());
            });
        });
    }
}
