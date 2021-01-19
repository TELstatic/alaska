<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class NacosTools extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nacos {action?}';

    private $accessKey;
    private $secretKey;
    private $endpoint = 'acm.aliyun.com';
    private $namespace;
    private $dataId;
    private $group;
    private $port = 8080;
    private $client;

    private $serverUrl;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nacos 管理工具';

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
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $this->accessKey = env('NACOS_ACCESS_KEY');
        $this->secretKey = env('NACOS_SECRET_KEY');
        $this->endpoint = env('NACOS_ENDPOINT');
        $this->namespace = env('NACOS_NAMESPACE');
        $this->port = env('NACOS_PORT', $this->port);
        $this->dataId = env('NACOS_DATA_ID');
        $this->group = env('NACOS_GROUP');

        if (!$this->validate()) {
            $this->error('请检查配置参数');

            return;
        }

        $this->client = new Client(['verify' => false]);

        $this->info('Nacos 配置工具');

        $actions = [
            '获取配置',
            '发布配置',
            '删除配置',
        ];

        if (is_null($this->argument('action'))) {
            $action = $this->choice('请选择操作',
                $actions,
                $actions[0]);
        } else {
            if (in_array($this->argument('action'), array_keys($actions))) {
                $action = $actions[$this->argument('action')];
            } else {
                $action = $this->choice('请选择操作',
                    $actions,
                    $actions[0]);
            }
        }

        $this->do($action);
    }

    public function do($action = '获取配置')
    {
        switch ($action) {
            default:
            case '获取配置':
                $config = $this->getConfig();

                if ($config) {
                    file_put_contents('.env', $config);
                    $this->info('获取配置成功');
                } else {
                    $this->error('获取配置失败');
                }

                break;
            case '发布配置':
                if ($this->publishConfig()) {
                    $this->info('发布配置成功');
                } else {
                    $this->error('发布配置失败');
                }

                break;

            case '删除配置':
                if ($this->removeConfig()) {
                    $this->info('删除配置成功');
                } else {
                    $this->error('删除配置失败');
                }

                break;
        }
    }

    /**
     * 验证配置参数
     *
     * Date: 2020/6/10
     * @return bool
     */
    private function validate()
    {
        $data = [
            'accessKey' => $this->accessKey,
            'secretKey' => $this->secretKey,
            'endpoint'  => $this->endpoint,
            'namespace' => $this->namespace,
            'dataId'    => $this->dataId,
            'group'     => $this->group,
        ];

        $rules = [
            'accessKey' => 'required',
            'secretKey' => 'required',
            'endpoint'  => 'required',
            'namespace' => 'required',
            'dataId'    => 'required',
            'group'     => 'required',
        ];

        $messages = [
            'accessKey.required' => '请填写`.env`配置 NACOS_ACCESS_KEY',
            'secretKey.required' => '请填写`.env`配置 NACOS_SECRET_KEY',
            'endpoint.required'  => '请填写`.env`配置 NACOS_ENDPOINT',
            'namespace.required' => '请填写`.env`配置 NACOS_NAMESPACE',
            'dataId.required'    => '请填写`.env`配置 NACOS_DATA_ID',
            'group.required'     => '请填写`.env`配置 NACOS_GROUP',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->toArray() as $item) {
                foreach ($item as $value) {
                    $this->error($value);
                }
            }

            return false;
        }

        return true;
    }

    /**
     * 获取配置
     *
     * Date: 2020/6/10
     * @return bool
     */
    private function getConfig()
    {
        $acmHost = str_replace(['host', 'port'], [$this->getServer(), $this->port],
            'http://host:port/diamond-server/config.co');

        $query = [
            'dataId' => urlencode($this->dataId),
            'group'  => urlencode($this->group),
            'tenant' => urlencode($this->namespace),
        ];

        $headers = $this->getHeaders();

        $response = $this->client->get($acmHost, [
            'headers' => $headers,
            'query'   => $query,
        ]);

        if ($response->getReasonPhrase() == 'OK') {
            return $response->getBody()->getContents();
        } else {
            return false;
        }
    }

    /**
     * 发布配置
     *
     * Date: 2020/6/10
     * @return bool
     */
    public function publishConfig()
    {
        $acmHost = str_replace(
            ['host', 'port'],
            [$this->getServer(), $this->port],
            'http://host:port/diamond-server/basestone.do?method=syncUpdateAll');

        $headers = $this->getHeaders();

        $formParams = [
            'dataId'  => urlencode($this->dataId),
            'group'   => urlencode($this->group),
            'tenant'  => urlencode($this->namespace),
            'content' => file_get_contents('.env'),
        ];

        $response = $this->client->post($acmHost, [
            'headers'     => $headers,
            'form_params' => $formParams,
        ]);

        $result = json_decode($response->getBody()->getContents(), 1);

        return $result['message'] == 'OK';
    }

    public function removeConfig()
    {
        $acmHost = str_replace(['host', 'port'], [$this->getServer(), $this->port],
            'http://host:port/diamond-server//datum.do?method=deleteAllDatums');

        $headers = $this->getHeaders();

        $formParams = [
            'dataId' => urlencode($this->dataId),
            'group'  => urlencode($this->group),
            'tenant' => urlencode($this->namespace),
        ];

        $response = $this->client->post($acmHost, [
            'headers'     => $headers,
            'form_params' => $formParams,
        ]);

        $result = json_decode($response->getBody()->getContents(), 1);

        return $result['message'] == 'OK';
    }

    /**
     * 获取配置服务器地址
     *
     * Date: 2020/6/10
     * @return string
     */
    private function getServer()
    {
        if ($this->serverUrl) {
            return $this->serverUrl;
        }

        $serverHost = str_replace(
            ['host', 'port'],
            [$this->endpoint, $this->port],
            'http://host:port/diamond-server/diamond');

        $response = $this->client->get($serverHost);

        return $this->serverUrl = rtrim($response->getBody()->getContents(), PHP_EOL);
    }

    /**
     * 获取请求头
     *
     * Date: 2020/6/10
     * @return array
     */
    private function getHeaders()
    {
        $headers = [
            'Diamond-Client-AppName' => 'ACM-SDK-PHP',
            'Client-Version'         => '0.0.1',
            'Content-Type'           => 'application/x-www-form-urlencoded; charset=utf-8',
            'exConfigInfo'           => 'true',
            'Spas-AccessKey'         => $this->accessKey,
            'timeStamp'              => round(microtime(true) * 1000),
        ];

        $headers['Spas-Signature'] = $this->getSign($headers['timeStamp']);

        return $headers;
    }

    /**
     * 获取签名
     *
     * @param $timeStamp
     * Date: 2020/6/10
     * @return string
     */
    private function getSign($timeStamp)
    {
        $signStr = $this->namespace.'+';

        if (is_string($this->group)) {
            $signStr .= $this->group."+";
        }

        $signStr = $signStr.$timeStamp;

        return base64_encode(hash_hmac(
            'sha1',
            $signStr,
            $this->secretKey,
            true
        ));
    }
}
