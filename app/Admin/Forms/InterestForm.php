<?php

namespace App\Admin\Forms;

use App\Admin\Repositories\Interest;
use App\Admin\Repositories\Loan;
use Dcat\Admin\Widgets\Form;

class InterestForm extends Form
{
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        $url = current(explode('?', request('_current_')));

        $url .= '?'.http_build_query($input);

        return $this
            ->response()
            ->redirect($url)
            ->success('Success');
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->select('type', '计算类型')
            ->options(Interest::$typeMap)
            ->default(Interest::TYPE_FUTURE)->required();

        $this->number('value', '计算值')->required();
        $this->number('years', '存入年限')->required();
        $this->rate('rate', '年化利率')->required();
    }

    public function default()
    {
        return [
            'type'  => request('type', Interest::TYPE_FUTURE),
            'value' => request('value', 100),
            'rate'  => request('rate', 5),
            'years' => request('years', 10),
        ];
    }
}
