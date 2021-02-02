<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\CompanyForm;
use App\Admin\Repositories\Company;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;

class CompanyController extends AdminController
{
    public function index(Content $content)
    {
        // 总成本 总收益 总收益率 总项目个数
        return $content
            ->header('工具')
            ->description('基金公司查询')
            ->body(new Card(new CompanyForm()))
            ->body($this->grid());
    }

    protected function grid()
    {
        return Grid::make(new Company(), function (Grid $grid) {
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
