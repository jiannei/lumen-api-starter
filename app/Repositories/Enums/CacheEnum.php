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
use App\Support\Response\Repositories\Enums\ResponseCodeEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CacheEnum extends Enum
{
    // 定义缓存的 key 和 ttl（过期时间）：
    // key => 过期时间计算方法
    const AUTHORIZATION_USER = 'authorizationUser';

    public $ttl;

    /**
     * 生成缓存枚举实例.
     *
     * @param  string|Enum|int  $enumKeyOrValue  缓存常量的 key 值或 value 值
     * @param  string|int|null  $identifier  缓存区分标识
     * @param  mixed  $options  用于缓存时间计算
     * @return CacheEnum
     */
    public static function makeCache($enumKeyOrValue, $identifier = null, $options = null): CacheEnum
    {
        $cacheEnum = self::make($enumKeyOrValue);
        if (! $cacheEnum instanceof self || ! method_exists($cacheEnum, $cacheEnum->value)) {
            abort(ResponseCodeEnum::SYSTEM_CACHE_CONFIG_ERROR);
        }

        $cacheEnum->key = self::makeKey($cacheEnum->key, $identifier);
        $cacheEnum->ttl = forward_static_call([$cacheEnum, $cacheEnum->value], $options);

        return $cacheEnum;
    }

    public static function makeKey(string $enumKey, ?string $identifier = null): string
    {
        $key = Str::lower(str_replace('_', ':', $enumKey));

        return is_null($identifier) ? $key : $key.':'.$identifier;
    }

    /**
     * 根据缓存的 key 解析出枚举的 key
     * example: authorization:user:1 => AUTHORIZATION_USER.
     *
     * @param  string  $key
     * @return string
     */
    public static function parseEnumKey(string $key): string
    {
        $segments = explode('_', Str::upper(str_replace(':', '_', $key)));

        if (is_numeric(last($segments))) {
            array_pop($segments);
        }

        return implode('_', $segments);
    }

    private static function authorizationUser($options)
    {
        $exp = auth('api')->payload()->get('exp'); // token 剩余有效时间

        return Carbon::now()->diffInSeconds(Carbon::createFromTimestamp($exp));
    }
}
