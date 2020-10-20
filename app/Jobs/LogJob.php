<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Jobs;

use Monolog\Processor\WebProcessor;

class LogJob extends Job
{
    private $context;
    private $message;
    private $serverData;

    public function __construct(string $message, array $context = null, array $serverData = null)
    {
        $this->message = $message;
        $this->context = $context;
        $this->serverData = $serverData;
    }

    public function handle()
    {
        $logger = clone app('log')->getLogger();
        $logger->pushProcessor(new WebProcessor($this->serverData));
        $logger->debug($this->message, $this->context);
    }
}
