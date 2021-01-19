<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Holiday;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Markdown;

class HolidayController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Holiday(), function (Grid $grid) {
            $grid->column('year', '年份');
            $grid->column('name', '文件名');

            $grid->disableDeleteButton();
            $grid->disableBatchActions();
            $grid->disableFilterButton();
            $grid->disableEditButton();
        });
    }

    public function template(Content $content)
    {
        $filename = storage_path('app/2021.json');

        return $content
            ->header('节假日管理')
            ->description('模板')
            ->body(Card::make(Markdown::make($this->content($filename))));
    }

    public function content($filename)
    {
        try {
            return file_get_contents($filename);
        } catch (\Exception $exception) {
            abort(404);
        }
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Card
     */
    protected function detail($id)
    {
        $filename = storage_path('date/'.$id.'.json');

        return Card::make(Markdown::make($this->content($filename)));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        for ($i = -1; $i <= 1; $i++) {
            $years[now()->addYears($i)->year] = now()->addYears($i)->year;
        }

        return Form::make(new Holiday(), function (Form $form) use ($years) {
            $form->display('id');
            $form->select('year', '年份')->options($years)->required();
            $form->file('file', '配置')->required();
            $form->html('<a target="_blank" href="'.route('holiday.template').'">参考模板</a>');
            $form->html('参考模版数字含义: 0=>调休日 1=>节假日(双倍工资) 2=>节假日(三倍工资)');
            $form->html('如已选年份存在配置,新配置将会覆盖旧配置');
            $form->disableDeleteButton();
            $form->disableListButton();
            $form->disableViewCheck();
            $form->disableCreatingCheck();
            $form->disableEditingCheck();
        });
    }
}
