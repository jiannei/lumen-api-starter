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

    // You can use enumerations to define the code when the response is returned,
    // and set the response message according to the locale
    //
    // The following two enumeration packages are good choices
    //
    // https://github.com/Jiannei/laravel-enum
    // https://github.com/BenSampo/laravel-enum

    'enum' => \App\Repositories\Enums\ResponseCodeEnum::class, // \Jiannei\Enum\Laravel\Repositories\Enums\HttpStatusCodeEnum::class

    //  You can set some attributes (eg:code/message/header/options) for the exception, and it will override the default attributes of the exception
    'exception' => [
        \Illuminate\Validation\ValidationException::class => [
            'code' => \App\Repositories\Enums\ResponseCodeEnum::HTTP_UNPROCESSABLE_ENTITY,
        ],
        \Illuminate\Auth\AuthenticationException::class => [

        ],
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class =>[
            'message' => '',
        ],
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => [
            'message' => '',
        ],
    ],

    // Set the structure of the response data
    'format' => [
        \Jiannei\Response\Laravel\Support\Format::class,
        [
            'status' => ['alias' => 'status', 'show' => true],
            'code' => ['alias' => 'code', 'show' => true],
            'message' => ['alias' => 'message', 'show' => true],
            'error' => ['alias' => 'error', 'show' => true],
            'data' => [
                'alias' => 'data',
                'show' => true,

                'fields' => [
                    // When data is nested with data, such as returning paged data, you can also set an alias for the inner data
                    'data' => ['alias' => 'list', 'show' => true], // data/rows/list

                    'meta' => [
                        'alias' => 'meta',
                        'show' => true,

                        'fields' => [
                            'pagination' => [
                                'alias' => 'pagination',
                                'show' => true,

                                'fields' => [
                                    'total' => ['alias' => 'total', 'show' => true],
                                    'count' => ['alias' => 'count', 'show' => true],
                                    'per_page' => ['alias' => 'per_page', 'show' => true],
                                    'current_page' => ['alias' => 'current_page', 'show' => true],
                                    'total_pages' => ['alias' => 'total_pages', 'show' => true],
                                    'links' => [
                                        'alias' => 'links',
                                        'show' => true,

                                        'fields' => [
                                            'previous' => ['alias' => 'previous', 'show' => true],
                                            'next' => ['alias' => 'next', 'show' => true],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
