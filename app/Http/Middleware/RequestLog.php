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

use App\Events\RequestArrived;
use App\Events\RequestHandled;
use Closure;
use Illuminate\Http\Request;

class RequestLog
{
    public function handle(Request $request, Closure $next)
    {
        event(new RequestArrived($request));

        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        event(new RequestHandled($request, $response));
    }
}
