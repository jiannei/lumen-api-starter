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

use App\Traits\Helpers;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use Helpers;

    /**
     * Custom Failed Validation Response.
     *
     * @param Request $request
     * @param array   $errors
     *
     * @return mixed
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return call_user_func(static::$responseBuilder, $request, $errors);
        }

        $this->response->fail('Validation error', 422, $errors);
    }
}
