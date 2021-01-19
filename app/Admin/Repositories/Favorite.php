<?php

namespace App\Admin\Repositories;

use App\Services\FundService;
use App\Services\StockService;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Markdown;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Favorite extends Repository
{
    public $fundService;
    public $stockService;

    public function __construct()
    {
        $this->fundService = new FundService();
        $this->stockService = new StockService();
    }

    public function detail(Show $show)
    {
        return $this->fundService->getDetail(request('code', 202015));
    }

    public function get(Grid\Model $model)
    {
        $perPage = $model->getPerPage();

        $type = request('_selector.type', \App\Models\Favorite::TYPE_FUND);

        $favorites = \App\Models\Favorite::query()
            ->where('type', $type)
            ->paginate($perPage);

        foreach ($favorites as $favorite) {
            if ($favorite->type === \App\Models\Favorite::TYPE_FUND) {
                $favorite['info'] = $this->fundService->getDetail($favorite->code);
            } else {
                $favorite['info'] = $this->stockService->getDetail($favorite->code, '');
            }
        }

        return $favorites;
    }
}
