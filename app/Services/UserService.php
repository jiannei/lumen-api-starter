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
use App\Repositories\Eloquent\UserRepositoryEloquent;
use App\Repositories\Presenters\UserPresenter;
use Illuminate\Http\Request;

class UserService
{
    private $repository;

    /**
     * UserService constructor.
     *
     * @param  UserRepositoryEloquent  $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handleSearchList(Request $request)
    {
        $this->repository->pushCriteria(new UserCriteria($request));
        $this->repository->setPresenter(UserPresenter::class);

        return $this->repository->paginate($request->get('limit'));
    }

    public function handleSearchSimpleList(Request $request)
    {
        $this->repository->pushCriteria(new UserCriteria($request));
        $this->repository->setPresenter(UserPresenter::class);

        return $this->repository->simplePaginate($request->get('limit'));
    }

    public function handleSearchCursorList(Request $request)
    {
        $this->repository->pushCriteria(new UserCriteria($request));
        $this->repository->setPresenter(UserPresenter::class);

        return $this->repository->cursorPaginate($request->get('limit'));
    }

    public function handleSearchItem($id)
    {
        $this->repository->setPresenter(UserPresenter::class);

        return $this->repository->find($id);
    }

    public function handleCreateItem(Request $request)
    {
        return $this->repository->insertUser($request->all());
    }
}
