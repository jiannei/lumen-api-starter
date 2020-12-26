# Lumen Api Starter Designed With ❤️

查找了网上很多的API 相关的开发规范文档，参考了不少大佬们总结的经验，决定尝试使用最新版本的 Lumen（当下最新版本是 Lumen 8.x)来构建一个**基础功能**完备，**规范统一**，能够**快速**应用于实际的 API 项目开发启动模板。同时，也希望通过**合理的**架构设计使其适用于中大型项目。

少许的依赖安装，遵循 Laravel 的思维进行扩展，不额外增加「负担」。

开箱即用，加速 Api 开发。

![StyleCI build status](https://github.styleci.io/repos/267924989/shield)
![Test](https://github.com/Jiannei/lumen-api-starter/workflows/Test/badge.svg?branch=master)

[中文文档](https://github.com/Jiannei/lumen-api-starter/blob/master/README.md)

### 社区讨论传送

- [是时候使用 Lumen 8 + API Resource 开发项目了！](https://learnku.com/articles/45311)
- [一篇 RESTful API 路由设计的最佳实践](https://learnku.com/articles/45526)
- [教你更优雅地写 API 之「规范响应数据」](https://learnku.com/articles/52784)
- [教你更优雅地写 API 之「枚举使用」](https://learnku.com/articles/53015)

Lumen学习交流群：1105120693（QQ）

## 概览

### 现已支持

- 适配 Laravel 7 中新增的 HttpClient 客户端（已升级到 Laravel 8）
- RESTful 规范的路由定义和 HTTP 响应结构
    - 使用 Laravel Api Resource
    - 支持自定义**业务操作应码**以及**业务操作描述**（多语言支持，根据配置中的 APP_LOCAL 配置返回）
- Jwt-auth 方式授权（支持将授权用户缓存到 redis，减少 user 表查询频次）
- 更为便捷地使用枚举/常量：方便地对枚举进行判断校验；请求中包含枚举参数可以自动转换为对应枚举实例
- 支持日志记录到 MongoDB：
    - 异步队列记录日志，包括所有请求日志、SQL 日志、异常日志、业务日志’；
    - 每次请求关联了 UNIQUE_ID，可以通过 UNIQUE_ID 查询出单次请求产生的全部日志
    - 请求日志包含单次请求执行时间记录
    - 支持以每日、每月以及每年按表进行拆分
- 使用 laravel-permission 管理权限：支持根据定义好的 PermissionEnum 生成权限（包含权限校验案例）
- 合理有效地「Repository & Service」架构设计 😏

### 计划支持

其他规划讨论中...

### 目录结构一览

```
├── app
│   ├── Console
│   │   ├── Commands
│   │   └── Kernel.php                            // Schedule 调度
│   ├── Contracts                                       // 定义 interface
│   │   └── Repositories
│   ├── Events
│   │   ├── Event.php
│   │   └── ExampleEvent.php
│   ├── Exceptions                                      // 异常处理
│   │   └── Handler.php
│   ├── Http
│   │   ├── Controllers                           // Controller 任务分发给不同 Service 处理，返回响应给客户端
│   │   ├── Middleware
│   │   └── Resources                             // Api Resource 数据转换
│   ├── Jobs                                            // 异步任务
│   │   ├── ExampleJob.php
│   │   └── Job.php
│   ├── Listeners
│   │   └── ExampleListener.php
│   ├── Policies                                        // 权限校验
│   │   └── PostPolicy.php
│   ├── Providers
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EloquentUserProvider.php              // 定制的 EloquentUserProvider，缓存授权用户信息
│   │   ├── EventServiceProvider.php
│   │   └── RepositoryServiceProvider.php         // repository 模式架构中，将 interface 与 repository 进行对象绑定
│   ├── Repositories                                    // Repository 层：数据仓库层
│   │   ├── Criteria                              // 数据查询条件的组装拼接；（可以将公共的或者复杂的查询条件放在这个地方）
│   │   ├── Eloquent                              // 定义针对某个数据表（或存在关联关系的数据表）的数据维护逻辑；不处理业务（动态数据；实质的 Repository；基于 Eloquent\Model 的封装 ）
│   │   ├── Enums                                 // 枚举集合（静态数据）
│   │   ├── Models                                // Laravel 原始的 Eloquent\Model：定义数据表特性、数据表之间的关联关系等；不处理业务
│   │   ├── Presenters                            // 配合 Transformer 使用
│   │   ├── Transformers                          // 响应前的数据转换，作用与 Api Resource 类似，但是功能更丰富
│   │   └── Validators                            // Eloquent 数据维护前的校验，与表单验证功能类似
│   ├── Services                                        // Service 层：处理实际业务；可以调用 Repository
│   │   ├── PostService.php
│   │   └── UserService.php
│   └── Support                                         // 对框架的扩展，或者实际项目中需要封装一些与业务无关的通用功能集
│       ├── Serializers                                 // league/fratcal 的 ArraySerializer 扩展，支持简单分页数据格式转换
│       ├── Traits                                      // Class 中常用的辅助功能集
│       └── helpers.php                                 // 全局会用到的辅助函数
```

## Repository & Service 模式架构

在添加这部分描述的时候，联想到了 Vue 中的 Vuex，熟悉 Vuex 的同学可以类比一下。

```
Controller => dispatch，校验请求后分发业务处理
Service => action，具体的业务实现
Repository => state、mutation、getter，具体的数据维护
```

### 职责说明

**Controller 岗位职责**：

1. 校验是否有必要处理请求，是否有权限和是否请求参数合法等。无权限或不合法请求直接 response 返回格式统一的数据
2. 将校验后的参数或 Request 传入 Service 中具体的方法，安排 Service 实现具体的功能业务逻辑
3. Controller 中可以通过`__construct()`依赖注入多个 Service。比如 `UserController` 中可能会注入 `UserService`（用户相关的功能业务）和 `EmailService`（邮件相关的功能业务）
4. 使用统一的 `$this->response`调用`sucess`或`fail`方法来返回统一的数据格式
5. （可选）使用 Laravel Api Resource 的同学可能在 Controller 中还会有转换数据的逻辑。比如，`return $this->response->success(new UserCollection($resource));`或`return $this->response->success(new UserResource($user));`
    
**Service 岗位职责**：

1. 实现项目中的具体**功能**业务。所以 Service 中定义的方法名，应该是用来**描述功能或业务**的（动词+业务描述）。比如`handleListPageDisplay`和`handleProfilePageDisplay`，分别对应用户列表展示和用户详情页展示的需求。
2. 处理 Controller 中传入的参数，进行**业务判断**
3.（可选）根据业务需求配置相应的 Criteria 和 Presenter 后（不需要的可以不用配置，或者将通用的配置到 Repository 中）
4. 调用 Repository 处理**数据的逻辑**
5. Service 可以不注入 Repository，或者只注入与处理当前业务**存在数据关联**的 Repository。比如，`EmailService`中或许就只有调用第三方 API 的逻辑，不需要更新维护系统中的数据，就不需要注入 Repository；`OrderService`中实现了订单出库逻辑后，还需要生成相应的财务结算单据，就需要注入 `OrderReposoitory`和`FinancialDocumentRepository`，财务单据中的原单号关联着订单号，存在着数据关联。
6. Service 中不允许调用其他 Service，保持职责单一，如有需要，应该考虑 Controller 中调用

**Repository 岗位职责**：

1. 只负责**数据维护**的逻辑，数据怎么查询、更新、创建、删除，以及**相关联**的数据如何维护。所以 Repository 中定义的方法名，应该是用来描述**数据是以怎样的形式去维护的**。比如 `searchUsersByPage`、`searchUsersById`和`insertUser`。
2. Repository 只绑定**一个** model，**只允许**维护与当前 Repository 绑定的 Model 数据，**最多允许**维护与绑定的 Model 存在关联关系的 Model。比如，订单 OrderRepository 中会涉及到更新订单商品 OrderGoodsRepository 的数据。
3. Repository 中可以配置条件查询（Criteria）、数据校验（Validator）和数据转换显示（Presenter），通常是将通用的配置在 Repository，不通用的独立出相应文件。
4. Repository 本质是在 Laravel ORM Model 中的一层封装，可以完全不用担心使用 Repository 等同于放弃了 ORM 灵活性。原先常用的 ORM 方法**并没有移除**，只是位置从 Controller 中换到了 Repository 而已。
5. Repository 中的 `$this->model` 实际就是绑定的 Model 实例，所以就有了这样的写法`$this->model::all()`,与原先的 ORM 写法`User::all()`是完全等价的。
6. Repository 中不允许引入其他 Repository

**Model 岗位职责**：

经过前面的 Service 和 Repository 「分层」，剥离了可能存在于 Model 中的很多逻辑，比如校验参数，拼接查询，处理业务和转换数据结构等。所以，现如今的 Model 只需要相对简单地数据定义就可以了。比如，对数据表的定义，字段的映射，以及数据表之间关联关系等，提供给 Repository 中使用就够了。

### Repository 模式中涉及到的一些名词理解

完整的执行顺序：`Criteria -> Validator -> Presenter`

**Constants**:

这个是 lumen-api-starter 新增的部分，用来定义应用系统中常量的数据。

**Criteria**：[l5-repository criteria](https://github.com/andersao/l5-repository#example-the-criteria) 

作用类似 Eloquent Model 中的 Scope 查询，把常用的查询提取出来，但是比 Scope 更强大。
可以省去 Model 中大量的根据请求参数判断并拼接查询条件的代码，与此同时，能够做到将多种数据之间存在的**通用**筛选条件剥离出来。
比如 `make:repository`创建生成的 Repository 中默认包含以下代码，就是给 Repository 默认配置了一个 RequestCriteria，就可以直接使用下面的方式来过滤数据，是不是非常方便？！

```php
public function boot()
{
    $this->pushCriteria(app(RequestCriteria::class));
}
```

```php
http://prettus.local/users?search=age:17;email:john@gmail.com&searchJoin=and

Filtering fields

http://prettus.local/users?filter=id;name

[
    {
        "id": 1,
        "name": "John Doe"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 3,
        "name": "Laravel"
    }
]
Sorting the results

http://prettus.local/users?filter=id;name&orderBy=id&sortedBy=desc

[
    {
        "id": 3,
        "name": "Laravel"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 1,
        "name": "John Doe"
    }
]
```

**Presenter**：[L5-repository presenters](https://github.com/andersao/l5-repository#presenters)

可选，使用 Api Resource 的同学可以略过。需要安装 `composer require league/fractal`，Dingo Api 中的 transformer 也是使用了这个扩展包。

作用类似 Laravel 的 Api Resource，或者可以说 Api Resource 是 Transformer 的轻量实现。

L5-repository 认为你将数据表结构的**数据转换**后是为了用来**展示**的，所以它将数据转换相关的逻辑独立出来，称为 Presenter。本质是整合了 fractal 中的 transformer 功能。 

Transformer 的优秀之处这里暂不做讨论，因为这里的主角是 Presenter。[传送门](https://fractal.thephpleague.com/)

先对比一下几种数据转换方式：

- Dingo Api 中 transformer 的使用方式

在 Controller 中调用 Response 中的 item 返回数据时传入 transformer 来转换数据

```php
return $this->item($user, new UserTransformer, ['key' => 'user']);
```


- Laravel 中 Api Resource 的使用方式

在 Controller 中调用 Resource 或者 ResourceCollection 转换数据

```php
 //return $this->response->success(new UserResource($user));// 使用 lumen-api-starter 统一 code\status\message\data
return new UserResource($user);// 未统一响应结构
```

- L5-repository 中 transformer 的使用方式（为了避免混淆，这里讲的是独立出文件的形式，当然也有可以直接在 model 或 repository 中定义的方式，更详细的使用请参考 l5-repository 的说明）

需要先定义 transformer，然后在 Presenter 中「注册」，最后在调用 Repository 时使用。

举例：

定义 UserTransformer

```
// app/Repositories/Transformers/UserTransformer.php
<?php


namespace App\Repositories\Transformers;

use App\Repositories\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'nickname' => $user->name,
            'email' => $user->email,
        ];
    }
}

```

「注册」到 UserPresenter

```php
// app/Repositories/Presenters/UserPresenter.php
<?php


namespace App\Repositories\Presenters;


use App\Repositories\Transformers\UserTransformer;
use League\Fractal\TransformerAbstract;use Prettus\Repository\Presenter\FractalPresenter;

class UserPresenter extends FractalPresenter
{
    /**
     * Prepare data to present
     *
     * @return TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserTransformer();
    }
}

```

在调用 repository 的时候使用

```php
// app/Services/UserService.php
public function listPage(Request $request)
{
    $this->repository->pushCriteria(new UserCriteria($request));
    $this->repository->setPresenter(UserPresenter::class);

    return $this->repository->searchUsersByPage();
}
```

看得出 Dingo Api 和 Api Resource 都是在最后响应数据的环节来转换数据，而 Repository 模式中认为**但凡是与数据有关的处理逻辑都应该被「装进 Repository中」**，应用系统中的其他部分不需要关心数据如何去查询（Criteria），如何去校验（Validator），以及如何去转换后提供显示（Presenter）。其他部分做好相应的职责就行，但凡与数据打交道的地方都交给 Repository。


### 规范

* 命名规范：

- controller：
    - 类名：名词，复数形式，描述是对整个资源集合进行操作；当没有集合概念的时候。换句话说，当资源只有一个的情况下，使用单数资源名称也是可以的——即一个单一的资源。例如，如果有一个单一的总体配置资源，你可以使用一个单数名称来表示
    - 方法名：动词+名词，体现资源操作。如，store\destroy

- service:
    - 类名：名词，单数。比如`UserService`、`EmailService`和`OrderService`
    - 方法名：`动词+名词`，描述能够实现的业务需求。比如：`handleRegistration`表示实现用户注册功能。

- repository
    - 类名：名词，单数。`make:repository`命令可以直接生成。
    - 方法名：动词+名词，描述数据的维护（CRUD）。   比如：`searchUsersByPage`
    - 可能会出现的动词：createXXX（add）;searchXXX；queryXXX、findXXX、fetch（get）；updateXXX；deleteXXX（destroy）；组合形式：with\Join...，如 searchOrdersLeftJoinGodds
    - 通常情况 Database、Cache、Redis、Carbon 操作只能出现在 repository

* 使用规范：待补充

## Packages

- [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) （可选，需要使用 Laravel 7 新增的 HttpClient 时安装）
- [jenssegers/mongodb](https://github.com/jenssegers/laravel-mongodb) （可选，需要使用记录日志到 MongoDB 时安装）
- [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth) （默认支持 JWT 授权）
- [illuminate/redis](https://github.com/illuminate/redis) （默认使用 Redis 来缓存）
- [spatie/laravel-permission](https://github.com/spatie/laravel-permission) （使用这个包来管理分配用户权限）
- [prettus/l5-repository](https://github.com/andersao/l5-repository) （默认使用 Repository 模式）
- [league/fractal](https://github.com/thephpleague/fractal) (可选，需要用到 transformer 时安装)

## 其他

依照惯例，如对您的日常工作有所帮助或启发，欢迎三连 `star + fork + follow`。

如果有任何批评建议，通过邮箱（longjian.huang@foxmail.com）的方式（如果我每天坚持看邮件的话）可以联系到我。

总之，欢迎各路英雄好汉。

## 参考

* [RESTful API 最佳实践](https://learnku.com/articles/13797/restful-api-best-practice)
* [RESTful 服务最佳实践](https://www.cnblogs.com/jaxu/p/7908111.html)
* [DingoApi](https://github.com/dingo/api)
* [overtrue/laravel-query-logger](https://github.com/overtrue/laravel-query-logger)
* [BenSampo/laravel-enum](https://github.com/BenSampo/laravel-enum)
* [spatie/laravel-enum](https://github.com/spatie/laravel-enum)

## License

The Lumen Api Starter is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
