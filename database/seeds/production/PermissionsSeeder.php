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
use App\Repositories\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionClass = app(PermissionContract::class);
        $roleClass = app(RoleContract::class);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // 禁用外键约束

        DB::table($roleClass->getTable())->truncate();
        DB::table($permissionClass->getTable())->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // 启用外键约束

        $roles = RoleEnum::makeRoles();
        foreach ($roles as $item) {
            $roleClass->create($item);
        }

        $permissions = PermissionEnum::makePermissions();
        foreach ($permissions as $item) {
            $permissionClass::create($item);
        }
    }
}
