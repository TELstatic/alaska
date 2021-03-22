<?php

namespace App\Console\Commands;

use App\User;
use Dcat\Admin\Models\Menu;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\DB;

class CreateMenus extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zone:create-menus {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建菜单';

    public $order = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return 1;
        }

        $data = [
            [
                'title'     => 'Index',
                'icon'      => 'feather icon-bar-chart-2',
                'uri'       => '/',
                'extension' => '',
                'show'      => 1,
            ],
            [
                'title'     => '工具',
                'icon'      => 'fa-anchor',
                'uri'       => '#',
                'extension' => '',
                'show'      => 1,
                'children'  => [
                    [
                        'title'     => '贷款计算器',
                        'icon'      => '',
                        'uri'       => '/loan',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => '复利计算器',
                        'icon'      => '',
                        'uri'       => '/interest',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => '年化计算器',
                        'icon'      => '',
                        'uri'       => '/annualized',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => '收益计算器',
                        'icon'      => '',
                        'uri'       => '/income',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => '股票大盘信息',
                        'icon'      => '',
                        'uri'       => '/stock/board',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => '基金查询',
                        'icon'      => '',
                        'uri'       => '/fund',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => '股票查询',
                        'icon'      => '',
                        'uri'       => '/stock',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => '基金经理查询',
                        'icon'      => '',
                        'uri'       => '/manager',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => '基金公司查询',
                        'icon'      => '',
                        'uri'       => '/company',
                        'extension' => '',
                        'show'      => 1,
                    ],
                ],
            ],
            [
                'title'     => '投资分类',
                'icon'      => 'fa-align-left',
                'uri'       => '/catalog',
                'extension' => '',
                'show'      => 1,
            ],
            [
                'title'     => '我的账号',
                'icon'      => 'fa-address-book-o',
                'uri'       => '/account',
                'extension' => '',
                'show'      => 1,
            ],
            [
                'title'     => '投资项目',
                'icon'      => 'fa-adn',
                'uri'       => '/project',
                'extension' => '',
                'show'      => 1,
            ],
            [
                'title'     => '我的订单',
                'icon'      => 'fa-amazon',
                'uri'       => '/order',
                'extension' => '',
                'show'      => 1,
            ],
            [
                'title'     => '我的定投',
                'icon'      => 'fa-balance-scale',
                'uri'       => '/automatic',
                'extension' => '',
                'show'      => 1,
            ],
            [
                'title'     => '我的自选',
                'icon'      => 'fa-star-o',
                'uri'       => '/favorite',
                'extension' => '',
                'show'      => 1,
            ],
            [
                'title'     => '货币类型',
                'icon'      => 'fa-cny',
                'uri'       => '/currency',
                'extension' => '',
                'show'      => 1,
            ],
            [
                'title'     => '配置中心',
                'icon'      => 'fa-codepen',
                'uri'       => '#',
                'extension' => '',
                'show'      => 1,
                'children'  => [
                    [
                        'title'     => '节假日',
                        'icon'      => 'fa-cny',
                        'uri'       => '/holiday',
                        'extension' => '',
                        'show'      => 1,
                    ],
                ],
            ],
            [
                'title'     => 'Admin',
                'icon'      => 'feather icon-settings',
                'uri'       => '#',
                'extension' => '',
                'show'      => 1,
                'children'  => [
                    [
                        'title'     => 'Users',
                        'icon'      => '',
                        'uri'       => '/auth/users',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => 'Roles',
                        'icon'      => '',
                        'uri'       => '/auth/roles',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => 'Permission',
                        'icon'      => '',
                        'uri'       => '/auth/permissions',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => 'Menu',
                        'icon'      => '',
                        'uri'       => '/auth/menu',
                        'extension' => '',
                        'show'      => 1,
                    ],
                    [
                        'title'     => 'Operation log',
                        'icon'      => '',
                        'uri'       => '/auth/logs',
                        'extension' => '',
                        'show'      => 0,
                    ],
                ],
            ],
            [
                'title'     => '打赏',
                'icon'      => 'fa-gratipay',
                'uri'       => '/reward',
                'extension' => '',
                'show'      => 1,
            ],
        ];

        $bar = $this->output->createProgressBar(count($data));

        DB::statement('truncate admin_menu');

        foreach ($data as $datum) {
            $data = [
                'icon'      => $datum['icon'],
                'uri'       => $datum['uri'],
                'title'     => $datum['title'],
                'order'     => ++$this->order,
                'show'      => $datum['show'] ?? 1,
                'parent_id' => 0,
            ];

            $menu = Menu::query()->create($data);

            $this->build($datum['children'] ?? [], $menu);

            $bar->advance();
        }

        (new Menu())->flushCache();

        $bar->finish();
        $this->info('');
        $this->info('菜单创建完成');
    }

    public function build($data, Menu $menu)
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $datum) {
            $item = [
                'icon'      => $datum['icon'] ?? '',
                'uri'       => $datum['uri'],
                'title'     => $datum['title'],
                'show'      => $datum['show'] ?? 1,
                'order'     => ++$this->order,
                'parent_id' => $menu->id,
            ];

            $this->build($datum['children'] ?? [],
                Menu::query()->create($item));
        }
    }
}
