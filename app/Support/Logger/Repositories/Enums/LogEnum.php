<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support\Logger\Repositories\Enums;

use App\Support\Enum\Enum;

class LogEnum extends Enum
{
    // 定义应用中的日志分类；以冒号区分层级
    const SQL = 'system:sql';
    const REQUEST_TIME = 'system:request_time';
}
