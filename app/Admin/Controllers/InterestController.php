<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\InterestForm;
use App\Admin\Forms\LoanForm;
use App\Admin\Repositories\Interest;
use App\Admin\Repositories\Loan;
use App\Exports\InterestExport;
use App\Exports\LoanExport;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InterestController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('复利计算器')
            ->body(new Card(new InterestForm()))
            ->body($this->detail(0))
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(
            new Interest(), function (Grid $grid) {
            $grid->column('年份', '年份');
            $grid->column('本金', '本金')->display(function ($name) {
                return formatNumber($name, 2);
            });
            $grid->column('利息', '利息')->display(function ($name) {
                return formatNumber($name, 2);
            });
            $grid->column('本息小计', '本息小计')->display(function ($name) {
                return formatNumber($name, 2);
            });

            $grid->disableBatchActions();
            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableColumnSelector();
            $grid->disableViewButton();
            $grid->disableRefreshButton();

            $url = route('interest.export').'?'.http_build_query(request()->all());

            $grid->tools('<a class="btn btn-primary btn-outline" href="'.$url.'" target="_blank">数据导出</a>');
        });
    }

    public function export(Request $request)
    {
        $presentValue = $request->get('value', 100);
        $rate = $request->get('rate', 5) / 100;
        $years = $request->get('years', 10);

        if ($request->get('type', Interest::TYPE_FUTURE) == Interest::TYPE_FUTURE) {
            $data = calcFutureValue($presentValue, $rate, $years);
        } else {
            $data = calcPresentValue($presentValue, $rate, $years);
        }

        $filename = $request->get('value').'-'.
            $request->get('years').'-'.
            Interest::$typeMap[$request->get('type', Interest::TYPE_FUTURE)].'.xlsx';

        return Excel::download(new InterestExport($data['items']), $filename);
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
        return Show::make($id, new Interest(), function (Show $show) {
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
            $show->field('total', '年份');
            $show->disableDeleteButton();
            $show->disableListButton();
            $show->disableEditButton();
            $show->disableQuickEdit();
        });
    }

}
