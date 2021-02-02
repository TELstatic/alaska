<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Markdown;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Holiday extends Repository
{
    public function store(Form $form)
    {
        $file = storage_path('date/'.request('year').'.json');

        $originFile = storage_path('app/public/'.request('file'));

        file_put_contents($file, file_get_contents($originFile));

        return file_exists($file);
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

        if (count($files) > 0) {
            foreach ($files as $file) {
                $data[] = [
                    'id'   => str_replace('.json', '', $file),
                    'year' => str_replace('.json', '', $file),
                    'name' => $file,
                ];
            }
        }

        $offset = ($currentPage - 1) * $perPage;

        $data = array_splice($data, $offset, $perPage);

        return $model->makePaginator(
            count($data),
            $data
        );
    }
}
