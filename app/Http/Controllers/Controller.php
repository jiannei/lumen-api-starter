<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Controllers;

use App\Support\Traits\ResponseTrait;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ResponseTrait;
}
