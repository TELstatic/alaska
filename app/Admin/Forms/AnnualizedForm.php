<?php

namespace App\Admin\Forms;

use App\Admin\Repositories\Interest;
use App\Admin\Repositories\Loan;
use Dcat\Admin\Widgets\Form;

class AnnualizedForm extends Form
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

        $url = $url.'?'.http_build_query($input);

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
        $this->number('present_value', '本金')->required();
        $this->number('feature_value', '本息合计')->required();
        $this->number('days', '存款天数')->required();
    }

    public function default()
    {
        return [
            'present_value' => request('present_value', 100),
            'feature_value' => request('feature_value', 200),
            'days'         => request('days', 365),
        ];
    }
}
