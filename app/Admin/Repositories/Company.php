<?php


namespace App\Admin\Repositories;

use App\Services\CompanyService;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;

class Company extends Repository
{
    public function get(Grid\Model $model)
    {
        $companyService = new CompanyService();

        $currentPage = $model->getCurrentPage();
        $perPage = $model->getPerPage();

        if ($keyword = request('keyword')) {
            $data = $companyService->get($keyword);
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
