<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\LoanForm;
use App\Admin\Repositories\Loan;
use App\Exports\LoanExport;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LoanController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('贷款计算器')
            ->body(new Card(new LoanForm()))
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
            new Loan(
                request('month'),
                request('rate'),
                request('total'),
                request('type')
            ), function (Grid $grid) {
            $grid->column('期数', '还款期数');
            $grid->column('本金', '应还本金')->display(function ($name) {
                return formatNumber($name, 2);
            });
            $grid->column('利息', '应还利息')->display(function ($name) {
                return formatNumber($name, 2);
            });
            $grid->column('本息小计', '本息小计')->display(function ($name) {
                return formatNumber($name, 2);
            });
            $grid->column('还款日期', '还款日期');

            $grid->disableBatchActions();
            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableColumnSelector();
            $grid->disableViewButton();
            $grid->disableRefreshButton();

            $url = route('loan.export').'?'.http_build_query(request()->all());

            $grid->tools('<a class="btn btn-primary btn-outline" href="'.$url.'" target="_blank">数据导出</a>');
        });
    }

    public function export(Request $request)
    {
        if ($request->get('type') == Loan::TYPE_PRINCIPAL) {
            $data = loanPrincipal($request->get('month'), $request->get('rate'), $request->get('total'));
        } else {
            $data = loanInterest($request->get('month'), $request->get('rate'), $request->get('total'));
        }

        $filename = $request->get('total').'-'.
            $request->get('month').'-'.
            Loan::$typeMap[$request->get('type', Loan::TYPE_PRINCIPAL)].'.xlsx';

        return Excel::download(new LoanExport($data['items']), $filename);
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
        return Show::make($id, new Loan(
            request('month'),
            request('rate'),
            request('total'),
            request('type')
        ), function (Show $show) {
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
            $show->field('total', '还款月数');
            $show->disableDeleteButton();
            $show->disableListButton();
            $show->disableEditButton();
            $show->disableQuickEdit();
        });
    }

}
