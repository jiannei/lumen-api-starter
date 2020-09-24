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

        $this->middleware('auth:api', ['except' => ['store', 'show', 'index']]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     *
     * @api               {get} users 获取用户列表
     * @apiName           get_users
     * @apiGroup          Users
     *
     * @apiParam {String} name 用户名
     * @apiParam {String} email  邮箱
     *
     * @apiSuccess {Array} data  列表数据.
     * @apiSuccess {String} data.nickname   昵称
     * @apiSuccess {String} data.email   邮箱
     *
     * @apiSuccessExample Success-Response:
     * {"status":"success","code":200,"message":"\u64cd\u4f5c\u6210\u529f","data":{"data":[{"nickname":"chunpat","email":"398949389@qq.com"},{"nickname":"chunpat1","email":"3989493891@qq.com"},{"nickname":"chunpat2","email":"3989493892@qq.com"}],"meta":{"pagination":{"total":4,"count":3,"per_page":3,"current_page":1,"total_pages":2,"links":{"next":"http:\/\/127.0.0.1:9898\/users?page=2"}}}}}
     *
     * @apiUse            FailResponse
     *
     */
    public function index(Request $request)
    {
        $users = $this->userService->handleList($request);

        return $this->response->success($users);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     *
     * @api               {get} users/:id 获取用户
     * @apiName           get_users_by_id
     * @apiGroup          Users
     *
     * @apiParam {Number} id Users unique ID.
     *
     * @apiSuccess {String} nickname   昵称
     * @apiSuccess {String} email   邮箱
     *
     * @apiSuccessExample Success-Response:
     * {"status":"success","code":200,"message":"\u64cd\u4f5c\u6210\u529f","data":{"nickname":"chunpat","email":"398949389@qq.com"}}
     * @apiUse            FailResponse
     *
     */
    public function show($id)
    {
        $user = $this->userService->handleProfile($id);

        return $this->response->success($user);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     * @throws \Illuminate\Validation\ValidationException
     *
     * @api               {post} users 新增用户
     * @apiName           post_users
     * @apiGroup          Users
     *
     * @apiParam {String} name 昵称 必填
     * @apiParam {String} email 邮箱 必填
     * @apiParam {String} password 最小8位 必填
     *
     * @apiSuccess {String} nickname   昵称
     * @apiSuccess {String} email   邮箱
     *
     * @apiUse BaseResponse
     *
     * @apiSuccessExample Success-Response:
     * {"status":"success","code":201,"message":"Created","data":{"nickname":"chunpat4","email":"3989493894@qq.com"}}
     * @apiUse            FailResponse
     */
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
