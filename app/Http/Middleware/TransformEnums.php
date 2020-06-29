<?php

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
