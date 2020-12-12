<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories\Transformers;

use App\Repositories\Enums\PermissionEnum;
use App\Repositories\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $data = [
            'id' => $user->id,
            'nickname' => $user->name,
            'email' => $user->email,
        ];

        if (! $this->checkColumnPermission()) {
            $data['email'] = '**** ****';
        }

        return $data;
    }

    protected function checkColumnPermission()
    {
        return auth('api')->user()->can(PermissionEnum::DATA_USERS_COLUMN_EMAIL()->name);
    }
}
