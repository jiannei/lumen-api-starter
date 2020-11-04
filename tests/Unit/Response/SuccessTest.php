<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Unit\Response;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Repositories\Enums\ResponseCodeEnum;
use App\Repositories\Models\User;
use App\Support\Traits\ResponseTrait;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class SuccessTest extends TestCase
{
    use ResponseTrait, DatabaseMigrations;

    public function testSuccess()
    {
        // 方式一：直接返回响应成功
        $response = $this->response()->success(); // 注意：这里必须使用 this->response() 函数方式调用，因为 MakesHttpRequests 中有 response 属性

        $this->assertEquals(200, $response->status());

        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => ResponseCodeEnum::fromValue(200)->description,
            'data' => (object) [],
            'error' => []
        ]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testCreated()
    {
        // 方式二：返回创建成功
        $response = $this->response()->created();

        $this->assertEquals(201, $response->status());

        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 201,
            'message' => ResponseCodeEnum::fromValue(201)->description,
            'data' => (object) [],
            'error' => []
        ]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testAccepted()
    {
        // 方式三：返回接收成功
        $response = $this->response()->accepted();

        $this->assertEquals(202, $response->status());

        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 202,
            'message' => ResponseCodeEnum::fromValue(202)->description,
            'data' => (object) [],
            'error' => []
        ]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testNoContent()
    {
        // 方式四：返回空内容；创建成功或删除成功等场景
        $response = $this->response()->noContent();

        $this->assertEquals(204, $response->status());

        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 204,
            'message' => ResponseCodeEnum::fromValue(204)->description,
            'data' => (object) [],
            'error' => []
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testSuccessWithArrayData()
    {
        // 方式五：返回普通的数组
        $data = [
            'name' => 'Jiannei',
            'email' => 'longjian.huang@foxmail.com',
        ];
        $response = $this->response()->success($data);

        $this->assertEquals(200, $response->status());

        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => ResponseCodeEnum::fromValue(200)->description,
            'data' => $data,
            'error' => []
        ]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testSuccessWithResourceData()
    {
        // 方式六：返回 Api resource
        $user = User::factory()->make();
        $response = $this->response()->success(new UserResource($user));

        $this->assertEquals(200, $response->status());
        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => ResponseCodeEnum::fromValue(200)->description,
            'data' => [
                'nickname' => $user->name,
                'email' => $user->email,
            ],
            'error' => []
        ]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testSuccessWithCollectionData()
    {
        // 方式七：返回 Api collection
        $users = User::factory()->count(10)->make();
        $response = $this->response()->success(new UserCollection($users));

        $this->assertEquals(200, $response->status());

        $data = $users->map(function ($item) {
            return [
                'nickname' => $item->name,
                'email' => $item->email,
            ];
        })->all();
        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => ResponseCodeEnum::fromValue(200)->description,
            'data' => $data,
            'error' => []
        ]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testSuccessWithPaginatedData()
    {
        // 方式八：返回分页的 Api collection
        User::factory()->count(20)->create();
        $users = User::paginate();

        $response = $this->response()->success(new UserCollection($users));

        $this->assertEquals(200, $response->status());

        $paginated = $users->toArray();
        $formatData = $users->map(function ($item) {
            return [
                'nickname' => $item->name,
                'email' => $item->email,
            ];
        })->all();
        $data = [
            'data' => $formatData,
            'meta' => [
                'pagination' => [
                    'total' => $paginated['total'] ?? null,
                    'count' => $paginated['to'] ?? null,
                    'per_page' => $paginated['per_page'] ?? null,
                    'current_page' => $paginated['current_page'] ?? null,
                    'total_pages' => $paginated['last_page'] ?? null,
                    'links' => [
                        'previous' => $paginated['prev_page_url'] ?? null,
                        'next' => $paginated['next_page_url'] ?? null,
                    ],
                ],
            ],
        ];
        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => ResponseCodeEnum::fromValue(200)->description,
            'data' => $data,
            'error' => []
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testSuccessWithMessage()
    {
        // 方式九：返回指定的 Message
        $response = $this->response()->success(null, '成功');

        $expectedJson = json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => '成功',
            'data' => (object) [],
            'error' => []
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testSuccessWithCustomMessageAndCode()
    {
        // 方式十：根据预定义的「业务码」和「对应的描述信息」返回
        $response = $this->response()->success(null, '', ResponseCodeEnum::SERVICE_LOGIN_SUCCESS);

        $expectedJson = json_encode([
            'status' => 'success',
            'code' => ResponseCodeEnum::SERVICE_LOGIN_SUCCESS, // 返回自定义的业务码
            'message' => ResponseCodeEnum::fromValue(ResponseCodeEnum::SERVICE_LOGIN_SUCCESS)->description, // 根据业务码取多语言的业务描述
            'data' => (object) [],
            'error' => []
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }
}
