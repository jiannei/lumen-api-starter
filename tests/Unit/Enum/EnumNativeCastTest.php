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

use App\Support\Enum\Exceptions\InvalidEnumValueException;
use ReflectionProperty;
use Tests\TestCase;
use Tests\Unit\Enum\Enums\UserTypeCustomCastEnum;
use Tests\Unit\Enum\Enums\UserTypeEnum;
use Tests\Unit\Enum\Models\User;

class EnumNativeCastTest extends TestCase
{
    public function testCanSetModelValueUsingEnumInstance()
    {
        // 可以使用常量实例来给 model 赋值
        $model = app(User::class);
        $model->user_type = UserTypeEnum::MODERATOR();

        $this->assertEquals(UserTypeEnum::MODERATOR(), $model->user_type);
    }

    public function testCanSetModelValueUsingEnumValue()
    {
        // 可以使用常量的值来给 model 赋值
        $model = app(User::class);
        $model->user_type = UserTypeEnum::MODERATOR;

        // 由于事先在 \Tests\Unit\Enum\Models\User 中定义了 $casts，所以这里 $model->user_type 取值时会自动进行转换
        $this->assertInstanceOf(UserTypeEnum::class, $model->user_type);

        $this->assertEquals(UserTypeEnum::MODERATOR(), $model->user_type);
    }

    public function testCannotSetModelValueUsingInvalidEnumValue()
    {
        // 给 model 赋值非法的常量值会抛出异常
        $this->expectException(InvalidEnumValueException::class);

        $model = app(User::class);
        $model->user_type = 5;
    }

    public function testGettingModelValueReturnsEnumInstance()
    {
        // 由于事先在 \Tests\Unit\Enum\Models\User 中定义了 $casts，所以这里 $model->user_type 取值时会自动进行转换
        $model = app(User::class);
        $model->user_type = UserTypeEnum::MODERATOR;

        $this->assertInstanceOf(UserTypeEnum::class, $model->user_type);
    }

    public function testCanGetAndSetNullOnEnumCastable()
    {
        // 可以设置空值
        $model = app(User::class);
        $model->user_type = null;

        $this->assertNull($model->user_type);
    }

    public function testCanHandleStringIntFromDatabase()
    {
        // 从数据库中取出的枚举值/常量值通常为字符串类型，也可以转换成相应的常量实例
        $model = app(User::class);
        $reflection = new ReflectionProperty(User::class, 'attributes');
        $reflection->setAccessible(true);
        $reflection->setValue($model, ['user_type' => '1']);

        $this->assertInstanceOf(UserTypeEnum::class, $model->user_type);
    }

    public function testThatModelWithEnumCanBeCastToArray()
    {
        // 包含有 cast 常量转换的 Model 也可以转成数组
        $model = app(User::class);
        $model->user_type = UserTypeEnum::MODERATOR();

        $this->assertSame([
            'user_type' => [
                'description' => 'Moderator',
                'key' => 'MODERATOR',
                'value' => 1,
            ],
        ], json_decode(json_encode($model), true));
    }

    public function testCanUseCustomCasting()
    {
        // 自定义常量实例转换逻辑
        $model = app(User::class);

        $reflection = new ReflectionProperty(User::class, 'attributes');
        $reflection->setAccessible(true);
        $reflection->setValue($model, ['user_type_custom' => 'type-3']); // 常规转换此处是 3

        $this->assertInstanceOf(UserTypeCustomCastEnum::class, $model->user_type_custom); // type-3 => UserTypeCustomCastEnum
        $this->assertEquals(UserTypeCustomCastEnum::SUPER_ADMINISTRATOR(), $model->user_type_custom);

        $model->user_type_custom = UserTypeCustomCastEnum::ADMINISTRATOR(); // 常规转换此处 user_type_custom 的值为 0

        $this->assertSame('type-0', $reflection->getValue($model)['user_type_custom']);
    }
}
