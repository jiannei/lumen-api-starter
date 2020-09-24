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
     * @apiDefine        BaseResponse
     * @apiSuccess (BaseResponseHeader) {int} status   简要信息： success（成功）、fail（失败）
     * @apiSuccess (BaseResponseHeader) {int} code     状态码
     * @apiSuccess (BaseResponseHeader) {string} message   提示信息
     * @apiSuccess (BaseResponseHeader) {string} data      返回数据
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
     * {"status":"success","code":200,"message":"\u64cd\u4f5c\u6210\u529f","data":{}}
     */

    /**
     * @apiDefine Users 用户
     */
}
