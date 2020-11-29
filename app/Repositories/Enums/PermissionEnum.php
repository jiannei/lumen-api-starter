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

use Closure;
use Illuminate\Support\Str;
use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;
use Jiannei\Enum\Laravel\Enum;

class PermissionEnum extends Enum implements LocalizedEnumContract
{
    // 系统级别内置的权限：不允许分配权限给普通用户；（比如，只能超级管理员才能拥有）
    const SYSTEM = '000000';
    const SYSTEM_ACTIVITY_LOG_CLEAN = '000001';
    const SYSTEM_CACHE_CLEAR = '000002';

    // 路由级别权限：路由分组、路由操作；允许分配权限给普通用户，或分配给角色；ROUTE 开头
    // 定义规范：viewAny、view、create、update、delete...
    // https://learnku.com/docs/laravel/8.x/authorization/9382#f98610
    const ROUTE = '100000';

    const ROUTE_USERS = '100100';
    const ROUTE_USERS_CREATE = '100110';
    const ROUTE_USERS_DELETE = '100111';
    const ROUTE_USERS_UPDATE = '100112';
    const ROUTE_USERS_VIEW = '100113';
    const ROUTE_USERS_VIEW_ANY = '100114';

    const ROUTE_POSTS = '100200';
    const ROUTE_POSTS_CREATE = '100210';
    const ROUTE_POSTS_DELETE = '100211';
    const ROUTE_POSTS_UPDATE = '100212';
    const ROUTE_POSTS_VIEW = '100213';
    const ROUTE_POSTS_VIEW_ANY = '100214';

    // 数据级别权限：数据表权限，数据行权限，数据列权限
    const DATA = '200000';

    const DATA_USERS = '200100';
    const DATA_USERS_ROW = '200101';
    const DATA_USERS_COLUMN_EMAIL = '200110';

    const DATA_POSTS = '200200';
    const DATA_POSTS_ROW = '200201';
    const DATA_POSTS_BODY = '200210';

    public $name;
    public $code;
    public $type;
    public $description;

    public function __construct($enumValue, bool $strict = true)
    {
        parent::__construct($enumValue, $strict);

        $this->name = Str::lower(str_replace('_', ':', $this->key));
        $this->code = $enumValue;
        $this->type = Str::lower(current(explode('_', $this->key)));
    }

    /**
     * 根据常量定义构建权限.
     *
     * @return array
     */
    public static function makePermissions(): array
    {
        $rawPermissions = self::getInstances();

        $permissions = [];
        collect($rawPermissions)->each(function ($item) use (&$permissions) {
            $permissions[] = [
                'name' => $item->name,
            ];
        });

        return $permissions;
    }

    /**
     * 前置-授权拦截检查.
     *
     * @return Closure
     */
    public static function gateBeforeCallback(): Closure
    {
        return function ($user, $ability) {
            return $user->isSuperAdmin() ?: null;
        };
    }
}
