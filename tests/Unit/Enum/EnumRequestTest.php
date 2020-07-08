<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Unit\Enum;

use Illuminate\Http\Request;
use Tests\TestCase;

class EnumRequestTest extends TestCase
{
    public function testHasTransformEnumsMacro()
    {
        // 检查是否成功通过 EnumServiceProvider 向原始的 Request 中注入 transformEnums 方法
        // \App\Support\Enum\Http
        $this->assertTrue(Request::hasMacro('transformEnums'));
    }
}
