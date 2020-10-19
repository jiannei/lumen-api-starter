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

use Illuminate\Support\Facades\Log;

class LogJob extends Job
{
    private $context;
    private $message;

    public function __construct(string $message, array $context)
    {
        $this->message = $message;
        $this->context = $context;
    }

    public function handle()
    {
        Log::debug($this->message, $this->context);
    }
}
