<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support\Logger;

use Carbon\Carbon;
use MongoDB\Client;
use Monolog\Handler\MongoDBHandler;
use Monolog\Logger;

class MongoLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     *
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $uri = "mongodb://{$config['host']}:{$config['port']}";
        if ('daily' === $config['separate']) {
            $collection = Carbon::now()->format('Ymd').'_log';
        } elseif ('monthly' === $config['separate']) {
            $collection = Carbon::now()->format('Ym').'_log';
        } elseif ('yearly' === $config['separate']) {
            $collection = Carbon::now()->format('Y').'_log';
        } else {
            $collection = 'logs';
        }

        $handler = new MongoDBHandler( // 创建 Handler
            new Client($uri), // 创建 MongoDB 客户端（依赖 mongodb/mongodb）
            $config['database'],
            $collection
        );
        $handler->setLevel($config['level']);

        $logger = new Logger('mongo'); // 创建 Logger
        $logger->pushHandler($handler); // 挂载 Handler

        return $logger;
    }
}
