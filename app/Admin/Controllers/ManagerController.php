<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\ManagerForm;
use App\Admin\Repositories\Manager;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;

class ManagerController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('基金经理查询')
            ->body(new Card(new ManagerForm()))
            ->body($this->grid());
    }

    protected function grid()
    {
        return Grid::make(new Manager(), function (Grid $grid) {
            $grid->column('MgrName', '基金经理')->display(function ($name) {
                return '<a target="_blank" href="//fund.eastmoney.com/manager/'.$this->MgrId.'.html?spm=search">'.$name.'</a>';
            });
            $grid->column('JJGS', '基金公司')->display(function ($name) {
                return '<a target="_blank" href="//fund.eastmoney.com/company/'.$this->JJGSID.'.html?spm=search">'.$name.'</a>';
            });
            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->disableDeleteButton();
            $grid->disableActions();
        });
    }
}
