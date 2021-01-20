<?php


namespace App\Services;

use App\Models\Automatic;
use App\Models\Order;
use GuzzleHttp\Client;

class OrderService
{
    public function getConfirmedAmount($price, $value)
    {
        return round($price / $value, 2);
    }

    public function getCurrentValue($url)
    {
        $client = new Client();

        try {
            $response = $client->get($url);

            $result = json_decode($response->getBody()->getContents(), true);

            if ($result['success']) {
                return $result['data']['current_value'];
            }

            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function store(Automatic $automatic)
    {
        $now = now();

        $automatic->load('project');

        if (!$automatic->project->url) {
            return false;
        }

        if ($value = $this->getCurrentValue($automatic->project->url)) {
            $charge = $automatic->price * $automatic->project->getAttribute('买入费率');

            Order::query()->create([
                'price'        => $automatic->price,
                'type'         => Order::TYPE_AUTO,
                'project_id'   => $automatic->project_id,
                '确认金额'         => $automatic->price - $charge,
                '确认份额'         => $this->getConfirmedAmount($automatic->price - $charge, $value),
                '确认净值'         => $value,
                '手续费'          => $charge,
                'created_at'   => $now,
                'confirmed_at' => $now,
            ]);
        }

        return false;
    }
}
