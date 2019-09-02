<?php

/*
 * This file is part of the mouyong/puyingcloud-sdk.
 *
 * (c) 牟勇 <my24251325@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\Tongbushi;

use Monolog\Logger;
use Hanson\Foundation\Log;
use Hanson\Foundation\Foundation;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\HandlerInterface;
use Cblink\Tongbushi\Providers\LoggerServiceProvider;
use Cblink\Tongbushi\Providers\TongbushiServiceProvider;


/**
 * @property \Hanson\Foundation\Log $logger
 * @property Client $tongbushi
 */
class Tongbushi extends Foundation
{
    protected $providers = [
        LoggerServiceProvider::class,
        TongbushiServiceProvider::class,
    ];

    public function __construct($config)
    {
        parent::__construct($config);
        // 主动重新初始化日志
        $this->initializeLogger();
    }

    /**
     * 获取配置
     *
     * @param null $key
     *
     * @return mixed
     */
    public function getConfig($key = null)
    {
        return $key ? $this->config[$key] : $this->config;
    }

    /**
     * @throws \Exception
     */
    protected function initializeLogger()
    {
        if ($this->foundationVersion() >= 3) {
            return;
        }

        // 当 foundation 小于 3 的时候，无法正常读取 config 的配置，需要主动重新获取
        // 以下进行 logger 的重新初始化
        $logger = new Logger($this['config']['log']['name'] ?? 'tongbushi');

        if (!($this['config']['debug'] ?? false) || defined('PHPUNIT_RUNNING')) {
            $logger->pushHandler(new NullHandler());
        } elseif (($this['config']['log']['handler'] ?? null) instanceof HandlerInterface) {
            $logger->pushHandler($this['config']['log']['handler']);
        } elseif ($logFile = ($this['config']['log']['file'] ?? null)) {
            $logger->pushHandler(new StreamHandler(
                    $logFile,
                    $this['config']['log']['level'] ?? Logger::WARNING,
                    true,
                    $this['config']['log']['permission'] ?? null
            ));
        }

        Log::setLogger($logger);
    }

    public function foundationVersion()
    {
        if (method_exists(parent::class, 'getConfig')) {
            return 3;
        }

        return 2;
    }
}
