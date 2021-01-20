<?php


namespace App\Admin\Repositories;

use App\Services\ManagerService;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Grid;

class Manager extends Repository
{
    public function get(Grid\Model $model)
    {
        $managerService = new ManagerService();

        $currentPage = $model->getCurrentPage();
        $perPage = $model->getPerPage();

        if ($keyword = request('keyword')) {
            $data = $managerService->get($keyword);
        } else {
            $data = [];
        }

        $offset = ($currentPage - 1) * $perPage;

        $items = array_splice($data, $offset, $perPage);

        return $model->makePaginator(
            count($data),
            $items
        );
    }
}
