<h1 align="center"> laravel-bug-msg </h1>

<p align="center"> .</p>


## Installing

```shell
# laravel7以上使用2.0版本
$ composer require cblink/laravel-bug-msg ^2.0 -vvv

# laravel5.5以上，7以下使用1.0版本
$ composer require cblink/laravel-bug-msg ^1.0 -vvv
```

## Config

```php
<?php
return [
    'notify' => [
        /*
         * 是否每次错误都通知（建议 false，否则可能会轰炸）
         */
        'every' => false,

        /*
         * 分钟单位，该区间同一错误只提醒一次
         */
        'interval' => 5,
    ],

    'cache' => [
        'prefix' => 'notice.exception.',
    ],

    // 配置信息
    'config' => [
        // 是否开启调试
        'debug' => false,
        // 通知地址的key
        'key' => '',
        // 通知地址的认证信息
        'token' => '',
    ],
];
```

## Usage

```php
<?php

use Cblink\BugMsg\ExceptionHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler{
    
    // ...

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     *
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
       
            /* 添加这段代码即可 start */
            (new ExceptionHelper())
            /* @var array $config 引用至配置部分 */
            ->handle($exception, $config);
            /* end */
        }

        parent::report($exception);
    }
}
```


## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/cblink/laravel-bug-msg/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/cblink/laravel-bug-msg/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
