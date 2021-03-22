<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Repositories\Enums\ResponseCodeEnum;

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

    // Set the http status code returned when the form validation fails.
    //  When the error_code is set to 200 or 500, it will not work

    'validation_error_code' => ResponseCodeEnum::CLIENT_VALIDATION_ERROR,

    // Set the structure of the paging data return,the following structure will be returned by default,
    // You can modify the name of the inner data field through the following configuration items, such as rows or list
    //{
    //    "status": "success",
    //    "code": 200,
    //    "message": "Success.",
    //    "data": {
    //    "data": [
    //        // ...
    //    ],
    //        "meta": {
    //        // ...
    //    }
    //    },
    //    "error": {}
    //}

    'format' => [
        'paginated_resource' => [
            'data_field' => 'list',
        ],
    ],

    // You can use enumerations to define the code when the response is returned,
    // and set the response message according to the locale
    //
    // The following two enumeration packages are good choices
    //
    // https://github.com/Jiannei/laravel-enum
    // https://github.com/BenSampo/laravel-enum

     'enum' => ResponseCodeEnum::class,
];
