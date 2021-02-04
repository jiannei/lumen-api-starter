<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\UserRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Models\User;
use App\Repositories\Validators\UserValidator;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRepositoryEloquent.
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
        'email', // Default Condition "="
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return UserValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function insertUser($attributes)
    {
        $this->model->name = $attributes['name'];
        $this->model->email = $attributes['email'];
        $this->model->password = Hash::make($attributes['password']);
        $this->model->saveOrFail();

        return $this->model;
    }
}
