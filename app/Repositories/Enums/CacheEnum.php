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

use App\Support\Enum\Enum;

class CacheEnum extends Enum
{
    // 定义缓存时用到的 key 值；以冒号区分层级
    const AUTHORIZATION_USER = 'authorization:user';

    /**
     * 根据生成缓存时的 index.
     *
     * @param  string|Enum|int  $cacheEnumKeyOrValue  缓存常量的 key 值或 value 值
     * @param  string|null  $identifier  integer|string 缓存区分
     * @return string
     */
    public static function buildKey($cacheEnumKeyOrValue, ?string $identifier = null)
    {
        $key = (string) self::make($cacheEnumKeyOrValue);

        return is_null($identifier) ? $key : $key.':'.$identifier;
    }
}
