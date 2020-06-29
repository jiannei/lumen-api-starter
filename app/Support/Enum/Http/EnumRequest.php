<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support\Enum\Http;

use Closure;
use Illuminate\Http\Request;

/**
 * @internal This class is only used to get mixed into \Illuminate\Http\Request
 *
 * @mixin Request
 */
final class EnumRequest
{
    public function transformEnums(): Closure
    {
        return function (array $transformations, bool $strict): void {
            foreach ($transformations as $key => $enumClass) {
                if (! $this->request->has($key)) {
                    continue;
                }

                $requestKey = $this->request->get($key);
                $enumKeyOrValue = is_numeric($requestKey) ? (int) $requestKey : $requestKey;

                $this->request->set(
                    $key,
                    forward_static_call(
                        [$enumClass, 'make'],
                        $enumKeyOrValue,
                        $strict
                    )
                );
            }
        };
    }
}
