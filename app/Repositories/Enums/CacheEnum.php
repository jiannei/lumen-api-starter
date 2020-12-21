<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories\Enums;

use Illuminate\Support\Carbon;
use Jiannei\Enum\Laravel\Repositories\Enums\CacheEnum as BaseCacheEnum;

class CacheEnum extends BaseCacheEnum
{
    const AUTHORIZATION_USER = 'authorizationUser';

    protected function authorizationUser($options)
    {
        $exp = auth('api')->payload()->get('exp'); // token 剩余有效时间

        return Carbon::now()->diffInSeconds(Carbon::createFromTimestamp($exp));
    }
}
