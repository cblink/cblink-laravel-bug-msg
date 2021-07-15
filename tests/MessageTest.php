<?php

namespace Cblink\BugMsg\Tests;

use Cblink\BugMsg\SendExceptionJob;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{

    public function test_message()
    {
        $config = require_once dirname(__DIR__).'/config/notice_exception.php';

        try {
            throw new \LogicException('test', 500);
        }catch (\Exception $exception){
            $job = new SendExceptionJob($config,
                [
                    'env' => 'local',
                    'app_name' => 'default-app',
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'url' => 'http://www.baidu.com/test/123',
                    'exception' => get_class($exception),
                    'trace' => $exception->getTraceAsString(),
                ]);

            $job->handle();
        }

        $this->assertTrue(true);
    }

}