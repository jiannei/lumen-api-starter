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

use App\Support\Enum\Exceptions\InvalidEnumKeyException;
use App\Support\Enum\Exceptions\InvalidEnumValueException;
use Tests\TestCase;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumInstanceTest extends TestCase
{
    public function testCanInstantiateEnumClassWithNew()
    {
        // 使用 new 方式实例化常量
        $userType = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);
        $this->assertInstanceOf(UserTypeEnum::class, $userType);
    }

    public function testCanInstantiateEnumClassFromValue()
    {
        // 根据常量的值（value）来实例化常量
        $userType = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);
        $this->assertInstanceOf(UserTypeEnum::class, $userType);
    }

    public function testCanInstantiateEnumClassFromKey()
    {
        // 根据常量的名称（key）来实例化常量
        $userType = UserTypeEnum::fromKey('ADMINISTRATOR');
        $this->assertInstanceOf(UserTypeEnum::class, $userType);
    }

    public function testAnExceptionIsThrownWhenTryingToInstantiateEnumClassWithAnInvalidEnumValue()
    {
        // 尝试使用不存在的常量值实例化常量时会抛出异常
        $this->expectException(InvalidEnumValueException::class);

        UserTypeEnum::fromValue('InvalidValue');
    }

    public function testAnExceptionIsThrownWhenTryingToInstantiateEnumClassWithAnInvalidEnumKey()
    {
        // 尝试使用不存在的常量名称实例化常量时会抛出异常
        $this->expectException(InvalidEnumKeyException::class);

        UserTypeEnum::fromKey('foobar');
    }

    public function testCanGetTheValueForAnEnumInstance()
    {
        // 通过常量实例可以获取常量的值（value）
        $userType = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertEquals($userType->value, UserTypeEnum::ADMINISTRATOR);
    }

    public function testCanGetTheKeyForAnEnumInstance()
    {
        // 通过常量实例可以获取常量的名称（key）
        $userType = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertEquals($userType->key, UserTypeEnum::getKey(UserTypeEnum::ADMINISTRATOR));
    }

    public function testCanGetTheDescriptionForAnEnumInstance()
    {
        // 通过常量实例可以获取常量的描述（description）
        $userType = UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR);

        $this->assertEquals($userType->description, UserTypeEnum::getDescription(UserTypeEnum::ADMINISTRATOR));
    }

    public function testCanGetEnumInstanceByCallingAnEnumKeyAsAStaticMethod()
    {
        // 通过静态方法来实例化常量
        $this->assertInstanceOf(UserTypeEnum::class, UserTypeEnum::ADMINISTRATOR());
    }

    public function testMagicInstantiationFromInstanceMethod()
    {
        // 静态方法、非静态方法
        $userType = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);
        $this->assertInstanceOf(UserTypeEnum::class, $userType->magicInstantiationFromInstanceMethod());
    }

    public function testAnExceptionIsThrownWhenTryingToGetEnumInstanceByCallingAnEnumKeyAsAStaticMethodWhichDoesNotExist()
    {
        // 通过不合法的静态方法实例化常量时将抛出异常
        $this->expectException(InvalidEnumKeyException::class);

        UserTypeEnum::KeyWhichDoesNotExist();
    }

    public function testGettingAnInstanceUsingAnInstanceReturnsAnInstance()
    {
        $this->assertInstanceOf(UserTypeEnum::class, UserTypeEnum::fromValue(UserTypeEnum::ADMINISTRATOR));
    }
}
