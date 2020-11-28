<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support\Logger\Listeners;

use App\Support\Logger\Events\RequestHandledEvent;
use App\Support\Logger\Repositories\Enums\LogEnum;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RequestHandledListener
{
    public function handle(RequestHandledEvent $event)
    {
        if (config('logging.request.enabled')) {
            $request = $event->request;
            $response = $event->response;

            $start = $request->server('REQUEST_TIME_FLOAT');
            $end = microtime(true);
            $context = [
                'request' => $request->all(),
                'response' => $response instanceof SymfonyResponse ? json_decode($response->getContent(), true) : (string) $response,
                'start' => $start,
                'end' => $end,
                'duration' => formatDuration($end - $start),
            ];

            logger(LogEnum::REQUEST_TIME, $context);
        }
    }
}
