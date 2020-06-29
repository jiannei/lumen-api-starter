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

use Illuminate\Contracts\Validation\Rule;
use InvalidArgumentException;

class Enum implements Rule
{
    /**
     * @var string|\App\Support\Enum\Enum
     */
    protected $enumClass;

    /**
     * The name of the rule.
     */
    protected $rule = 'enum';

    /**
     * Create a new rule instance.
     *
     * @param  string  $enum
     * @return void
     */
    public function __construct(string $enum)
    {
        $this->enumClass = $enum;

        if (!class_exists($this->enumClass)) {
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
        return "{$this->rule}:{$this->enumClass}";
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return __('enums.validations.enum');
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
        return $value instanceof $this->enumClass;
    }
}
