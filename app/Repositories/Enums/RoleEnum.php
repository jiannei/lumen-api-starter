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

use App\Support\Enum\Contracts\LocalizedEnumContract;
use App\Support\Enum\FlaggedEnum;
use Illuminate\Support\Str;

class RoleEnum extends FlaggedEnum implements LocalizedEnumContract
{
    const GUEST = 1 << 0;
    const ADMIN = 1 << 1;
    const SUPER_ADMIN = self::GUEST | self::ADMIN;

    public $name;

    public function __construct($flags)
    {
        parent::__construct($flags);
        $this->name = Str::ucfirst(Str::lower(Str::of($this->key)->replace('_', ' ')));
    }

    public static function makeRoles(): array
    {
        $rawRoles = self::getInstances();
        collect($rawRoles)->each(function ($item, $key) use (&$roles) {
            if ($item->value !== self::NONE) {
                $roles[] = ['name' => $item->name];
            }
        });

        return $roles;
    }
}
