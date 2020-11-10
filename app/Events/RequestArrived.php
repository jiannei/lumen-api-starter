<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestArrived extends Event
{
    public function __construct(Request $request)
    {
        $uniqueId = $request->headers->get('X-Unique-Id') ?: Str::uuid()->toString();

        $request->server->set('UNIQUE_ID', $uniqueId);
    }
}
