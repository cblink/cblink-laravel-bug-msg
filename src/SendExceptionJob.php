<?php

namespace Cblink\BugMsg;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

/**
 * Class SendExceptionJob
 * @package Cblink\BugMsg
 */
class SendExceptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $requestUrl = 'notice.service.cblink.net/api/messenger';

    private $config;

    private $data;

    private $debug;

    public function __construct($config, $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $requestUrl = sprintf(
            'http://%s%s/%s',
            Arr::get($this->config, 'config.debug', false) ? 'dev-' : '',
            $this->requestUrl,
            Arr::get($this->config, 'config.key', '')
        );

        $response = (new Client())->request('POST', $requestUrl, [
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Authorization' => Arr::get($this->config, 'config.token'),
            ],
            'json' => $this->data,
            'timeout' => 30,
        ]);

        $code = $response->getStatusCode();
        $content = $response->getBody()->getContents();

        if ($code != 200) {
            logger(sprintf('bibiji request fail, statusCode %s', $code));
            return;
        }

        $body = json_decode($content, true);

        if (json_last_error() || empty($body['err_code']) || $body['err_code'] != 0) {
            logger('bibiji request fail: ', $body);
        }
    }
}