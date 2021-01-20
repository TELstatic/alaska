<?php

namespace App\Admin\Controllers;

use App\Models\Catalog;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class CatalogController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Catalog(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('logo')
                ->image('',50,50);
            $grid->column('risk_level')->select(Catalog::$riskLevelMap);
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('name');
            });
        });
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
        return Show::make($id, new Catalog(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('logo')->image('',50,50);
            $show->field('risk_level')->using(Catalog::$riskLevelMap);
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Catalog(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->image('logo')->saveFullUrl();
            $form->select('risk_level')
                ->options(Catalog::$riskLevelMap)
                ->default(Catalog::RISK_LEVEL_R1)->required();

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
