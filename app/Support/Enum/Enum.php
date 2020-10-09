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

use App\Contracts\Enums\EnumContract;
use App\Contracts\Enums\LocalizedEnumContract;
use App\Exceptions\InvalidEnumKeyException;
use App\Exceptions\InvalidEnumValueException;
use App\Support\Enum\Cast\EnumCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;

abstract class Enum implements EnumContract, Castable
{
    use Macroable {
        __callStatic as macroCallStatic;
        __call as macroCall;
    }

    protected static $cache = [];
    public $description;
    public $key;
    public $value;

    public function __construct($enumValue, bool $strict = true)
    {
        if (! static::hasValue($enumValue, $strict)) {
            throw new InvalidEnumValueException($enumValue, $this);
        }

        $this->key = static::getKey($enumValue);
        $this->value = $enumValue;
        $this->description = static::getDescription($enumValue);
    }

    public static function hasValue($value, bool $strict = true): bool
    {
        $validValues = static::getValues();

        if ($strict) {
            return in_array($value, $validValues, true);
        }

        return in_array((string) $value, array_map('strval', $validValues), true);
    }

    public static function getValues(): array
    {
        return array_values(static::getConstants());
    }

    protected static function getConstants(): array
    {
        $calledClass = static::class;

        if (! array_key_exists($calledClass, static::$cache)) {
            $reflect = new ReflectionClass($calledClass);
            static::$cache[$calledClass] = $reflect->getConstants();
        }

        return static::$cache[$calledClass];
    }

    public static function getKey($value): string
    {
        return array_search($value, static::getConstants(), true);
    }

    public static function getDescription($value): string
    {
        return static::getLocalizedDescription($value) ?? static::getFriendlyKeyName(static::getKey($value));
    }

    protected static function getLocalizedDescription($value): ?string
    {
        if (static::isLocalizable()) {
            $localizedStringKey = static::getLocalizationKey().'.'.$value;

            if (Lang::has($localizedStringKey)) {
                return __($localizedStringKey);
            }
        }

        return null;
    }

    protected static function isLocalizable(): bool
    {
        return isset(class_implements(static::class)[LocalizedEnumContract::class]);
    }

    public static function getLocalizationKey(): string
    {
        return config('enum.localization.key').'.'.static::class;
    }

    protected static function getFriendlyKeyName(string $key): string
    {
        if (ctype_upper(str_replace('_', '', $key))) {
            $key = strtolower($key);
        }

        return ucfirst(str_replace('_', ' ', Str::snake($key)));
    }

    public static function getInstance($enumValue): self
    {
        return static::fromValue($enumValue);
    }

    public static function fromValue($enumValue, bool $strict = true): self
    {
        if ($enumValue instanceof static) {
            return $enumValue;
        }

        return new static($enumValue, $strict);
    }

    public static function getInstances(): array
    {
        return array_map(
            function ($constantValue) {
                return new static($constantValue);
            },
            static::getConstants()
        );
    }

    public static function getRandomInstance(): self
    {
        return new static(static::getRandomValue());
    }

    public static function getRandomValue()
    {
        $values = static::getValues();

        return $values[array_rand($values)];
    }

    public static function getRandomKey(): string
    {
        $keys = static::getKeys();

        return $keys[array_rand($keys)];
    }

    public static function getKeys(): array
    {
        return array_keys(static::getConstants());
    }

    public static function make($enumKeyOrValue, bool $strict = true)
    {
        if ($enumKeyOrValue instanceof static) {
            return $enumKeyOrValue;
        }

        if (static::hasValue($enumKeyOrValue, $strict)) {
            return static::fromValue($enumKeyOrValue, $strict);
        }

        if (static::hasKey($enumKeyOrValue, $strict)) {
            return static::fromKey($enumKeyOrValue, $strict);
        }

        return $enumKeyOrValue;
    }

    public static function hasKey($key, bool $strict = true): bool
    {
        $validKeys = static::getKeys();
        if ($strict) {
            return in_array($key, $validKeys, true);
        }

        return in_array(strtolower((string) $key), array_map('strtolower', $validKeys), true);
    }

    public static function fromKey(string $key, bool $strict = true): self
    {
        if (! static::hasKey($key, $strict)) {
            throw new InvalidEnumKeyException($key, static::class);
        }

        $key = $strict ? $key : strtoupper($key);
        $enumValue = static::getValue($key);

        return new static($enumValue, $strict);
    }

    public static function getValue(string $key)
    {
        return static::getConstants()[$key];
    }

    public static function parseDatabase($value)
    {
        return $value;
    }

    public static function serializeDatabase($value)
    {
        return $value;
    }

    public static function castUsing(array $arguments)
    {
        return new EnumCast(static::class);
    }

    public static function toSelectArray(): array
    {
        $array = static::toArray();
        $selectArray = [];

        foreach ($array as $key => $value) {
            $selectArray[$value] = static::getDescription($value);
        }

        return $selectArray;
    }

    public static function toArray(): array
    {
        return static::getConstants();
    }

    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return self::__callStatic($method, $parameters);
    }

    public static function __callStatic($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return static::macroCallStatic($method, $parameters);
        }

        return static::fromKey($method);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function in(array $values): bool
    {
        foreach ($values as $value) {
            if ($this->is($value)) {
                return true;
            }
        }

        return false;
    }

    public function is($enumValue): bool
    {
        if ($enumValue instanceof static) {
            return $this->value === $enumValue->value;
        }

        return $this->value === $enumValue;
    }

    public function isNot($enumValue): bool
    {
        return ! $this->is($enumValue);
    }
}
