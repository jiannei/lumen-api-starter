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

use Closure;
use Illuminate\Http\Request;

class TransformEnums
{
    public function handle(Request $request, Closure $next, $strict = true)
    {
        $strict = (bool) json_decode(strtolower($strict));

        $request->transformEnums(config('enum.transformations'), $strict);

        return $next($request);
    }
}
