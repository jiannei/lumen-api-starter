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


use App\Repositories\Enums\ExampleEnum;
use Tests\TestCase;

class EnumLocalizationTest extends TestCase
{
    public function testEnumGetDescriptionWithLocalization()
    {
        // 常量描述本地化：存在相应语言包的时候取语言包中对应的描述
        $this->app->setLocale('en');
        $this->assertEquals('Super administrator', ExampleEnum::getDescription(ExampleEnum::SUPER_ADMINISTRATOR));

        $this->app->setLocale('zh-CN');
        $this->assertEquals('超级管理员', ExampleEnum::getDescription(ExampleEnum::SUPER_ADMINISTRATOR));
    }

    public function testEnumGetDescriptionForMissingLocalizationKey()
    {
        // 常量描述本地化：语言包中不存在相应常量对应描述，根据常量的名称（key）值进行转换
        $this->app->setLocale('en');
        $this->assertEquals('Moderator', ExampleEnum::getDescription(ExampleEnum::MODERATOR));

        $this->app->setLocale('zh-CN');
        $this->assertEquals('Moderator', ExampleEnum::getDescription(ExampleEnum::MODERATOR));
    }
}
