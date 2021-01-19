<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Show\FavorFund;
use App\Admin\Forms\FundForm;
use App\Admin\Repositories\Fund;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;

class FundController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('基金查询')
            ->body(new Card(new FundForm()))
            ->body($this->detail());
    }

    protected function grid()
    {
        return Grid::make(new Fund(), function (Grid $grid) {
            $grid->column('code', '基金代码');
            $grid->column('name', '基金名称');
            $grid->column('netWorth', '最新净值');
            $grid->column('netWorthDate', '最新净值时间');
            $grid->column('lastWeekGrowth', '最近一周涨幅');
            $grid->column('lastMonthGrowth', '最近一个月涨幅');
            $grid->column('lastThreeMonthsGrowth', '最近三个月涨幅');
            $grid->column('lastSixMonthsGrowth', '最近半年涨幅');
            $grid->column('lastYearGrowth', '最近一年涨幅');

            $grid->disableEditButton();
            $grid->disableDeleteButton();
        });
    }

    /**
     * Make a show builder.
     *
     * @return mixed
     */
    protected function detail()
    {
        $id = request('code', 202015);

        return Show::make($id, new Fund(), function (Show $show) {
            $show->field('code', '基金代码');
            $show->field('name', '基金名称');
            $show->field('type', '类型');
            $show->field('manager', '基金经理');
            $show->field('netWorth', '最新净值');
            $show->field('netWorthDate', '最新净值时间');
            $show->field('fundScale', '基金规模');
            $show->field('lastWeekGrowth', '最近一周涨幅');
            $show->field('lastMonthGrowth', '最近一个月涨幅');
            $show->field('lastThreeMonthsGrowth', '最近三个月涨幅');
            $show->field('lastSixMonthsGrowth', '最近半年涨幅');
            $show->field('lastYearGrowth', '最近一年涨幅');
            $show->field('buyMin', '最少购买金额');
            $show->field('buySourceRate', '场外购买费率');
            $show->field('buyRate', '场内购买费率');
            $show->disableDeleteButton();
            $show->disableListButton();
            $show->disableEditButton();
            $show->disableQuickEdit();

            $show->tools(function (Show\Tools $tools) {
                $tools->append(new FavorFund());
            });
        });
    }
}
