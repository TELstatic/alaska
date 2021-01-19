<?php

namespace App\Admin\Forms;

use App\Admin\Repositories\Loan;
use Dcat\Admin\Widgets\Form;

class StockForm extends Form
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
        $exchanges = [
            'sh' => '上交所',
            'sz' => '深交所',
        ];

        $this->text('code', '股票代码');
        $this->select('exchange', '交易所')
            ->default('sh')
            ->options($exchanges);
    }

    public function default()
    {
        return [
            'code'     => request('code', '600900'),
            'exchange' => request('exchange', 'sh'),
        ];
    }
}
