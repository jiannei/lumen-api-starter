<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Enums;

use Jiannei\Enum\Laravel\Support\Traits\EnumEnhance;

enum ResponseCode: int
{
    use EnumEnhance;

    case SERVICE_REGISTER_SUCCESS = 200101;
    case SERVICE_LOGIN_SUCCESS = 200102;

    // 业务操作错误码（外部服务或内部服务调用...）
    case SERVICE_REGISTER_ERROR = 500101;
    case SERVICE_LOGIN_ERROR = 500102;

    // 客户端错误码：400 ~ 499 开头，后拼接 3 位
    case CLIENT_PARAMETER_ERROR = 400001;
    case CLIENT_CREATED_ERROR = 400002;
    case CLIENT_DELETED_ERROR = 400003;

    // 服务端操作错误码：500 ~ 599 开头，后拼接 3 位
    case SYSTEM_ERROR = 500001;
    case SYSTEM_UNAVAILABLE = 500002;
    case SYSTEM_CACHE_CONFIG_ERROR = 500003;
    case SYSTEM_CACHE_MISSED_ERROR = 500004;
    case SYSTEM_CONFIG_ERROR = 500005;
}
