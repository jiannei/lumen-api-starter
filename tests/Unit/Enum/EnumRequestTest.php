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
use Tests\Traits\CreateRequest;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumRequestTest extends TestCase
{
    use CreateRequest;

    public function testHasTransformEnumsMacro()
    {
        // 检查是否成功通过 EnumServiceProvider 向原始的 Request 中注入 transformEnums 方法
        // \App\Support\Enum\Http
        $this->assertTrue(Request::hasMacro('transformEnums'));
    }

    public function testCanTransformARequestGetParameterToEnumByKey()
    {
        // 请求中包含枚举参数时，可以通过定义参数与枚举对象之间的对应关系，从而将参数值转换成枚对应的举实例
        // 根据「枚举的名称」转换

        // 比如，客户端发起了一个 request，参数中包含 user_type = 'MODERATOR'，我们希望可以在 Controller 中的方法中更为便捷的操作这个枚举
        // GET 方式：request 参数中包含 user_type
        $request = $this->createRequest([
            'user_type' => 'SUBSCRIBER',
        ]);

        // 在 /config/enum.php 的 transformations 中定义参数和枚举对象的对应关系
        // user_type => UserTypeEnum::class
        $request->transformEnums(['user_type' => UserTypeEnum::class,], true);

        // 通过转换成枚举实例，就可以直接使用枚举实例中的属性和方法
        $userType = $request->input('user_type');// UserTypeEnum::class

        $this->assertInstanceOf(UserTypeEnum::class, $userType);
        $this->assertEquals(UserTypeEnum::getKey(UserTypeEnum::SUBSCRIBER), $userType->key);
        $this->assertEquals(UserTypeEnum::SUBSCRIBER, $userType->value);
        $this->assertEquals(UserTypeEnum::getDescription(UserTypeEnum::SUBSCRIBER), $userType->description);
        $this->assertTrue($userType->is(UserTypeEnum::SUBSCRIBER));
    }

    public function testCanTransformARequestGetParameterToEnumByValue()
    {
        // 根据「枚举的值」进行转换

        // GET 方式：request 参数中包含 user_type
        $request = $this->createRequest([
            'user_type' => 2,
        ]);

        // user_type => UserTypeEnum::class
        $request->transformEnums(['user_type' => UserTypeEnum::class,], true);

        // 通过转换成枚举实例，就可以直接使用枚举实例中的属性和方法
        $userType = $request->input('user_type');// UserTypeEnum::class

        $this->assertInstanceOf(UserTypeEnum::class, $userType);
        $this->assertEquals('SUBSCRIBER', $userType->key);
    }

    public function testAnExceptionIsThrownIfAnNonExistingEnumValueIsPassed()
    {
        $request = $this->createRequest([
            'user_type' => '2',// 字符串类型的 2
        ]);

        $request->transformEnums(['user_type' => UserTypeEnum::class,], true);// strict 为 true

        $userType = $request->input('user_type');

        $this->assertNotInstanceOf(UserTypeEnum::class, $userType);
        $this->assertEquals('2', $userType);// 转换失败，返回字符串原始值 '2'
    }

    public function testCanTransformARequestGetParameterToEnumByKeyWithoutStrictTypeChecking()
    {
        $request = $this->createRequest([
            'user_type' => 'subscriber',// 或者 Subscriber，不区分大小写
        ]);

        $request->transformEnums(['user_type' => UserTypeEnum::class,], false); // 设置 strict 为 false，不严格校验参数值的小写或类型

        $userType = $request->input('user_type');// 转换成功，UserTypeEnum::class

        $this->assertInstanceOf(UserTypeEnum::class, $userType);
        $this->assertEquals('SUBSCRIBER', $userType->key);
    }

    public function testCanTransformARequestGetParameterToEnumByValueWithoutStrictTypeChecking()
    {
        $request = $this->createRequest([
            'user_type' => '2',
        ]);

        // user_type => UserTypeEnum::class
        $request->transformEnums(['user_type' => UserTypeEnum::class,], false); // 设置 strict 为 false，不严格校验参数值的小写或类型

        $userType = $request->input('user_type');// 转换成功，UserTypeEnum::class

        $this->assertInstanceOf(UserTypeEnum::class, $userType);
        $this->assertEquals('SUBSCRIBER', $userType->key);
    }

    public function testCanTransformARequestPostParameterToEnumByKey()
    {
        // 根据「枚举的名称」进行转换
        // POST 方式：request 参数中包含 user_type
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            ['user_type' => 'SUBSCRIBER',]
        );

        // user_type => UserTypeEnum::class
        $request->transformEnums(['user_type' => UserTypeEnum::class,], true);

        $this->assertInstanceOf(UserTypeEnum::class, $request->input('user_type'));
        $this->assertEquals('SUBSCRIBER', $request->input('user_type')->key);
    }

    public function testCanTransformARequestPostParameterToEnumByValue()
    {
        // 根据「枚举的值」进行转换
        // POST 方式：request 参数中包含 user_type
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            ['user_type' => 1,]
        );

        // user_type => UserTypeEnum::class
        $request->transformEnums(['user_type' => UserTypeEnum::class,], true);

        $this->assertInstanceOf(UserTypeEnum::class, $request->input('user_type'));
        $this->assertEquals('MODERATOR', $request->input('user_type')->key);
    }
}
