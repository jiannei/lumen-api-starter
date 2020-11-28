<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Repositories\Enums\ExampleEnum;
use App\Support\Response\Repositories\Enums\ResponseCodeEnum;

return [
    // example
    ExampleEnum::class => [
        ExampleEnum::ADMINISTRATOR => 'Administrator',
        ExampleEnum::SUPER_ADMINISTRATOR => 'Super administrator',
    ],

    ResponseCodeEnum::class => [
        // 成功
        ResponseCodeEnum::HTTP_OK => 'Success.', // 自定义 HTTP 状态码返回消息
        ResponseCodeEnum::HTTP_UNAUTHORIZED => 'Unauthorized.',

        // 业务操作成功
        ResponseCodeEnum::SERVICE_REGISTER_SUCCESS => 'Register success.',
        ResponseCodeEnum::SERVICE_LOGIN_SUCCESS => 'Login success.',

        // 客户端错误
        ResponseCodeEnum::CLIENT_PARAMETER_ERROR => 'Parameter error.',
        ResponseCodeEnum::CLIENT_CREATED_ERROR => 'Created error.',
        ResponseCodeEnum::CLIENT_DELETED_ERROR => 'Deleted error.',

        // 服务端错误
        ResponseCodeEnum::SYSTEM_ERROR => 'System error.',
        ResponseCodeEnum::SYSTEM_UNAVAILABLE => 'System unavailable.',

        // 业务操作失败：授权业务
        ResponseCodeEnum::SERVICE_REGISTER_ERROR => 'Register error.',
        ResponseCodeEnum::SERVICE_LOGIN_ERROR => 'Login error,',
    ],
];
