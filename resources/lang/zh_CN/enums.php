<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Repositories\Enums\PermissionEnum;
use App\Repositories\Enums\ResponseCodeEnum;
use App\Repositories\Enums\RoleEnum;

return [
    // 响应状态码
    ResponseCodeEnum::class => [
        // 成功
        ResponseCodeEnum::HTTP_OK => '操作成功', // 自定义 HTTP 状态码返回消息
        ResponseCodeEnum::HTTP_NOT_FOUND => '数据未找到', // 自定义 HTTP 状态码返回消息
        ResponseCodeEnum::HTTP_UNAUTHORIZED => '授权失败',

        // 业务操作成功
        ResponseCodeEnum::SERVICE_REGISTER_SUCCESS => '注册成功',
        ResponseCodeEnum::SERVICE_LOGIN_SUCCESS => '登录成功',

        // 客户端错误
        ResponseCodeEnum::CLIENT_PARAMETER_ERROR => '参数错误',
        ResponseCodeEnum::CLIENT_CREATED_ERROR => '数据已存在',
        ResponseCodeEnum::CLIENT_DELETED_ERROR => '数据不存在',
        ResponseCodeEnum::CLIENT_VALIDATION_ERROR => '表单验证错误',

        // 服务端错误
        ResponseCodeEnum::SYSTEM_ERROR => '服务器错误',
        ResponseCodeEnum::SYSTEM_UNAVAILABLE => '服务器正在维护，暂不可用',
        ResponseCodeEnum::SYSTEM_CACHE_CONFIG_ERROR => '缓存配置错误',
        ResponseCodeEnum::SYSTEM_CACHE_MISSED_ERROR => '缓存未命中',
        ResponseCodeEnum::SYSTEM_CONFIG_ERROR => '系统配置错误',

        // 业务操作失败：授权业务
        ResponseCodeEnum::SERVICE_REGISTER_ERROR => '注册失败',
        ResponseCodeEnum::SERVICE_LOGIN_ERROR => '登录失败',
    ],

    // 角色
    RoleEnum::class => [
        RoleEnum::SUPER_ADMIN => '超级管理员',
        RoleEnum::GUEST => '游客',
        RoleEnum::ADMIN => '管理员',
    ],

    // 权限
    PermissionEnum::class => [
        PermissionEnum::SYSTEM_ACTIVITY_LOG_CLEAN => '清理活动日志',
        PermissionEnum::SYSTEM_CACHE_CLEAR => '清理缓存',

        PermissionEnum::ROUTE_USERS => '用户管理',
        PermissionEnum::ROUTE_USERS_CREATE => '创建用户',
        PermissionEnum::ROUTE_USERS_DELETE => '删除用户',
        PermissionEnum::ROUTE_USERS_UPDATE => '更新用户资料',
        PermissionEnum::ROUTE_USERS_VIEW => '查询用户资料',
        PermissionEnum::ROUTE_USERS_VIEW_ANY => '查询用户列表',

        PermissionEnum::ROUTE_POSTS => ' 文章管理',
        PermissionEnum::ROUTE_POSTS_CREATE => '发布文章',
        PermissionEnum::ROUTE_POSTS_DELETE => '删除文章',
        PermissionEnum::ROUTE_POSTS_UPDATE => '更新文章',
        PermissionEnum::ROUTE_POSTS_VIEW => '查看文章',
        PermissionEnum::ROUTE_POSTS_VIEW_ANY => '查询文章列表',
    ],
];
