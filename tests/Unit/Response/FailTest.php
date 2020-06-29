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

use App\Repositories\Enums\ResponseCodeEnum;
use App\Support\Traits\ResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Tests\TestCase;
use Throwable;

class FailTest extends TestCase
{
    use ResponseTrait;

    public function testFail()
    {
        try {
            // 方式一：Controller 中直接返回失败，这里本质上是通过 JsonResponse 是抛出了一个 HttpResponseException，需要捕获异常后才能拿到真实响应
            // 不需要在前面加 return
            $this->response()->fail();
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();

            $this->assertEquals(500, $response->getStatusCode());

            $expectedJson = json_encode([
                'status' => 'fail',
                'code' => 500,
                'message' => ResponseCodeEnum::fromValue(500)->description,
                // 这里应该是与 ResponseCodeEnum 中 500 状态码对应的描述，如果没有定义则取 Symfony\Component\HttpFoundation\Response
                // 中标准的定义
                'data' => (object) [],
            ]);
            $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
        }
    }

    public function testFailWithMessage()
    {
        try {
            // 方式二：Controller 中返回指定的 Message
            $this->response()->fail('操作失败');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();

            $this->assertEquals(500, $response->getStatusCode());

            $expectedJson = json_encode([
                'status' => 'fail',
                'code' => 500,
                'message' => '操作失败',
                'data' => (object) [],
            ]);
            $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
        }
    }

    public function testFailWithCustomCodeAndMessage()
    {
        try {
            // 方式三：Controller 中返回预先定义的业务错误码和错误描述
            $this->response()->fail('', ResponseCodeEnum::SERVICE_LOGIN_ERROR);
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();

            $this->assertEquals(500, $response->getStatusCode());

            $expectedJson = json_encode([
                'status' => 'fail',
                'code' => ResponseCodeEnum::SERVICE_LOGIN_ERROR, // 预期返回指定的业务错误码
                'message' => ResponseCodeEnum::fromValue(ResponseCodeEnum::SERVICE_LOGIN_ERROR)->description, // 预期根据业务码取相应的错误描述
                'data' => (object) [],
            ]);
            $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
        }
    }

    public function testFailOutController()
    {
        try {
            // 方式四：Controller 中默认引入了 ResponseTrait；在没有引入 ResponseTrait 的地方可以直接使用 abort 来抛出 HttpException 异常然后返回错误信息
            abort(ResponseCodeEnum::SYSTEM_ERROR);
        } catch (HttpException $httpException) {
            try {
                $this->response()->fail(
                    '',
                    $this->isHttpException($httpException) ? $httpException->getStatusCode() : 500,
                    $this->convertExceptionToArray($httpException),
                    $this->isHttpException($httpException) ? $httpException->getHeaders() : [],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                );
            } catch (HttpResponseException $responseException) {
                $response = $responseException->getResponse();

                $this->assertEquals(500, $response->getStatusCode());
                $expectedJson = json_encode([
                    'status' => 'fail',
                    'code' => ResponseCodeEnum::SYSTEM_ERROR,
                    'message' => ResponseCodeEnum::fromValue(ResponseCodeEnum::SYSTEM_ERROR)->description,
                    'data' => $this->convertExceptionToArray($httpException),
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

                $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
            }
        }
    }

    /**
     * Determine if the given exception is an HTTP exception.
     *
     * @param  Throwable  $e
     * @return bool
     */
    protected function isHttpException(Throwable $e)
    {
        return $e instanceof HttpExceptionInterface;
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  Throwable  $e
     * @return array
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        return config('app.debug', false) ? [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ] : [
            'message' => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
        ];
    }
}
