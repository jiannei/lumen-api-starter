<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Middleware;

use App\Jobs\LogJob;
use App\Repositories\Enums\LogEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RequestTimeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        $start = $request->server('REQUEST_TIME_FLOAT');
        $end = microtime(true);
        $context = [
            'request' => $request->all(),
            'response' => $response instanceof SymfonyResponse ? json_decode($response->getContent(), true) : (string) $response,
            'start' => $start,
            'end' => $end,
            'duration' => formatDuration($end - $start),
        ];

        dispatch(new LogJob(LogEnum::LOG_REQUEST_TIME, $context));
    }
}
