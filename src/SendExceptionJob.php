<?php

namespace Cblink\BugMsg;

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
        $response = (new Notify(Arr::get($this->config, 'config.debug', false)))
            ->send(
                Arr::get($this->config, 'config.key', ''),
                $this->data,
                Arr::get($this->config, 'config.token')
            );

        $code = $response->getStatusCode();
        $content = $response->getBody()->getContents();

        if ($code != 200) {
            logger(sprintf('bibiji request fail, statusCode %s', $code));
            return;
        }

        $body = json_decode($content, true);

        if (json_last_error() || empty($body['err_code']) || $body['err_code'] != 0) {
            logger('bibiji request fail: ', [
                'request' => $this->data,
                'response' => $body,
            ]);
        }
    }
}