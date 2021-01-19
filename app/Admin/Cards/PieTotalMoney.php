<?php

namespace App\Admin\Cards;

use App\Admin\Repositories\Loan;
use App\Models\Project;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\LazyTable;
use Dcat\Admin\Widgets\Metrics\Bar;
use Dcat\Admin\Widgets\Metrics\Card;
use Dcat\Admin\Widgets\Metrics\Donut;
use Dcat\Admin\Widgets\Radio;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class PieTotalMoney extends LazyTable
{
   public function title(){
       return '';
   }

    public function render()
    {
        // 获取外部传递的参数
        $id = $this->id;

        // 查询数据逻辑
        $data = loanPrincipal(360,0.0466,1000000);

        $name = 'type';

        $options = Loan::$typeMap;

        return $radio = Radio::make($name, $options)->inline()->check(1);
    }


}
