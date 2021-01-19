<?php

namespace App\Console\Commands\Cron;

use App\Jobs\FetchProjectValue;
use App\Models\Project;
use Illuminate\Console\Command;

class SyncProjectValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zone:cron-sync-project-value';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时更新股票基金最新净值';

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
        Project::query()
            ->where('持有金额', '>', 0)
            ->whereNotNull('url')
            ->chunkById(100, function ($projects) {
                foreach ($projects as $project) {
                    dispatch(new FetchProjectValue($project));
                }
            });
    }
}
