<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Unit\Enum\Enums;

use App\Support\Enum\Enum;

final class StringValuesEnum extends Enum
{
    const ADMINISTRATOR = 'administrator';

    const MODERATOR = 'moderator';
}
