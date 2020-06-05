<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services;

use App\Contracts\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    private $repository;

    /**
     * UserService constructor.
     *
     * @param \App\Repositories\Eloquent\UserRepositoryEloquent $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getUsersByPage()
    {
        return $this->repository->paginate(3);
    }

    public function getUserById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Request $request
     *
     * @return \App\Models\User
     *
     * @throws \Throwable
     */
    public function register(Request $request)
    {
        return $this->repository->add($request->all());
    }
}
