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

use Tests\TestCase;
use Tests\Unit\Enum\Enums\StringValuesEnum;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumComparisonTest extends TestCase
{
    public function testComparisonAgainstPlainValueMatching()
    {
        // 使用常量实例的 is 方法，比较某个值与当前常量实例的值是否相同
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertTrue($admin->is(UserTypeEnum::ADMINISTRATOR));
    }

    public function testComparisonAgainstPlainValueNotMatching()
    {
        // 使用常量实例的 is 方法，比较某个值与当前常量实例的值是否相同
        // 对应的，常量实例还有 isNot 方法
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertFalse($admin->is(UserTypeEnum::SUPER_ADMINISTRATOR));
        $this->assertFalse($admin->is('some-random-value'));
        $this->assertTrue($admin->isNot(UserTypeEnum::SUPER_ADMINISTRATOR));
        $this->assertTrue($admin->isNot('some-random-value'));
    }

    public function testComparisonAgainstItselfMatches()
    {
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertTrue($admin->is($admin));
    }

    public function testComparisonAgainstOtherInstancesMatches()
    {
        // 比较两个常量实例的值是否相同
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);
        $anotherAdmin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertTrue($admin->is($anotherAdmin));
    }

    public function testComparisonAgainstOtherInstancesNotMatching()
    {
        // 比较两个常量实例的值是否相同
        $admin = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);
        $superAdmin = UserTypeEnum::fromValue(UserTypeEnum::SUPER_ADMINISTRATOR);

        $this->assertFalse($admin->is($superAdmin));
    }

    public function testEnumInstanceInArray()
    {
        // 常量实例的值是否属于数组
        $administrator = new StringValuesEnum(StringValuesEnum::ADMINISTRATOR);

        // 数组元素是常量的值
        $this->assertTrue($administrator->in([
            StringValuesEnum::MODERATOR,
            StringValuesEnum::ADMINISTRATOR,
        ]));

        // 数组元素是常量的实例
        $this->assertTrue($administrator->in([
            new StringValuesEnum(StringValuesEnum::MODERATOR),
            new StringValuesEnum(StringValuesEnum::ADMINISTRATOR),
        ]));

        $this->assertTrue($administrator->in([StringValuesEnum::ADMINISTRATOR]));
        $this->assertFalse($administrator->in([StringValuesEnum::MODERATOR]));
    }
}
