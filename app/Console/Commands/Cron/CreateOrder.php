<?php

namespace App\Console\Commands\Cron;

use App\Models\Automatic;
use App\Services\HolidayService;
use App\Services\OrderService;
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
     * @throws \Exception
     */
    public function handle()
    {
        $holidayService = new HolidayService(now());
        $orderService = new OrderService();

        Automatic::query()
            ->with('project')
            ->chunkById(100, function ($automatics) use ($holidayService, $orderService) {
                foreach ($automatics as $automatic) {
                    $now = now();

                    if (isset($automatic->next_applied_at) &&
                        Carbon::parse($automatic->next_applied_at)->toDateString() == $now->toDateString()) {
                        $orderService->store($automatic);

                        $automatic->update([
                            'next_applied_at' => null,
                        ]);

                        continue;
                    }

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

                    if ($holidayService->isLegalDay()) {
                        $orderService->store($automatic);
                    } else {
                        $automatic->update([
                            'next_applied_at' => $holidayService
                                ->getNextWeekday()
                                ->toDateTimeString(),
                        ]);
                    }
                }
            });
    }
}
