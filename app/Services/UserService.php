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
use App\Repositories\Criteria\UserCriteria;
use App\Repositories\Presenters\UserPresenter;
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

    public function listPage(Request $request)
    {
        $this->repository->pushCriteria(new UserCriteria($request));
        $this->repository->setPresenter(UserPresenter::class);

        return $this->repository->searchUsersByPage();
    }

    public function profilePage($id)
    {
        return $this->repository->searchUserBy($id);
    }

    public function register(Request $request)
    {
        return $this->repository->insertUser($request->all());
    }
}
