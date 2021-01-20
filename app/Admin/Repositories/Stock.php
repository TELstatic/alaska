<?php

namespace App\Admin\Repositories;

use App\Services\StockService;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Markdown;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Stock extends Repository
{
    public $stockService;

    public function __construct()
    {
        $this->stockService = new StockService();
    }

    public function detail(Show $show)
    {
        return $this->stockService->getDetail(
            request('code',600900),
            request('exchange')
            );
    }

    public function get(Grid\Model $model)
    {
        $currentPage = $model->getCurrentPage();
        $perPage = $model->getPerPage();

        $data = $this->stockService->getBoard();

        $offset = ($currentPage - 1) * $perPage;

        $data = array_splice($data, $offset, $perPage);

        return $model->makePaginator(
            count($data),
            $data
        );
    }
}
