<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Project;

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

        $project->update([
            '最新净值' => $order->getAttribute('确认净值'),
            '持有金额' => $project->getAttribute('持有金额') + $order->getAttribute('price'),
            '持有份额' => $project->getAttribute('持有份额') + $order->getAttribute('确认份额'),
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

        $project->update([
            '持有金额' => $project->orders->sum('price'),
            '持有份额' => $project->orders->sum('确认份额'),
            '最新净值' => $order->getAttribute('确认净值'),
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

        $project->update([
            '持有金额' => $project->orders->sum('price'),
            '持有份额' => $project->orders->sum('确认份额'),
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
