<?php

namespace App\Admin\Controllers;

use App\Models\Account;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Tab;

class RewardController extends AdminController
{
    public function index(Content $content)
    {
        return $content
            ->header('打赏')
            ->description('Thanks')
            ->row(function (Row $row) {
                $type = request('type', 1);

                $tab = new Tab();

                $title = '感谢有您的支持，Alaska 才能一直保持更新，增加更多功能。';

                if ($type == 1) {
                    $tab->add('支付宝', new Card($title,
                        '<img src="https://cdn.telstatic.xyz/sponsors/alipay.jpg" width="200" height="300">'));
                    $tab->addLink('微信', request()->fullUrlWithQuery(['type' => 2]));
                } else {
                    $tab->addLink('支付宝', request()->fullUrlWithQuery(['type' => 1]));
                    $tab->add('微信', new Card($title,
                        '<img src="https://cdn.telstatic.xyz/sponsors/wechat.jpg" width="200" height="300">'), true);
                }

                $row->column(12, $tab->withCard());
            });
    }
}
