<?php

namespace App\Admin\Repositories;

use App\Services\FundService;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Markdown;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Fund extends Repository
{
    public $fundService;

    public function __construct()
    {
        $this->fundService = new FundService();
    }

    public function detail(Show $show)
    {
//        $code = $show->getKey();

        $code = request('code', 202015);

        return $this->fundService->getDetail($code);
    }

    public function get(Grid\Model $model)
    {
        $currentPage = $model->getCurrentPage();
        $perPage = $model->getPerPage();

        $code = request('code', 202015);

        $data = $this->fundService->getInfo($code);

        $offset = ($currentPage - 1) * $perPage;

        $data = array_splice($data, $offset, $perPage);

        return $model->makePaginator(
            count($data),
            $data
        );
    }
}
