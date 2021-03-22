<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Project;

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
        Order::query()->create([
            'price'        => $project->getAttribute('持有金额'),
            'type'         => Order::TYPE_BUY,
            'project_id'   => $project->id,
            '确认金额'         => $project->getAttribute('持有金额'),
            '确认份额'         => $project->getAttribute('持有份额'),
            '确认净值'         => $project->getAttribute('持仓成本价'),
            '手续费'          => 0,
            'confirmed_at' => $project->created_at,
        ]);
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
            $income = $project->getAttribute('持有份额') * ($project->getAttribute('最新净值') - $project->getAttribute('持仓成本价'));
            $incomeRate = $project->getAttribute('最新净值') - $project->getAttribute('持仓成本价') / $project->getAttribute('持仓成本价');

            if ($income != $project->getAttribute('持有收益')) {
                $project->update([
                    '持有收益'  => $income,
                    '持有收益率' => floatval($incomeRate),
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
