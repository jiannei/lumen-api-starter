<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        $this->middleware('auth:api', ['except' => ['store', 'show']]);
    }

    public function index(Request $request)
    {
        $users = $this->userService->handleList($request);

        return $this->response->success($users);
    }

    public function show($id)
    {
        $user = $this->userService->handleProfile($id);

        return $this->response->success($user);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = $this->userService->handleRegistration($request);

        return $this->response->created(new UserResource($user));
    }
}
