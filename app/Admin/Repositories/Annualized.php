<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;

class Annualized extends Repository
{
    public function detail(Show $show)
    {
        $presentValue = request('present_value', 100);
        $featureValue = request('feature_value', 200);
        $days = request('days', 365);

        $rate = ($featureValue - $presentValue) / $presentValue / $days * 365;

        return [
            '本金'   => $presentValue,
            '总利息'  => $featureValue - $presentValue,
            '本息合计' => $featureValue,
            '年化'   => $rate,
        ];
    }
}
