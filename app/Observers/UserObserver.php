<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Observers;

use App\Repositories\Enums\CacheEnum;
use App\Repositories\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function updated(User $user)
    {
        Cache::forget(CacheEnum::getCacheKey(CacheEnum::AUTHORIZATION_USER));
    }

    public function deleted(User $user)
    {
        Cache::forget(CacheEnum::getCacheKey(CacheEnum::AUTHORIZATION_USER));
    }
}
