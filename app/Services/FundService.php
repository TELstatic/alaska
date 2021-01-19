<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class FundService
{
    public $url = 'https://api.doctorxiong.club/v1';

    public function __construct()
    {

    }

    protected function getKey($code, $type = 'info')
    {
        return 'fund-'.date('Ymd').'-'.$code.'-'.$type;
    }

    public function getInfo($code)
    {
        $key = $this->getKey($code);

        Cache::forget($key);

        return Cache::remember($key, 24 * 60 * 60, function () use ($code) {
            $url = $this->url.'/fund';

            $params = [
                'code' => $code,
            ];

            $client = new Client();

            $response = $client->get($url, [
                'query' => $params,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $result['data'][0];
        });
    }

    public function getDetail($code)
    {
        $key = $this->getKey($code, 'detail');

        Cache::forget($key);

        return Cache::remember($key, 24 * 60 * 60, function () use ($code) {
            $url = $this->url.'/fund/detail';

            $params = [
                'code' => $code,
            ];

            $client = new Client();

            $response = $client->get($url, [
                'query' => $params,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $result['data'];
        });
    }
}
