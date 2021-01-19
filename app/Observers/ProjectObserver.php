<?php

namespace App\Observers;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProjectObserver
{
    /**
     * Handle the projecty "created" event.
     *
     * @param \App\Models\Project $project
     * @return void
     */
    public function created(Project $project)
    {
        //
    }

    /**
     * Handle the projecty "updated" event.
     *
     * @param \App\Models\Project $project
     * @return void
     */
    public function updating(Project $project)
    {
        // 最新净值 或 份额 变化 重新计算 收益 和 收益率
        if ($project->isDirty(['最新净值', '持有份额'])) {
            $income = $project->getAttribute('持有份额') * $project->getAttribute('最新净值') - $project->getAttribute('持有金额');

            if ($income != $project->getAttribute('持有收益')) {
                $project->update([
                    '持有收益'  => $income,
                    '持有收益率' => floatval($income / $project->getAttribute('持有金额')),
                ]);
            }
        }
    }

    /**
     * Handle the projecty "deleted" event.
     *
     * @param \App\Models\Project $project
     * @return void
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the projecty "restored" event.
     *
     * @param \App\Models\Project $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the projecty "force deleted" event.
     *
     * @param \App\Models\Project $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }
}
