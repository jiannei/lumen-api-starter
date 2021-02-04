<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

class UserCriteria extends Criteria
{
    protected function condition(Builder $query): void
    {
        if ($name = $this->request->get('name')) {
            $query->where('name', '=', $name);
        }

        if ($email = $this->request->get('email')) {
            $query->where('email', 'like', "%$email%");
        }
    }
}
