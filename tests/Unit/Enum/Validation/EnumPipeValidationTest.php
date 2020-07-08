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

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumPipeValidationTest extends TestCase
{
    public function testValidateValueUsingPipeValidation()
    {
        // 根据常量的值（value）来判断是否合法：是否存在某个常量值
        $validator = Validator::make(['type' => UserTypeEnum::ADMINISTRATOR], [
            'type' => 'enum_value:'.UserTypeEnum::class,
        ]);

        $this->assertTrue($validator->passes());

        $validator = Validator::make(['type' => 99], [
            'type' => 'enum_value:'.UserTypeEnum::class,
        ]);

        $this->assertFalse($validator->passes());
    }

    public function testValidateValueUsingPipeValidationWithoutStrictTypeChecking()
    {
        // 根据常量的值（value）来判断是否合法：是否存在某个常量值
        // 默认 strict 为 true，校验类名称型或字符串大小写
        // strict 为 true 时，这里的 '1' 必须是整数
        $validator = Validator::make(['type' => '1'], [
            'type' => 'enum_value:'.UserTypeEnum::class.',false',
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testValidateKeyUsingPipeValidation()
    {
        // 根据常量的名称（key）来判断是否合法：是否存在某个常量名称
        $validator = Validator::make(['type' => 'ADMINISTRATOR'], [
            'type' => 'enum_key:'.UserTypeEnum::class,
        ]);

        $this->assertTrue($validator->passes());

        $validator = Validator::make(['type' => 'wrong'], [
            'type' => 'enum_key:'.UserTypeEnum::class,
        ]);

        $this->assertFalse($validator->passes());
    }

    public function testValidateKeyUsingPipeValidationWithoutStrictTypeChecking()
    {
        // 根据常量的名称（key）来判断是否合法：是否存在某个常量名称
        // strict 为 false 时，不校验大小写
        $validator = Validator::make(['type' => 'administrator'], [
            'type' => 'enum_key:'.UserTypeEnum::class.',false',
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testValidateEnumUsingPipeValidation()
    {
        //  // 校验常量是否属于某一个常量 class
        $validator = Validator::make(['type' => UserTypeEnum::ADMINISTRATOR()], [
            'type' => 'enum:'.UserTypeEnum::class,
        ]);

        $this->assertTrue($validator->passes());

        $validator = Validator::make(['type' => 'wrong'], [
            'type' => 'enum:'.UserTypeEnum::class,
        ]);

        $this->assertFalse($validator->passes());
    }
}
