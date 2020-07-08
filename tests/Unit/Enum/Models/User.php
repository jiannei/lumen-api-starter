<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Unit\Enum\Models;

use Illuminate\Database\Eloquent\Model;
use Tests\Unit\Enum\Enums\UserTypeCustomCastEnum;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class User extends Model
{
    protected $casts = [
        'user_type' => UserTypeEnum::class,
        'user_type_custom' => UserTypeCustomCastEnum::class,
    ];

    protected $fillable = [
        'user_type',
        'user_type_custom',
    ];
}
