<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Enums\ResponseCode;
use Jiannei\Enum\Laravel\Support\Enums\HttpStatusCode;

return [
    ResponseCode::class => [
        // 标准 HTTP 状态码
        HttpStatusCode::HTTP_OK->value => '操作成功',
        HttpStatusCode::HTTP_UNAUTHORIZED->value => '授权失败',

        // 业务操作成功
        ResponseCode::SERVICE_REGISTER_SUCCESS->value => '注册成功',
        ResponseCode::SERVICE_LOGIN_SUCCESS->value => '登录成功',

        // 业务操作失败：授权业务
        ResponseCode::SERVICE_REGISTER_ERROR->value => '注册失败',
        ResponseCode::SERVICE_LOGIN_ERROR->value => '登录失败',

        // 客户端错误
        ResponseCode::CLIENT_PARAMETER_ERROR->value => '参数错误',
        ResponseCode::CLIENT_CREATED_ERROR->value => '数据已存在',
        ResponseCode::CLIENT_DELETED_ERROR->value => '数据不存在',

        // 服务端错误
        ResponseCode::SYSTEM_ERROR->value => '服务器错误',
        ResponseCode::SYSTEM_UNAVAILABLE->value => '服务器正在维护，暂不可用',
        ResponseCode::SYSTEM_CACHE_CONFIG_ERROR->value => '缓存配置错误',
        ResponseCode::SYSTEM_CACHE_MISSED_ERROR->value => '缓存未命中',
        ResponseCode::SYSTEM_CONFIG_ERROR->value => '系统配置错误',
    ],
];
