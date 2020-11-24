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

class RequestArrivedEvent extends Event
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
