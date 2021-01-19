<?php

namespace App\Console\Commands\Cron;

use App\Models\Automatic;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zone:cron-create-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时创建定投订单';

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
     * 每天下午 4点
     *
     * @return int
     */
    public function handle()
    {
        Automatic::query()
            ->with('project')
            ->chunkById(100, function ($automatics) {
                foreach ($automatics as $automatic) {
                    $now = now();

                    switch ($automatic->type) {
                        case Automatic::TYPE_WEEK:
                            if ($now->dayOfWeek !== $automatic->day) {
                                continue;
                            }
                            break;
                        case Automatic::TYPE_FORTNIGHT:
                            if ($now->dayOfWeek !== $automatic->day) {
                                continue;
                            } else {
                                // 最近一次已定投 变更状态
                                if ($automatic->is_applied == 1) {
                                    $automatic->update([
                                        'is_applied' => 0,
                                    ]);

                                    continue;
                                } else {
                                    $automatic->update([
                                        'is_applied' => 1,
                                    ]);
                                }
                            }
                            break;
                        case Automatic::TYPE_MONTH:
                            if ($now->day !== $automatic->day) {
                                continue;
                            }
                            break;
                        default:
                        case Automatic::TYPE_DAY:
                            break;
                    }

                    // 工作日 todo 国家节假日 排除
                    if ($now->isWeekday()) {
                        if ($now->isFriday()) {
                            $confirmedAt = $now->addDays(3);
                        } else {
                            $confirmedAt = $now->addDays();
                        }
                    } else {
                        if ($now->isSaturday()) {
                            $confirmedAt = $now->addDays(2);
                        } else {
                            $confirmedAt = $now->addDays();
                        }
                    }

                    // todo 自动计算 基金 股票 确认份额 && 确认净值
                    Order::query()->create([
                        'price'        => $automatic->price,
                        'type'         => Order::TYPE_AUTO,
                        'project_id'   => $automatic->project_id,
                        '确认金额'         => $automatic->price * (1 - $automatic->project->getAttribute('买入费率')),
                        '确认份额'         => 0,
                        '确认净值'         => 0,
                        '手续费'          => $automatic->price * $automatic->project->getAttribute('买入费率'),
                        'created_at'   => $automatic->paid_at,
                        'confirmed_at' => $confirmedAt->toDate(),
                    ]);
                }
            });
    }
}
