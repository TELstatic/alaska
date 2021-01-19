<?php

namespace App\Admin\Forms;

use App\Admin\Repositories\Loan;
use Dcat\Admin\Widgets\Form;

class LoanForm extends Form
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
        $this->number('month', '贷款月数')
            ->min(1)
            ->max(360)
            ->required();
        $this->number('total', '贷款总额')
            ->required();
        $this->rate('rate', '贷款利率')
            ->required();
        $this->radio('type', '还款方式')
            ->options(Loan::$typeMap)
            ->required();

        $this->html('等额本金法与等额本息法并没有很大的优劣之分，大部分是根据每个人的现状和需求而定的。');
        $this->html('<a target="_blank" href="https://zhuanlan.zhihu.com/p/61140535">等额本息和等额本金的区别！</a>');
    }

    public function default()
    {
        return [
            'month' => request('month', 360),
            'rate'  => request('rate', 4.66),
            'total' => request('total', 1000000),
            'type'  => request('type', Loan::TYPE_PRINCIPAL),
        ];
    }
}
