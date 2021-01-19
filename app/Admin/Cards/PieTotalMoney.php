<?php

namespace App\Admin\Cards;

use App\Admin\Repositories\Loan;
use Dcat\Admin\Widgets\LazyTable;
use Dcat\Admin\Widgets\Radio;

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
