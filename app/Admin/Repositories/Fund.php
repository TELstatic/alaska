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
        return $this->fundService->getDetail(request('code',202015));
    }

    public function get(Grid\Model $model)
    {
        $currentPage = $model->getCurrentPage();
        $perPage = $model->getPerPage();

        $files = scandir(storage_path('date'));

        unset($files[0]);
        unset($files[1]);

        sort($files);

        $data = [];

        foreach ($files as $file) {
            $data[] = [
                'id'   => str_replace('.json', '', $file),
                'year' => str_replace('.json', '', $file),
                'name' => $file,
            ];
        }

        $offset = ($currentPage - 1) * $perPage;

        $data = array_splice($data, $offset, $perPage);

        return $model->makePaginator(
            count($data),
            $data
        );
    }
}