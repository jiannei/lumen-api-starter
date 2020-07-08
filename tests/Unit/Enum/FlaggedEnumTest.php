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
use Tests\Unit\Enum\Enums\SuperPowersEnum;

class FlaggedEnumTest extends TestCase
{
    public function testCanConstructFlaggedEnumUsingStaticProperties()
    {
        // 实例化 Flagged 常量对象的几种方式

        // 方式一：new
        $powers1 = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::LASER_VISION]);// 传入包含「常量值」的数组
        $this->assertInstanceOf(SuperPowersEnum::class, $powers1);

        $powers2 = new SuperPowersEnum(SuperPowersEnum::STRENGTH);// 传入单个「常量值」
        $this->assertInstanceOf(SuperPowersEnum::class, $powers2);

        // 方式二：fromValue
        $powers3 = SuperPowersEnum::fromValue([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::LASER_VISION]);// 传入包含「常量值」的数组
        $this->assertInstanceOf(SuperPowersEnum::class, $powers3);

        $powers4 = SuperPowersEnum::fromValue(SuperPowersEnum::FLIGHT);// 传入单个「常量值」
        $this->assertInstanceOf(SuperPowersEnum::class, $powers4);

        // 方式三：flags
        $powers5 = SuperPowersEnum::flags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::LASER_VISION]);// 传入包含「常量值」的数组
        $this->assertInstanceOf(SuperPowersEnum::class, $powers5);

        // 方式四：fromKey
        $powers6 = SuperPowersEnum::fromKey('immortality', false);// 传入单个「常量名称」
        $this->assertInstanceOf(SuperPowersEnum::class, $powers6);

        // 方式五：magic
        $powers7 = SuperPowersEnum::TIME_TRAVEL();
        $this->assertInstanceOf(SuperPowersEnum::class, $powers7);

        // 方式六：make
        $powers8 = SuperPowersEnum::make(SuperPowersEnum::SUPERMAN);
        $this->assertInstanceOf(SuperPowersEnum::class, $powers8);
    }

    public function testCanConstructFlaggedEnumUsingInstances()
    {
        // 实例化 Flagged 常量对象的几种方式

        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH(), SuperPowersEnum::FLIGHT(), SuperPowersEnum::LASER_VISION()]);
        $this->assertInstanceOf(SuperPowersEnum::class, $powers);

        $powers = SuperPowersEnum::fromValue([SuperPowersEnum::STRENGTH(), SuperPowersEnum::FLIGHT(), SuperPowersEnum::LASER_VISION()]);
        $this->assertInstanceOf(SuperPowersEnum::class, $powers);

        $powers = SuperPowersEnum::flags([SuperPowersEnum::STRENGTH(), SuperPowersEnum::FLIGHT(), SuperPowersEnum::LASER_VISION()]);
        $this->assertInstanceOf(SuperPowersEnum::class, $powers);
    }

    public function testCanCheckIfInstanceHasFlag()
    {
        // 检查 FlaggedEnum 是否包含「某个」常量标记
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

        $this->assertTrue($powers->hasFlag(SuperPowersEnum::STRENGTH()));
        $this->assertTrue($powers->hasFlag(SuperPowersEnum::STRENGTH));
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::LASER_VISION()));
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::LASER_VISION));
    }

    public function testCanCheckIfInstanceHasFlags()
    {
        // 检查 FlaggedEnum 是否包含「多个」常量标记
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

        $this->assertTrue($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]));
        $this->assertFalse($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::INVISIBILITY]));
    }

    public function testCanCheckIfInstanceDoesNotHaveFlag()
    {
        // 检查 FlaggedEnum 是否不包含「某个」常量标记
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

        $this->assertTrue($powers->notHasFlag(SuperPowersEnum::LASER_VISION()));
        $this->assertTrue($powers->notHasFlag(SuperPowersEnum::LASER_VISION));
        $this->assertFalse($powers->notHasFlag(SuperPowersEnum::STRENGTH()));
        $this->assertFalse($powers->notHasFlag(SuperPowersEnum::STRENGTH));
    }

    public function testCanCheckIfInstanceDoesNotHaveFlags()
    {
        // 检查 FlaggedEnum 是否不包含「多个」常量标记
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

        $this->assertTrue($powers->notHasFlags([SuperPowersEnum::INVISIBILITY, SuperPowersEnum::LASER_VISION]));
        $this->assertFalse($powers->notHasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::LASER_VISION]));
        $this->assertFalse($powers->notHasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]));
    }

    public function testCanSetFlags()
    {
        //  往 FlaggedEnum 中添加多个标记
        $powers = SuperPowersEnum::NONE();
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::LASER_VISION));

        $powers->setFlags([SuperPowersEnum::LASER_VISION, SuperPowersEnum::STRENGTH]);
        $this->assertTrue($powers->hasFlag(SuperPowersEnum::LASER_VISION));
        $this->assertTrue($powers->hasFlag(SuperPowersEnum::STRENGTH));
    }

    public function TestCanAddFlag()
    {
        //  往 FlaggedEnum 中添加单个标记
        $powers = SuperPowersEnum::NONE();
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::IMMORTALITY));

        $powers->addFlag(SuperPowersEnum::IMMORTALITY);
        $this->assertTrue($powers->hasFlag(SuperPowersEnum::IMMORTALITY));

        $powers->addFlag(SuperPowersEnum::TELEPORTATION);
        $this->assertTrue($powers->hasFlag(SuperPowersEnum::TELEPORTATION));
    }

    public function testCanAddFlags()
    {
        //  往 FlaggedEnum 中添加多个标记
        $powers = new SuperPowersEnum(SuperPowersEnum::NONE);
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::LASER_VISION));

        $powers->addFlags([SuperPowersEnum::LASER_VISION, SuperPowersEnum::STRENGTH]);
        $this->assertTrue($powers->hasFlags([SuperPowersEnum::LASER_VISION, SuperPowersEnum::STRENGTH]));
    }

    public function testCanRemoveFlag()
    {
        // 移除 FlaggedEnum 中的「单个」标记
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);
        $this->assertTrue($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]));

        $powers->removeFlag(SuperPowersEnum::STRENGTH);
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::STRENGTH));

        $powers->removeFlag(SuperPowersEnum::FLIGHT);
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::FLIGHT));

        $this->assertTrue($powers->is(SuperPowersEnum::NONE));
    }

    public function testCanRemoveFlags()
    {
        // 移除 FlaggedEnum 中的「多个」标记
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);
        $this->assertTrue($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]));

        $powers->removeFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);
        $this->assertFalse($powers->hasFlags([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]));

        $this->assertTrue($powers->is(SuperPowersEnum::NONE));
    }

    public function testCanGetFlags()
    {
        // 获取 FlaggedEnum 中已定义的标记
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT, SuperPowersEnum::INVISIBILITY]);
        $flags = $powers->getFlags();

        $this->assertCount(3, $flags);
        $this->assertContainsOnlyInstancesOf(SuperPowersEnum::class, $flags);
    }

    public function testCanSetShortcutValues()
    {
        // FlaggedEnum 也可以通过单个常量值来实例化
        $powers = new SuperPowersEnum(SuperPowersEnum::SUPERMAN);

        $this->assertTrue($powers->hasFlag(SuperPowersEnum::STRENGTH));
        $this->assertTrue($powers->hasFlag(SuperPowersEnum::LASER_VISION));
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::TIME_TRAVEL));
    }

    public function testShortcutValuesAreComparableToExplicitSet()
    {
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::LASER_VISION, SuperPowersEnum::FLIGHT]);
        $this->assertTrue($powers->hasFlag(SuperPowersEnum::SUPERMAN));

        $powers->removeFlag(SuperPowersEnum::LASER_VISION);
        $this->assertFalse($powers->hasFlag(SuperPowersEnum::SUPERMAN));
    }

    public function testCanCheckIfInstanceHasMultipleFlagsSet()
    {
        // 检查某个 FlaggedEnum 是否包含多个标记
        $this->assertTrue(SuperPowersEnum::SUPERMAN()->hasMultipleFlags());
        $this->assertFalse(SuperPowersEnum::STRENGTH()->hasMultipleFlags());
        $this->assertFalse(SuperPowersEnum::NONE()->hasMultipleFlags());
    }

    public function testCanGetBitmaskForAnInstance()
    {
        // 获取常量对应的二进制值
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);
        $this->assertEquals(1001, $powers->getBitmask());

        $this->assertEquals(1101, SuperPowersEnum::SUPERMAN()->getBitmask());
    }

    public function testCanInstantiateAFlaggedEnumFromAValueWhichHasMultipleFlagsSet()
    {
        $powers = new SuperPowersEnum([SuperPowersEnum::STRENGTH, SuperPowersEnum::FLIGHT]);

        $this->assertEquals($powers, SuperPowersEnum::fromValue($powers->value));
    }
}
