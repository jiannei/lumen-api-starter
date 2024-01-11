<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services;

use App\Models\User;

class UserService
{
    public function handleSearchList()
    {
        return User::query()->paginate();
    }

    public function handleSearchSimpleList()
    {
        return User::query()->simplePaginate();
    }

    public function handleSearchCursorList()
    {
        return User::query()->cursorPaginate();
    }

    public function handleSearchItem($id)
    {
        return User::query()->findOrFail($id);
    }

    public function handleCreateItem(array $data)
    {
        return User::query()->create($data);
    }
}
