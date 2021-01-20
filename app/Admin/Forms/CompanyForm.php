<?php

namespace App\Admin\Forms;

use App\Admin\Repositories\Loan;
use Dcat\Admin\Widgets\Form;

class CompanyForm extends Form
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
        $this->text('keyword', '基金公司');
    }

    public function default()
    {
        return [
            'keyword' => request('keyword'),
        ];
    }
}
