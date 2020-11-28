<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [

    'query' => [
        'enabled' => env('LOG_QUERY', false),

        // Only record queries that are slower than the following time
        // Unit: milliseconds
        'slower_than' => 0,
    ],

    'request' => [
        'enabled' => env('LOG_REQUEST', false),
    ],
];
