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

use App\Http\Middleware\TransformEnums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('enum', [
            'localization' => ['key' => 'enums'], 'transformations' => ['user_type' => UserTypeEnum::class],
        ]);

        $this->withoutMiddleware();
    }

    public function testCanTransformARequestParameterToEnumByKey()
    {
        // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）

        // 根据枚举名称来转换
        $this->post('test/enums', [
            'user_type' => 'MODERATOR',
        ]);

        (new TransformEnums())->handle($this->app['request'], function (Request $request) {
            $this->assertInstanceOf(UserTypeEnum::class, $request->get('user_type'));
            $this->assertEquals('MODERATOR', $request['user_type']->key);
        });
    }

    public function testCanTransformARequestParameterToEnumByValue()
    {
        // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）

        // 根据枚举值来转换
        $this->post('test/enums', [
            'user_type' => 1,
        ]);

        (new TransformEnums())->handle($this->app['request'], function (Request $request) {
            $this->assertInstanceOf(UserTypeEnum::class, $request->get('user_type'));
            $this->assertEquals('MODERATOR', $request['user_type']->key);
        });
    }

    public function testCanTransformARequestParameterToEnumByKeyWithoutStrictTypeChecking()
    {
        // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）

        // 根据枚举名称来转换
        $this->post('test/enums', [
            'user_type' => 'moderator', // strict 为 true 时，这里必须和枚举名称大小写一样
        ]);

        (new TransformEnums())->handle($this->app['request'], function (Request $request) {
            $this->assertInstanceOf(UserTypeEnum::class, $request->get('user_type'));
            $this->assertEquals('MODERATOR', $request['user_type']->key);
        }, false); // 设置 strict 为 false
    }

    public function testCanTransformARequestParameterToEnumByValueWithoutStrictTypeChecking()
    {
        // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）

        // 根据枚举值来转换
        $this->post('test/enums', [
            'user_type' => '1', // strict 为 true 时，这里必须和枚举名值类型一样
        ]);

        (new TransformEnums())->handle($this->app['request'], function (Request $request) {
            $this->assertInstanceOf(UserTypeEnum::class, $request->get('user_type'));
            $this->assertEquals('MODERATOR', $request['user_type']->key);
        }, false); // 设置 strict 为 false
    }
}
