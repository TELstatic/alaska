<?php


namespace App\Services;

use GuzzleHttp\Client;

class CompanyService
{
    public $url = 'https://fundsuggest.eastmoney.com/FundSearch/api/FundSearchAPI.ashx?m=8&key=';

    public function get($keyword)
    {
        $url = $this->url.$keyword;

        $client = new Client();

        $response = $client->get($url);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['Datas'];
    }
}
