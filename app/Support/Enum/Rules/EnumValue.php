<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support\Enum\Rules;

use App\Support\Enum\FlaggedEnum;
use Illuminate\Contracts\Validation\Rule;
use InvalidArgumentException;

class EnumValue implements Rule
{
    /**
     * @var string|\App\Support\Enum\Enum
     */
    protected $enumClass;

    /**
     * The name of the rule.
     */
    protected $rule = 'enum_value';

    /**
     * @var bool
     */
    protected $strict;

    /**
     * Create a new rule instance.
     *
     * @param  string|\App\Support\Enum\Enum  $enumClass
     * @param  bool  $strict
     * @return void
     */
    public function __construct($enumClass, bool $strict = true)
    {
        $this->enumClass = $enumClass;
        $this->strict = $strict;

        if (! class_exists($this->enumClass)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enumClass} doesn't exist.");
        }
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     *
     * @see \Illuminate\Validation\ValidationRuleParser::parseParameters
     */
    public function __toString()
    {
        $strict = $this->strict ? 'true' : 'false';

        return "{$this->rule}:{$this->enumClass},{$strict}";
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return __('enums.validations.enum_value');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_subclass_of($this->enumClass, FlaggedEnum::class) && (is_int($value) || ctype_digit($value))) {
            // Unset all possible flag values
            foreach ($this->enumClass::getValues() as $enumValue) {
                $value &= ~$enumValue;
            }

            // All bits should be unset
            return $value === 0;
        }

        if (is_subclass_of($value, \App\Support\Enum\Enum::class)) {
            $value = $value->value;
        }

        return $this->enumClass::hasValue($value, $this->strict);
    }
}
