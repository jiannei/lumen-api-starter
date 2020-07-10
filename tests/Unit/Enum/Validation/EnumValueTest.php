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

use App\Support\Enum\Rules\EnumValue;
use InvalidArgumentException;
use Tests\TestCase;
use Tests\Unit\Enum\Enums\StringValuesEnum;
use Tests\Unit\Enum\Enums\SuperPowersEnum;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumValueTest extends TestCase
{
    public function testValidationPasses()
    {
        // 根据常量的值（value）来判断是否合法：是否存在某个常量值
        $passes1 = (new EnumValue(UserTypeEnum::class))->passes('', 3);
        $passes2 = (new EnumValue(StringValuesEnum::class))->passes('', 'administrator');

        $this->assertTrue($passes1);
        $this->assertTrue($passes2);
    }

    public function testValidationFails()
    {
        // 传入不存在的常量值
        $fails1 = (new EnumValue(UserTypeEnum::class))->passes('', 7);
        $fails2 = (new EnumValue(UserTypeEnum::class))->passes('', 'OtherString');
        $fails3 = (new EnumValue(UserTypeEnum::class))->passes('', '3');

        $this->assertFalse($fails1);
        $this->assertFalse($fails2);
        $this->assertFalse($fails3);
    }

    public function testFlaggedEnumPassesWithNoFlagsSet()
    {
        // 校验 FlaggedEnum 是否存在某个常量值
        $passed = (new EnumValue(SuperPowersEnum::class))->passes('', 0);

        $this->assertTrue($passed);
    }

    public function testFlaggedEnumPassesWithSingleFlagSet()
    {
        // 校验 FlaggedEnum 是否存在某个常量值
        $passed = (new EnumValue(SuperPowersEnum::class))->passes('', SuperPowersEnum::FLIGHT);

        $this->assertTrue($passed);
    }

    public function testFlaggedEnumPassesWithMultipleFlagsSet()
    {
        // 校验 FlaggedEnum 是否存在某个常量值
        $passed = (new EnumValue(SuperPowersEnum::class))->passes('', SuperPowersEnum::SUPERMAN);

        $this->assertTrue($passed);
    }

    public function testFlaggedEnumPassesWithAllFlagsSet()
    {
        // 校验 FlaggedEnum 是否存在某个常量值
        $allFlags = array_reduce(SuperPowersEnum::getValues(), function (int $carry, int $powerValue) {
            return $carry | $powerValue;
        }, 0);

        $passed = (new EnumValue(SuperPowersEnum::class))->passes('', $allFlags);

        $this->assertTrue($passed);
    }

    public function testFlaggedEnumFailsWithInvalidFlagSet()
    {
        $allFlagsSet = array_reduce(SuperPowersEnum::getValues(), function ($carry, $value) {
            return $carry | $value;
        }, 0);
        $passed = (new EnumValue(SuperPowersEnum::class))->passes('', $allFlagsSet + 1);

        $this->assertFalse($passed);
    }

    public function testCanTurnOffStrictTypeChecking()
    {
        // strict 默认为 true，校验变量类型或名称大小写；为 false 不校验
        $passes = (new EnumValue(UserTypeEnum::class, false))->passes('', '3');

        $this->assertTrue($passes);

        $fails1 = (new EnumValue(UserTypeEnum::class, false))->passes('', '10');
        $fails2 = (new EnumValue(UserTypeEnum::class, false))->passes('', 'a');

        $this->assertFalse($fails1);
        $this->assertFalse($fails2);
    }

    public function testAnExceptionIsThrownIfAnNonExistingClassIsPassed()
    {
        // 实例化一个不存在的常量会抛出异常
        $this->expectException(InvalidArgumentException::class);

        (new EnumValue('PathToAClassThatDoesntExist'))->passes('', 'Test');
    }

    public function testCanSerializeToStringWithoutStrictTypeChecking()
    {
        // 校验规则序列化成 string
        $rule = new EnumValue(UserTypeEnum::class, false);

        $this->assertEquals('enum_value:'.UserTypeEnum::class.',false', (string) $rule);
    }

    public function testCanSerializeToStringWithStrictTypeChecking()
    {
        // 校验规则序列化成 string
        $rule = new EnumValue(UserTypeEnum::class, true);

        $this->assertEquals('enum_value:'.UserTypeEnum::class.',true', (string) $rule);
    }
}
