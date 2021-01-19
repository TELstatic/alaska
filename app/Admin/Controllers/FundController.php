<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\FundForm;
use App\Admin\Forms\LoanForm;
use App\Admin\Repositories\Fund;
use App\Admin\Repositories\Holiday;
use App\Admin\Repositories\Loan;
use App\Models\Catalog;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Markdown;

class FundController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('基金查询')
            ->body(new Card(new FundForm()))
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
        return Show::make($id, new Fund(), function (Show $show) {
            $show->field('code','基金代码');
            $show->field('name','基金名称');
            $show->field('type','类型');
            $show->field('manager','基金经理');
            $show->field('netWorth','最新净值');
            $show->field('netWorthDate','最新净值时间');
            $show->field('fundScale','基金规模');
            $show->field('lastWeekGrowth','最近一周涨幅');
            $show->field('lastMonthGrowth','最近一个月涨幅');
            $show->field('lastThreeMonthsGrowth','最近三个月涨幅');
            $show->field('lastSixMonthsGrowth','最近半年涨幅');
            $show->field('lastYearGrowth','最近一年涨幅');
            $show->field('buyMin','最少购买金额');
            $show->field('buySourceRate','场外购买费率');
            $show->field('buyRate','场内购买费率');
            $show->disableDeleteButton();
            $show->disableListButton();
            $show->disableEditButton();
            $show->disableQuickEdit();
        });
    }
}
