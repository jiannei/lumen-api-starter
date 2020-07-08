<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Repositories\Enums\ExampleEnum;

return [
    // example
    ExampleEnum::class => [
        ExampleEnum::ADMINISTRATOR => 'Administrator',
        ExampleEnum::SUPER_ADMINISTRATOR => 'Super administrator',
    ],
];
