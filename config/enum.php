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
    'localization' => [
        'key' => env('ENUM_LOCALIZATION_KEY', 'enums'),
    ],

    // 你可以将请求参数中用到的枚举定义在下面，通过中间件，将会被自动转换成枚举类
    'transformations' => [
        // 参数名 => 对应的枚举类
    ],
];
