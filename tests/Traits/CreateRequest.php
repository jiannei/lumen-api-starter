<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Traits;

use Illuminate\Http\Request;

trait CreateRequest
{
    protected function createRequest(array $query = [], string $method = Request::METHOD_GET, array $request = []): Request
    {
        $uri = 'http://localhost/test';
        $server = [
            'REQUEST_URI' => $uri,
            'CONTENT_TYPE' => 'application/json',
        ];

        $request = new Request($query, $request, [], [], [], $server, json_encode($request));
        $request->setMethod($method);

        return $request;
    }
}
