<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support\Enum\Providers;

use App\Support\Enum\Http\EnumRequest;
use App\Support\Enum\Rules\Enum;
use App\Support\Enum\Rules\EnumKey;
use App\Support\Enum\Rules\EnumValue;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        $this->bootValidationRules();
    }

    protected function bootValidationRules(): void
    {
        Validator::extend('enum_key', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;
            $strict = $parameters[1] ?? null;

            if (! $strict) {
                return (new EnumKey($enum))->passes($attribute, $value);
            }

            $strict = (bool) json_decode(strtolower($strict));

            return (new EnumKey($enum, $strict))->passes($attribute, $value);
        });

        Validator::extend('enum_value', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;
            $strict = $parameters[1] ?? null;

            if (! $strict) {
                return (new EnumValue($enum))->passes($attribute, $value);
            }

            $strict = (bool) json_decode(strtolower($strict));

            return (new EnumValue($enum, $strict))->passes($attribute, $value);
        });

        Validator::extend('enum', function (string $attribute, $value, array $parameters, ValidatorContract $validator): bool {
            $enum = $parameters[0] ?? null;

            return (new Enum($enum))->passes($attribute, $value);
        });
    }

    public function register()
    {
        $this->registerRequestTransformMacro();
    }

    protected function registerRequestTransformMacro()
    {
        Request::mixin(new EnumRequest);
    }
}
