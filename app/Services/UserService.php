<?php


namespace App\Services;


use App\Contracts\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    private $repository;

    /**
     * UserService constructor.
     * @param  \App\Repositories\Eloquent\UserRepositoryEloquent  $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getUserList()
    {
        return $this->repository->simplePaginate();
    }

    public function getUserById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param  Request  $request
     * @return \App\Models\User
     * @throws \Throwable
     */
    public function register(Request $request)
    {
        return $this->repository->add($request->all());
    }
}
