<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Set the http status code when the response fails
    |--------------------------------------------------------------------------
    |
    | the reference options are false, 200, 500
    |
    | false, stricter http status codes such as 404, 401, 403, 500, etc. will be returned
    | 200, All failed responses will also return a 200 status code
    | 500, All failed responses return a 500 status code
    */

    'error_code' => false,

    // lang/zh_CN/enums.php
    'locale' => 'enums.'.App\Enums\ResponseCode::class, // enums.\Jiannei\Enum\Laravel\Support\Enums\HttpStatusCode::class

    //  You can set some attributes (eg:code/message/header/options) for the exception, and it will override the default attributes of the exception
    'exception' => [
        Illuminate\Validation\ValidationException::class => [
            'code' => 422,
        ],
        Illuminate\Auth\AuthenticationException::class => [
        ],
        Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => [
            'message' => '',
        ],
        Illuminate\Database\Eloquent\ModelNotFoundException::class => [
            'message' => '',
        ],
    ],

    // Any key that returns data exists supports custom aliases and display.
    'format' => [
        'class' => Jiannei\Response\Laravel\Support\Format::class,
        'config' => [
            // key => config
            'status' => ['alias' => 'status', 'show' => true],
            'code' => ['alias' => 'code', 'show' => true],
            'message' => ['alias' => 'message', 'show' => true],
            'error' => ['alias' => 'error', 'show' => true],
            'data' => ['alias' => 'data', 'show' => true],
            'data.data' => ['alias' => 'data.data', 'show' => true], // rows/items/list
        ],
    ],
];
