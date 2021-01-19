<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class Loan extends Repository
{
    public $month;
    public $rate;
    public $total;
    public $type;

    public function __construct(
        $month = 0,
        $rate = 0,
        $total = 0,
        $type = Loan::TYPE_PRINCIPAL
    ) {
        $this->month = $month;
        $this->rate = $rate;
        $this->total = $total;
        $this->type = $type;
    }

    const TYPE_PRINCIPAL = 0;
    const TYPE_INTEREST = 1;

    public static $typeMap = [
        self::TYPE_PRINCIPAL => '等额本金',
        self::TYPE_INTEREST  => '等额本息',
    ];

    public function detail(Show $show)
    {
        if ($this->type == Loan::TYPE_PRINCIPAL) {
            $data = loanPrincipal($this->month, $this->rate, $this->total);
        } else {
            $data = loanInterest($this->month, $this->rate, $this->total);
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

        if ($this->type == Loan::TYPE_PRINCIPAL) {
            $data = loanPrincipal($this->month, $this->rate, $this->total);
        } else {
            $data = loanInterest($this->month, $this->rate, $this->total);
        }

        $offset = ($currentPage - 1) * $perPage;

        $items = array_splice($data['items'], $offset, $perPage);

        return $model->makePaginator(
            $data['total'],
            $items
        );
    }
}
