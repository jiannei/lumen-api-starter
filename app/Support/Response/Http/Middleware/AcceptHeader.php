<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support\Response\Http\Middleware;

use Closure;

class AcceptHeader
{
    public function handle($request, Closure $next)
    {
        if (app()->environment('production')) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
