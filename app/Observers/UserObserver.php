<?php

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
