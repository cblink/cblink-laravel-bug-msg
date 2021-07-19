<?php

namespace Cblink\BugMsg;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ExceptionHelper
{
    /**
     * @param Exception $exception
     * @param array $config
     */
    public function handle(Exception $exception, array $config = [])
    {
        if (!Arr::get($config, 'notify.every', false)) {

            if (Cache::get($this->cacheKey($config, $exception))){
                return;
            }

            Cache::put(
                $this->cacheKey($config, $exception),
                1,
                Carbon::now()->addMinutes(Arr::get($config, 'notify.interval', 5))
            );
        }

        $this->notify($exception, $config);
    }

    /**
     * @param Exception $exception
     * @param $config
     */
    public function notify(Exception $exception, $config)
    {
        SendExceptionJob::dispatch(
            $config,
            [
                'env' => config('app.env', 'production'),
                'app_name' => config('app.name', 'default'),
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'url' => request()->fullUrl(),
                'exception' => get_class($exception),
                'trace' => $exception->getTraceAsString(),
            ]
        );
    }

    /**
     * @param array $config
     * @param Exception $exception
     * @return string
     */
    private function cacheKey(array $config, Exception $exception): string
    {
        return Arr::get($config, 'cache.prefix', 'laravel_notify').get_class($exception).$exception->getLine();
    }


}