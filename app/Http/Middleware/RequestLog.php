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

use App\Events\RequestArrivedEvent;
use App\Events\RequestHandledEvent;
use Closure;
use Illuminate\Http\Request;

class RequestLog
{
    public function handle(Request $request, Closure $next)
    {
        event(new RequestArrivedEvent($request));

        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        event(new RequestHandledEvent($request, $response));
    }
}
