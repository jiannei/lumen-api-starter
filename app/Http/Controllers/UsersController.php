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
use Jiannei\Response\Laravel\Support\Facades\Response;

class UsersController extends Controller
{

    public function __construct(private UserService $service)
    {
//        $this->middleware('auth:api', ['except' => ['store', 'show']]);
    }

    public function index(string $paginate = 'paginate')
    {
        $users = match ($paginate) {
            'simple' => $this->service->handleSearchSimpleList(),
            'cursor' => $this->service->handleSearchCursorList(),
            default => $this->service->handleSearchList(),
        };

        return Response::success(UserResource::collection($users));
    }

    public function show($id)
    {
        $user = $this->service->handleSearchItem($id);

        return Response::success($user);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = $this->service->handleCreateItem($request->all());

        return Response::created(new UserResource($user));
    }
}
