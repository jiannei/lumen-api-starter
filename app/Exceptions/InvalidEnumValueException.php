<?php

/*
 * This file is part of Jiannei/lumen-api-starter.
 * (c) Jiannei <longjian.haung@foxmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Exceptions;

use App\Support\Enum\Enum;
use Exception;

class InvalidEnumValueException extends Exception
{
    /**
     * Create an InvalidEnumValueException.
     *
     * @param $invalidValue
     * @param  Enum|string  $enumClass
     */
    public function __construct($invalidValue, $enumClass)
    {
        $invalidValueType = gettype($invalidValue);
        $enumValues = implode(', ', $enumClass::getValues());
        $enumClassName = class_basename($enumClass);

        parent::__construct("Cannot construct an instance of $enumClassName using the value ($invalidValueType) `$invalidValue`. Possible values are [$enumValues].");
    }
}
