<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Project;
use App\Services\ProjectService;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function created(Order $order)
    {
        $project = Project::query()->findOrFail($order->project_id);

        $cost = $project->orders->sum('确认金额') / $project->orders->sum('确认份额');

        $project->update([
            '持有金额'  => $project->orders->sum('确认份额') * $cost,
            '持有份额'  => $project->orders->sum('确认份额'),
            '持仓成本价' => $cost,
        ]);
    }

    /**
     * Handle the order "updated" event.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function updated(Order $order)
    {
        $project = Project::with('orders')->findOrFail($order->project_id);

        $cost = $project->orders->sum('确认金额') / $project->orders->sum('确认份额');

        $project->update([
            '持有金额'  => $project->orders->sum('确认份额') * $cost,
            '持有份额'  => $project->orders->sum('确认份额'),
            '持仓成本价' => $cost,
        ]);
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $project = Project::with('orders')->findOrFail($order->project_id);

        $cost = $project->orders->sum('确认金额') / $project->orders->sum('确认份额');

        $project->update([
            '持有金额'  => $project->orders->sum('确认份额') * $cost,
            '持有份额'  => $project->orders->sum('确认份额'),
            '持仓成本价' => $cost,
        ]);
    }

    /**
     * Handle the order "restored" event.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
