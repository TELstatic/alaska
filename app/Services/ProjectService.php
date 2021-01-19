<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    protected $url;

    protected $yesterdayValue;
    protected $currentValue;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function fetch()
    {
        $client = new Client();

        try {
            $response = $client->get($this->url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36',
                ],
            ]);
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());

            return false;
        }

        $result = json_decode($response->getBody()->getContents(), true);

        if (isset($result['success']) && $result['success']) {
            $this->parse($result);

            return true;
        }

        return false;
    }

    protected function parse($result)
    {
        $this->yesterdayValue = $result['data']['last_value'];
        $this->currentValue = $result['data']['current_value'];
    }

    public function getYesterdayValue()
    {
        return $this->yesterdayValue;
    }

    public function getCurrentValue()
    {
        return $this->currentValue;
    }
}
