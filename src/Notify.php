<?php

namespace Cblink\BugMsg;

use GuzzleHttp\Client;

/**
 * Class Notify
 * @package Cblink\BugMsg
 */
class Notify
{
    /**
     * @var string
     */
    protected $scheme = 'http';

    /**
     * @var string
     */
    protected $requestUrl = 'notice.service.cblink.net/api/messenger';

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var string
     */
    protected $customUrl;

    /**
     * Notify constructor.
     * @param false $debug
     */
    public function __construct(bool $debug = false, $customUrl = null)
    {
        $this->debug = $debug;
        $this->customUrl = $customUrl;
    }

    /**
     * @param bool $debug
     * @return Notify
     */
    public function setDebug(bool $debug): Notify
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @param $key
     * @param array $data
     * @param string|null $token
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($key, array $data = [], string $token = null): \Psr\Http\Message\ResponseInterface
    {
        $requestUrl = sprintf(
            '%s://%s/%s',
            $this->scheme,
            $this->customUrl ?: ($this->debug ? 'dev-' : '') . $this->requestUrl,
            $key
        );

        return (new Client())->request('POST', $requestUrl, [
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Authorization' => $token,
            ],
            'json' => $data,
            'timeout' => 30,
        ]);
    }
}