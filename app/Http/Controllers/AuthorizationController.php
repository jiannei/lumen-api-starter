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
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class AuthorizationController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    public function destroy()
    {
        auth()->logout();

        return Response::noContent();
    }

    public function show()
    {
        $user = auth()->userOrFail();

        return Response::success(new UserResource($user));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'filled|email',
            'name' => 'required_without:email',
            'password' => 'required',
        ]);

        $credentials = request(['name', 'email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            Response::errorUnauthorized();
        }

        return $this->respondWithToken($token);
    }
}
