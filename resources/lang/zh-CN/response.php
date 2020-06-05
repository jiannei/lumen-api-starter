<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Constants\ResponseConstant;

return [
    // 成功
    ResponseConstant::CUSTOM_HTTP_OK => '操作成功', // 自定义 HTTP 状态码返回消息

    // 业务操作成功
    ResponseConstant::SERVICE_REGISTER_SUCCESS => '注册成功',
    ResponseConstant::SERVICE_LOGIN_SUCCESS => '登录成功',

    // 客户端错误
    ResponseConstant::CLIENT_PARAMETER_ERROR => '参数错误',

    // 服务端错误
    ResponseConstant::SYSTEM_ERROR => '服务器错误',
    ResponseConstant::SYSTEM_UNAVAILABLE => '服务器正在维护，暂不可用',

    // 业务操作失败：授权业务
    ResponseConstant::SERVICE_REGISTER_ERROR => '注册失败',
    ResponseConstant::SERVICE_LOGIN_ERROR => '登录失败',
];
