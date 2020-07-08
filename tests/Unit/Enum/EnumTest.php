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

use App\Repositories\Enums\ResponseCodeEnum;
use App\Support\Enum\Enum;
use Tests\TestCase;
use Tests\Unit\Enum\Enums\StringValuesEnum;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumTest extends TestCase
{
    public function testEnumValues()
    {
        // 常规用法：获取常量的值
        $this->assertEquals(0, UserTypeEnum::ADMINISTRATOR);
        $this->assertEquals(3, UserTypeEnum::SUPER_ADMINISTRATOR);
    }

    public function testEnumGetKeys()
    {
        // 获取所有已定义常量的名称
        $keys = UserTypeEnum::getKeys();
        $expectedKeys = ['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER', 'SUPER_ADMINISTRATOR'];

        $this->assertEquals($expectedKeys, $keys);
    }

    public function testEnumGetKey()
    {
        // 根据常量的值获取常量的名称
        $this->assertEquals('MODERATOR', UserTypeEnum::getKey(1));
        $this->assertEquals('SUPER_ADMINISTRATOR', UserTypeEnum::getKey(3));
    }

    public function testEnumGetValues()
    {
        // 获取所有已定义常量的值
        $values = UserTypeEnum::getValues();
        $expectedValues = [0, 1, 2, 3];

        $this->assertEquals($expectedValues, $values);
    }

    public function testEnumGetValue()
    {
        // 根据常量的名称获取常量的值
        $this->assertEquals(1, UserTypeEnum::getValue('MODERATOR'));
        $this->assertEquals(3, UserTypeEnum::getValue('SUPER_ADMINISTRATOR'));
    }

    public function testEnumGetDescription()
    {
        // 根据常量的值获取常量的描述信息
        // 1. 不存在语言包的情况，返回较为友好的英文描述
        $this->assertEquals('Administrator', UserTypeEnum::getDescription(UserTypeEnum::ADMINISTRATOR));

        // 2. 在 resource/lang/zh-CN/enums.php 中定义常量与描述的对应关系（enums.php 文件名称可以在 config/enum.php 文件中配置）
        $this->assertEquals('登录成功', ResponseCodeEnum::getDescription(ResponseCodeEnum::SERVICE_LOGIN_SUCCESS));

        // 补充：也可以先实例化常量对象，然后再根据对象实例来获取常量描述
        $responseEnum = new ResponseCodeEnum(ResponseCodeEnum::SERVICE_LOGIN_SUCCESS);
        $this->assertEquals('登录成功', $responseEnum->description);
    }

    public function testEnumHasValue()
    {
        // 检查常量是否包含某个 常量值
        $this->assertTrue(UserTypeEnum::hasValue(1));

        $this->assertFalse(UserTypeEnum::hasValue(-1));
    }

    public function testEnumHasKey()
    {
        // 检查常量是否包含某个 常量名称
        $this->assertTrue(UserTypeEnum::hasKey('MODERATOR'));

        $this->assertFalse(UserTypeEnum::hasKey('ADMIN'));
    }

    public function testEnumInstance()
    {
        // 实例化常量对象的方式

        // 方式一：new 传入常量的值
        $administrator1 = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);

        // 方式二：fromValue
        $administrator2 = UserTypeEnum::fromValue(0);

        // 方式三：fromKey
        $administrator3 = UserTypeEnum::fromKey('ADMINISTRATOR');

        // 方式四：magic
        $administrator4 = UserTypeEnum::ADMINISTRATOR();

        // 方式五：make，尝试根据「常量的值」或「常量的名称」实例化对象常量，实例失败时返回原先传入的值
        $administrator5 = UserTypeEnum::make(0); // 此处尝试根据「常量的值」实例化
        $administrator6 = UserTypeEnum::make('ADMINISTRATOR'); // 此处尝试根据「常量的名称」实例化

        $this->assertInstanceOf(UserTypeEnum::class, $administrator1);
        $this->assertInstanceOf(UserTypeEnum::class, $administrator2);
        $this->assertInstanceOf(UserTypeEnum::class, $administrator3);
        $this->assertInstanceOf(UserTypeEnum::class, $administrator4);
        $this->assertInstanceOf(UserTypeEnum::class, $administrator5);
        $this->assertInstanceOf(UserTypeEnum::class, $administrator6);
    }

    public function testEnumMake()
    {
        // 尝试根据常量的值或者常量的名称实例化一个常量对象
        // 1.尝试根据常量的值实例化常量对象
        $administrator1 = UserTypeEnum::make(UserTypeEnum::ADMINISTRATOR()->value);
        $this->assertEquals(UserTypeEnum::ADMINISTRATOR, $administrator1->value);

        // 2.尝试根据常量的名称实例化常量对象
        $administrator2 = UserTypeEnum::make(UserTypeEnum::ADMINISTRATOR()->key);
        $this->assertEquals(UserTypeEnum::ADMINISTRATOR, $administrator2->value);

        // 3. 尝试用不存在的值来实例化常量对象
        $enum = UserTypeEnum::make(-1);
        $this->assertEquals(-1, $enum); // 不存在时返回传入的值

        $enum = UserTypeEnum::make(null);
        $this->assertEquals(null, $enum); // 不存在时返回传入的值

        // 4. 不区分传入值的类型、大小写等
        $administrator3 = UserTypeEnum::make('0'); // strict 默认为 true，会校验传入值的类型
        $this->assertNotInstanceOf(UserTypeEnum::class, $administrator3);

        $administrator4 = UserTypeEnum::make('0', false); // strict 设置为 false，不校验传入值的类型
        $this->assertInstanceOf(UserTypeEnum::class, $administrator4);

        $administrator5 = UserTypeEnum::make('administrator'); // strict 默认为 true，会校验传入值的大小写
        $this->assertNotInstanceOf(UserTypeEnum::class, $administrator5);

        $administrator4 = UserTypeEnum::make('administrator', false); // strict 设置为 false，不校验传入值的大小写
        $this->assertInstanceOf(UserTypeEnum::class, $administrator4);
    }

    public function testEnumGetRandomKey()
    {
        // 随机获取一个常量的名称
        $this->assertContains(UserTypeEnum::getRandomKey(), UserTypeEnum::getKeys());
    }

    public function testEnumGetRandomValue()
    {
        // 随机获取一个常量的值
        $this->assertContains(UserTypeEnum::getRandomValue(), UserTypeEnum::getValues());
    }

    public function testEnumToArray()
    {
        // 转换为 key => value 形式的数组
        $array = UserTypeEnum::toArray();
        $expectedArray = [
            'ADMINISTRATOR' => 0,
            'MODERATOR' => 1,
            'SUBSCRIBER' => 2,
            'SUPER_ADMINISTRATOR' => 3,
        ];

        $this->assertEquals($expectedArray, $array);
    }

    public function testEnumToSelectArray()
    {
        // 转换为 value => key 形式的数组，可以用于页面的下拉选项
        $array = UserTypeEnum::toSelectArray();
        $expectedArray = [
            0 => 'Administrator',
            1 => 'Moderator',
            2 => 'Subscriber',
            3 => 'Super administrator',
        ];

        $this->assertEquals($expectedArray, $array);
    }

    public function testEnumToSelectArrayWithStringValues()
    {
        // 转换为  value => key 形式的数组，可以用于页面的下拉选项
        $array = StringValuesEnum::toSelectArray();
        $expectedArray = [
            'administrator' => 'Administrator',
            'moderator' => 'Moderator',
        ];

        $this->assertEquals($expectedArray, $array);
    }

    public function testEnumGetKeyUsingStringValue()
    {
        // 根据字符串格式的值获取常量名称
        $this->assertEquals('ADMINISTRATOR', StringValuesEnum::getKey('administrator'));
    }

    public function testEnumGetValueUsingStringKey()
    {
        // 根据字符串格式的名称获取常量的值
        $this->assertEquals('administrator', StringValuesEnum::getValue('ADMINISTRATOR'));
    }

    public function testEnumIs()
    {
        // 获取常量内部定义的全部对象实例
        /** @var StringValuesEnum $administrator */
        /** @var StringValuesEnum $moderator */
        [
            'ADMINISTRATOR' => $administrator,
            'MODERATOR' => $moderator,
        ] = StringValuesEnum::getInstances();

        // 检查某个常量是与另一个常量「值」相同
        $this->assertTrue($administrator->is(StringValuesEnum::ADMINISTRATOR));
        $this->assertTrue($moderator->is(StringValuesEnum::MODERATOR));
    }

    public function testEnumIsAny()
    {
        // 实例化一个常量对象
        $administrator = StringValuesEnum::fromValue(StringValuesEnum::ADMINISTRATOR);

        // 检查某个常量是为属于已定义的任一常量
        $this->assertTrue($administrator->in(StringValuesEnum::getInstances()));
    }

    public function testEnumCanBeCastToString()
    {
        // 常量实例 __toString 后为改常量的「值」
        $enumWithZeroIntegerValue = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);
        $enumWithPositiveIntegerValue = new UserTypeEnum(UserTypeEnum::SUPER_ADMINISTRATOR);
        $enumWithStringValue = new StringValuesEnum(StringValuesEnum::MODERATOR);

        // Numbers should be cast to strings
        $this->assertSame('0', (string) $enumWithZeroIntegerValue);
        $this->assertSame('3', (string) $enumWithPositiveIntegerValue);

        // Strings should just be returned
        $this->assertSame(StringValuesEnum::MODERATOR, (string) $enumWithStringValue);
    }

    public function testEnumIsMacroableWithStaticMethods()
    {
        // 给 Enum 扩展静态方法
        Enum::macro('toFlippedArray', function () {
            return array_flip(self::toArray());
        });

        $this->assertTrue(UserTypeEnum::hasMacro('toFlippedArray'));
        $this->assertEquals(UserTypeEnum::toFlippedArray(), array_flip(UserTypeEnum::toArray()));
    }

    public function testEnumIsMacroableWithInstanceMethods()
    {
        // 给 Enum 扩展实例方法
        Enum::macro('macroGetValue', function () {
            return $this->value;
        });

        $this->assertTrue(UserTypeEnum::hasMacro('macroGetValue'));

        $user = new UserTypeEnum(UserTypeEnum::ADMINISTRATOR);
        $this->assertSame(UserTypeEnum::ADMINISTRATOR, $user->macroGetValue());
    }
}
