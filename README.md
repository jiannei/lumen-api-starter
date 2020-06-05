# Lumen Api Starter Designed With ❤️

翻阅了网上很多的API 开发规范文档，参考了不少大佬们总结的经验，决定尝试使用最新版本的 Lumen（当下最新版本是 Lumen 7.x)来构建一个**基础功能**完备，**规范统一**，能够**快速**应用于实际的 API 项目开发启动模板。同时，也希望通过**合理的**应用架构设计为中大型应用保驾护航。

少许的依赖安装，遵循 Laravel 的思维进行扩展，不额外增加“负担”。

开箱即用，加速 Api 开发。

[中文文档](https://github.com/Jiannei/lumen-api-starter/blob/master/README.md)

![StyleCI build status](https://github.styleci.io/repos/267924989/shield) 

[TOC]

## 概览

### 现已支持

- 适配 Laravel 7 中新增的 HttpClient 客户端
- RESTflu 规范的路由定义和 HTTP 响应结构
    - 使用 Laravel Api Resource
    - 支持自定义**业务操作应码**以及**业务操作描述**（多语言支持，根据配置中的 APP_LOCAL 配置返回）
- Jwt-auth 方式授权
- 支持日志记录到 MongoDB
- 合理有效地『Repository & Service』架构设计（😏）

### 计划支持

规划讨论中。（详细文档说明？Laravel 7 的对应实现版本？生成 API 文档？支持单元测试？支持国际化？）

## RESTful 方式的路由设计简单准则

待补充。

## 规范的响应结构

[RESTful 服务最佳实践](https://www.cnblogs.com/jaxu/p/7908111.html)

> - code——包含一个整数类型的HTTP响应状态码。
> - status——包含文本："success"，"fail"或"error"。HTTP状态响应码在500-599之间为"fail"，在400-499之间为"error"，其它均为"success"（例如：响应状态码为1XX、2XX和3XX）。
> - message——当状态值为"fail"和"error"时有效，用于显示错误信息。参照国际化（il8n）标准，它可以包含信息号或者编码，可以只包含其中一个，或者同时包含并用分隔符隔开。
> - data——包含响应的body。当状态值为"fail"或"error"时，data仅包含错误原因或异常名称。

整体响应结构设计参考如上，相对严格地遵守了 RESTful 设计准则，返回合理的 HTTP 状态码。

考虑到业务通常需要返回不同的“业务描述处理结果”，在所有响应结构中都支持传入符合业务场景的`message`。

### 说明

- data: 
    - 查询单条数据时直接返回对象结构，减少数据层级；
    - 查询全部数据时返回数组结构；
    - 查询分页数据时返回对象结构
    - 创建或更新成功，返回修改后的数据；（也可以不返回数据直接返回空对象）
    - 删除成功时返回空对象
- status:
    - error, 客服端出错，HTTP 状态响应码在400-599之间。如，传入错误参数，访问不存在的数据资源等
    - fail，服务端出错，HTTP 状态响应码在500-599之间。如，代码语法错误，空对象调用函数，连接数据库失败，undefined index等
    - success, HTTP 响应状态码为1XX、2XX和3XX，用来表示业务处理成功。
- message: 描述执行的请求操作处理的结果；也可以支持国际化，根据实际业务需求来切换。
- code: HTTP 响应状态码；可以根据实际业务需求，调整成业务操作码

### 使用

在需要用到的地方使用 `\App\Traits\Helpers`对`\App\Http\Response`中封装的响应方法进行调用，通常是在 Controller 层中根据业务处理的结果进行响应，所以 `\App\Http\Controllers`基类中已经引入了 `Helpers`trait，可以直接在 Controller 中进行如下调用：

```php
// 操作成功情况
$this->response->success($data,$message);
$this->response->success(new  UserCollection($resource),  '成功');// 返回 API Resouce Collection
$this->response->success(new  UserResource($user),  '成功');// 返回 API Resouce
$user  =  ["name"=>"nickname","email"=>"longjian.huang@foxmail.com"];
$this->response->success($user,  '成功');// 返回普通数组

$this->response->created($data,$message);
$this->response->accepted($message);
$this->response->noContent($message);

// 操作失败或异常情况
$this->response->fail($message);
$this->response->errorNotFound();
$this->response->errorBadRequest();
$this->response->errorForbidden();
$this->response->errorInternal();
$this->response->errorUnauthorized();
$this->response->errorMethodNotAllowed();
```

### 操作成功时的响应结构

- 返回单条数据

```json
{
    "data": {
        "nickname": "Jiannei",
        "email": "longjian.huang@foxmail.com"
    },
    "status": "success",
    "code": 200,
    "message": "成功"
}
```

- 返回全部数据

```json
{
    "data": [
        {
            "nickname": "Jiannei",
            "email": "longjian.huang@foxmail.com"
        },
        {
            "nickname": "Qian",
            "email": "1234567891@foxmail.com"
        },
        {
            "nickname": "Turbo",
            "email": "123456789@foxmail.com"
        }
        // ...
    ],
    "status": "success",
    "code": 200,
    "message": "成功"
}
```

- 返回分页数据

```json
{
    "status": "success",
    "code": 200,
    "message": "操作成功",
    "data": {
        "data": [
            {
                "nickname": "Jiannei",
                "email": "longjian.huang@foxmail.com"
            },
            {
                "nickname": "Turbo",
                "email": "123456789@qq.com"
            },
            {
                "nickname": "Qian",
                "email": "987654321@qq.com"
            }
        ],
        "meta": {
            "pagination": {
                "total": 13,
                "count": 3,
                "per_page": 3,
                "current_page": 1,
                "total_pages": 5,
                "links": {
                    "previous": null,
                    "next": "http://lumen-api.test/users?page=2"
                }
            }
        }
    }
}
```

### 操作失败时的响应结构

```json
{
    "status": "fail",
    "code": 500,
    "message": "Service error",
    "data": {}
}
```

### 异常捕获时的响应结构

整体格式与业务操作成功和业务操作失败时的一致，相比失败时，data 部分会增加额外的异常信息展示，方便项目开发阶段进行快速地问题定位。

- 自定义实现了 `ValidationException` 的响应结构：

```json
{
    "status": "error",
    "code": 422,
    "message": "Validation error",
    "data": {
        "email": [
            "The email has already been taken."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

- `NotFoundException` 异常捕获的响应结构

关闭 debug 时：

```json
{
    "status": "error",
    "code": 404,
    "message": "Service error",
    "data": {
        "message": "No query results for model [App\\Models\\User] 19"
    }
}
```

开启 debug 时：

```json
{
    "status": "error",
    "code": 404,
    "message": "Service error",
    "data": {
        "message": "No query results for model [App\\Models\\User] 19",
        "exception": "Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException",
        "file": "/var/www/lumen-api-starter/vendor/laravel/lumen-framework/src/Exceptions/Handler.php",
        "line": 107,
        "trace": [
            {
                "file": "/var/www/lumen-api-starter/app/Exceptions/Handler.php",
                "line": 55,
                "function": "render",
                "class": "Laravel\\Lumen\\Exceptions\\Handler",
                "type": "->"
            },
            {
                "file": "/var/www/lumen-api-starter/vendor/laravel/lumen-framework/src/Routing/Pipeline.php",
                "line": 72,
                "function": "render",
                "class": "App\\Exceptions\\Handler",
                "type": "->"
            },
            {
                "file": "/var/www/lumen-api-starter/vendor/laravel/lumen-framework/src/Routing/Pipeline.php",
                "line": 50,
                "function": "handleException",
                "class": "Laravel\\Lumen\\Routing\\Pipeline",
                "type": "->"
            }
            // ...
        ]
    }
}
```

- 其他类型异常捕获时的响应结构

```json
{
    "status": "fail",
    "code": 500,
    "message": "syntax error, unexpected '$user' (T_VARIABLE)",
    "data": {
        "message": "syntax error, unexpected '$user' (T_VARIABLE)",
        "exception": "ParseError",
        "file": "/var/www/lumen-api-starter/app/Http/Controllers/UsersController.php",
        "line": 34,
        "trace": [
            {
                "file": "/var/www/lumen-api-starter/vendor/composer/ClassLoader.php",
                "line": 322,
                "function": "Composer\\Autoload\\includeFile"
            },
            {
                "function": "loadClass",
                "class": "Composer\\Autoload\\ClassLoader",
                "type": "->"
            },
            {
                "function": "spl_autoload_call"
            }
           // ...
        ]
    }
}
```


### 根据实际业务场景定制的响应返回

- 操作成功

拿「登录成功返回用户信息」举个栗子：

**第一种**：指定 message

使用

```php
return $this->response->success($user,'注册成功');
```

返回

```json
{
    "status": "success",
    "code": 200,
    "message": "注册成功",
    "data": {
        "nickname": "Jiannei",
        "email": "longjian.huang@foxmail.com"
  }
}
```

**第二种**：message 参数为空，使用 ResponseConstant 中自定义的业务操作码，读取 `resources/lang/zh-CN/response.php`中的业务描述信息，也就说明支持多语言了

```php
return $this->response->success($user,'',ResponseConstant::SERVICE_LOGIN_SUCCESS);
```

```json
{
    "status": "success",
    "code": 200101,
    "message": "注册成功",
    "data": {
        "nickname": "Jiannei",
        "email": "longjian.huang@foxmail.com"
    }
}
```

**注意**：两种的返回数据有中的 code 不同，第二种返回的是自定义的操作码，具体定义规则可以查看 `app/Constants/ResponseConstant.php`

- 操作失败

直接抛出 `HttpException`，使用自定义的错误码就可以了，如此简单。

使用

```php
 throw new \Symfony\Component\HttpKernel\Exception\HttpException(ResponseConstant::SERVICE_LOGIN_ERROR);
```

返回

```json
{
    "status": "fail",
    "code": 500102,
    "message": "登录失败",
    "data": {
        "message": ""
    }
}
```

### 特别说明

使用 Postman 等 Api 测试工具的使用需要添加 `X-Requested-With：XMLHttpRequest`或者`Accept:application/json`header 信息来表明是 Api 请求，否则在异常捕获到后返回的可能不是预期的 JSON 格式响应。

## 丰富的日志模式支持

- 支持记录日志（包括业务错误记录的日志和捕获的异常信息等）到 MongoDB，方便线上问题的排查
- 记录到 MongoDB 的日志，支持以每日、每月以及每年按表进行拆分
- 支持记录 sql 语句

## Repository & Service 模式架构

### 职责说明

待补充。

### 规范

* 命名规范：待补充
* 使用规范：待补充

## Packages

- [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)
- [jenssegers/mongodb](https://github.com/jenssegers/laravel-mongodb)
- [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- [prettus/l5-repository](https://github.com/andersao/l5-repository)
- [overtrue/laravel-query-logger](https://github.com/overtrue/laravel-query-logger)

## 其他

依照惯例，如对您的日常工作有所帮助或启发，欢迎三连 `star + fork + follow`。

如果有任何批评建议，通过邮箱（longjian.huang@foxmial.com）的方式（如果我每天坚持看邮件的话）可以联系到我。

总之，欢迎各路英雄好汉。

## 参考

* [RESTful API 最佳实践](https://learnku.com/articles/13797/restful-api-best-practice)
* [RESTful 服务最佳实践](https://www.cnblogs.com/jaxu/p/7908111.html)
* [DingoApi](https://github.com/dingo/api)


## License

The Lumen Api Starter is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
