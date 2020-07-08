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

use App\Contracts\Enums\LocalizedEnumContract;
use App\Support\Enum\Enum;

class ExampleEnum extends Enum implements LocalizedEnumContract
{
    const ADMINISTRATOR = 1;

    const MODERATOR = 0;

    const SUPER_ADMINISTRATOR = 2;
}
