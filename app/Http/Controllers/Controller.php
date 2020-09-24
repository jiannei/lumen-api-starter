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

use App\Support\Traits\ResponseTrait;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ResponseTrait;

    /**
     * @apiDefine        BaseResponse BaseResponse
     * @apiSuccess (BaseResponse) {int} status   简要信息： success（成功）、fail（失败）
     * @apiSuccess (BaseResponse) {int} code     状态码
     * @apiSuccess (BaseResponse) {string} message   提示信息
     * @apiSuccess (BaseResponse) {Array} data      返回数据
     */

    /**
     * @apiDefine        FailResponse
     * @apiErrorExample  Error-Response:
     * {
     *    "status": "fail",
     *    "code": 500,
     *    "message": "Service error",
     *    "data": {}
     * }
     */

    /**
     * @apiDefine          SuccessResponse
     * @apiSuccessExample  Success-Response:
     * {
     *    "status":"success",
     *    "code":200,"message":"\u64cd\u4f5c\u6210\u529f",
     *    "data":{}
     * }
     */

    /**
     * @apiDefine          AuthorizationHeader
     * @apiHeader          {String} authorization Authorization value.
     * @apiHeaderExample {json} Header-Example:
     * {
     *       "authorization": "bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6OTg5OFwvYXBpXC92MVwvYXV0aG9yaXphdGlvbiIsImlhdCI6MTYwMDkxMjM2MiwiZXhwIjoxNjAwOTE2MDU1LCJuYmYiOjE2MDA5MTI0NTUsImp0aSI6ImJFcHY3WFdFUjR0Q3lMWm0iLCJzdWIiOjEsInBydiI6IjcxMTA3ZWU2YWM4ZWRlYmEwYzgxZDUwNTJlNzI3NjU1ZDE3YzZiMTEifQ.L9uIlqX7HjKpl27EPzuV7paZ_bDSNh4dqfCD1CHrVew"
     * }
     */

    /**
     * @api               {get} description 接口基本返回字段
     * @apiName           get_description
     * @apiGroup          Description
     *
     * @apiUse BaseResponse
     */

    /**
     * @apiDefine Description 注意事项说明
     */

    /**
     * @apiDefine Users 用户
     */

    /**
     * @apiDefine Authorization 授权
     */
}
