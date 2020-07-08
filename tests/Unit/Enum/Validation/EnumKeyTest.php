<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Unit\Enum\Validation;

use App\Support\Enum\Rules\EnumKey;
use InvalidArgumentException;
use Tests\TestCase;
use Tests\Unit\Enum\Enums\StringValuesEnum;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumKeyTest extends TestCase
{
    public function testValidationPasses()
    {
        // 根据常量的名称（key）来判断是否合法：是否存在某个常量名称
        $passes1 = (new EnumKey(UserTypeEnum::class))->passes('', 'ADMINISTRATOR'); // strict 默认为 true，校验名称大小写
        $fails = (new EnumKey(StringValuesEnum::class))->passes('', 'Administrator'); // strict 默认为 true，校验名称大小写
        $passes2 = (new EnumKey(StringValuesEnum::class))->passes('', 'ADMINISTRATOR'); // strict 默认为 true，不校验名称大小写
        $passes3 = (new EnumKey(StringValuesEnum::class, false))->passes('', 'administrator'); // strict 默认为 false，不校验名称大小写

        $this->assertTrue($passes1);
        $this->assertFalse($fails);
        $this->assertTrue($passes2);
        $this->assertTrue($passes3);
    }

    public function testValidationFails()
    {
        // 传入不存在的常量名称（key）
        $fails1 = (new EnumKey(UserTypeEnum::class))->passes('', 'Anything else');
        $fails2 = (new EnumKey(UserTypeEnum::class))->passes('', 2);
        $fails3 = (new EnumKey(UserTypeEnum::class))->passes('', '2');

        $this->assertFalse($fails1);
        $this->assertFalse($fails2);
        $this->assertFalse($fails3);
    }

    public function testAnExceptionIsThrownIfAnNonExistingClassIsPassed()
    {
        // 实例化一个不存在的常量会抛出异常
        $this->expectException(InvalidArgumentException::class);

        (new EnumKey('PathToAClassThatDoesntExist'))->passes('', 'Test');
    }

    public function testCanSerializeToString()
    {
        // 校验规则序列化成 string
        $rule1 = new EnumKey(UserTypeEnum::class);
        $rule2 = new EnumKey(UserTypeEnum::class, false);

        $this->assertEquals('enum_key:'.UserTypeEnum::class.',true', (string) $rule1);
        $this->assertEquals('enum_key:'.UserTypeEnum::class.',false', (string) $rule2);
    }
}
