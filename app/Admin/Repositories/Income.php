<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;

class Income extends Repository
{
    public function detail(Show $show)
    {
        $presentValue = request('present_value', 100);
        $rate = request('rate', 5) / 100;
        $days = request('days', 365);

        $interest = $presentValue * $rate / $days * 365;

        return [
            '本金'   => $presentValue,
            '总利息'  => $interest,
            '本息合计' => $presentValue + $interest,
            '年化'   => $rate,
        ];
    }
}
