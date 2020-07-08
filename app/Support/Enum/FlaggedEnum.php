<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support\Enum;

use App\Exceptions\InvalidEnumValueException;

abstract class FlaggedEnum extends Enum
{
    const NONE = 0;

    /**
     * The value of one of the enum members.
     *
     * @var mixed
     */
    public $value;

    /**
     * Construct a FlaggedEnum instance.
     *
     * @param  int[]|Enum[]|mixed  $flags
     * @return void
     */
    public function __construct($flags)
    {
        $this->key = null;
        $this->description = null;

        if (is_array($flags)) {
            $this->setFlags($flags);
        } else {
            try {
                parent::__construct($flags);
            } catch (InvalidEnumValueException $exception) {
                $this->value = $flags;
            }
        }
    }

    /**
     * Set the flags for the enum to the given array of flags.
     *
     * @param  int[]|Enum[]  $flags
     * @return self
     */
    public function setFlags(array $flags): self
    {
        $this->value = array_reduce($flags, function ($carry, $flag) {
            return $carry | static::fromValue($flag)->value;
        }, 0);

        return $this;
    }

    /**
     * Return a FlaggedEnum instance with defined flags.
     *
     * @param  int[]|Enum[]  $flags
     * @return FlaggedEnum
     */
    public static function flags(array $flags): self
    {
        return static::fromValue($flags);
    }

    /**
     * Add the given flags to the enum.
     *
     * @param  int[]|Enum[]  $flags
     * @return self
     */
    public function addFlags(array $flags): self
    {
        array_map(function ($flag) {
            $this->addFlag($flag);
        }, $flags);

        return $this;
    }

    /**
     * Add the given flag to the enum.
     *
     * @param  int|Enum  $flag
     * @return self
     */
    public function addFlag($flag): self
    {
        $this->value |= static::fromValue($flag)->value;

        return $this;
    }

    /**
     * Get the bitmask for the enum.
     *
     * @return int
     */
    public function getBitmask(): int
    {
        return (int) decbin($this->value);
    }

    /**
     * Return the flags as an array of instances.
     *
     * @return Enum[]
     */
    public function getFlags(): array
    {
        $members = static::getInstances();
        $flags = [];

        foreach ($members as $member) {
            if ($this->hasFlag($member)) {
                $flags[] = $member;
            }
        }

        return $flags;
    }

    /**
     * Check if the enum has the specified flag.
     *
     * @param  int|Enum  $flag
     * @return bool
     */
    public function hasFlag($flag): bool
    {
        $flagValue = static::fromValue($flag)->value;
        if ($flagValue === 0) {
            return false;
        }

        return ($flagValue & $this->value) === $flagValue;
    }

    /**
     * Check if the enum has all of the specified flags.
     *
     * @param  int[]|Enum[]  $flags
     * @return bool
     */
    public function hasFlags(array $flags): bool
    {
        foreach ($flags as $flag) {
            if (! static::hasFlag($flag)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if there are multiple flags set on the enum.
     *
     * @return bool
     */
    public function hasMultipleFlags(): bool
    {
        return ($this->value & ($this->value - 1)) !== 0;
    }

    /**
     * Check if the enum doesn't have any of the specified flags.
     *
     * @param  int[]|Enum[]  $flags
     * @return bool
     * @throws InvalidEnumValueException
     */
    public function notHasFlags(array $flags): bool
    {
        foreach ($flags as $flag) {
            if (! static::notHasFlag($flag)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the enum does not have the specified flag.
     *
     * @param  int|Enum  $flag
     * @return bool
     * @throws InvalidEnumValueException
     */
    public function notHasFlag($flag): bool
    {
        return ! $this->hasFlag($flag);
    }

    /**
     * Remove the given flags from the enum.
     *
     * @param  int[]|Enum[]  $flags
     * @return self
     */
    public function removeFlags(array $flags): self
    {
        array_map(function ($flag) {
            $this->removeFlag($flag);
        }, $flags);

        return $this;
    }

    /**
     * Remove the given flag from the enum.
     *
     * @param  int|Enum  $flag
     * @return self
     */
    public function removeFlag($flag): self
    {
        $this->value &= ~static::fromValue($flag)->value;

        return $this;
    }
}
