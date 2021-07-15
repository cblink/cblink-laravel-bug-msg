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

    protected $requestUrl = 'http://notice.service.cblink.net/api/messenger';

    private $config;

    private $data;

    private $debug;

    public function __construct($config, $data, bool $debug = false)
    {
        $this->config = $config;
        $this->data = $data;
        $this->debug = $debug;
    }

    public function handle()
    {
        $requestUrl = sprintf(
            '%s/%s%s',
            $this->requestUrl,
            Arr::get($this->config, 'config.key', ''),
            $this->debug ? '/debug' : ''
        );

        (new Client())->request('POST', $requestUrl, [
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Authorization' => Arr::get($this->config, 'config.token'),
            ],
            'json' => $this->data
        ]);
    }
}