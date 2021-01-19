<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;
use Illuminate\Support\Arr;

class Interest extends Repository
{
    const TYPE_FUTURE = 0;
    const TYPE_PRESENT = 1;

    public static $typeMap = [
        self::TYPE_FUTURE  => '一次性支付终值',
        self::TYPE_PRESENT => '一次性支付现值',
    ];

    public function detail(Show $show)
    {
        $value = request('value', 100);
        $rate = request('rate', 5) / 100;
        $years = request('years');

        if (request('type', Interest::TYPE_FUTURE) == Interest::TYPE_FUTURE) {
            $data = calcFutureValue($value, $rate, $years);
        } else {
            $data = calcPresentValue($value, $rate, $years);
        }

        return Arr::only($data, [
            '本金',
            '总利息',
            '本息合计',
            'total',
        ]);
    }

    public function get(Grid\Model $model)
    {
        $currentPage = $model->getCurrentPage();
        $perPage = $model->getPerPage();

        $value = request('value', 100);
        $rate = request('rate', 5) / 100;
        $years = request('years');

        if (request('type', Interest::TYPE_FUTURE) == Interest::TYPE_FUTURE) {
            $data = calcFutureValue($value, $rate, $years);
        } else {
            $data = calcPresentValue($value, $rate, $years);
        }

        $offset = ($currentPage - 1) * $perPage;

        $items = array_splice($data['items'], $offset, $perPage);

        return $model->makePaginator(
            $data['total'],
            $items
        );
    }
}
