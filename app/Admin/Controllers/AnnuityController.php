<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Annualized;
use App\Models\Order;
use App\Models\Project;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Form;

class AnnuityController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('年金险计算器')
            ->body($this->form())
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
        return Show::make($id, new Annualized(), function (Show $show) {
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

    public function form()
    {
        return Form::make(Order::with(['project']), function (Form $form) {
            $form->title(' ');
            $form->display('id');
            $form->text('price','初始投入资金')->required();

            $form->table('items','资金流',function (Form\NestedForm $nestedForm){
                $nestedForm->html('<div class="item_index" style="margin-top: 15px"></div>','序号');
                $nestedForm->number('in','流入资金');
                $nestedForm->number('out','流出资金');
            })->useTable();

            $form->html('参考资料: <a href="https://zhuanlan.zhihu.com/p/105482729" target="_blank">如何计算年金保险收益率</a>');

            $form->disableViewButton();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableViewCheck();
            $form->disableListButton();
        });
    }
}
