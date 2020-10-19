<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use App\Providers\EnumServiceProvider;
use App\Providers\LoggerServiceProvider;
use App\Providers\RepositoryServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Prettus\Repository\Providers\LumenRepositoryServiceProvider;
use Tymon\JWTAuth\Providers\LumenServiceProvider;

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->register(Jenssegers\Mongodb\MongodbServiceProvider::class);

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/

$app->configure('app');
$app->configure('auth');
$app->configure('broadcasting');
$app->configure('cache');
$app->configure('database');
$app->configure('filesystems');
$app->configure('logging');
$app->configure('queue');
$app->configure('services');
$app->configure('views');
$app->configure('repository');
$app->configure('enum');
$app->configure('permission');

$app->alias('cache', \Illuminate\Cache\CacheManager::class);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->middleware([
    App\Http\Middleware\RequestTimeMiddleware::class,
    App\Http\Middleware\AcceptHeader::class,
    App\Http\Middleware\EtagMiddleware::class,
]);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'enum' => App\Http\Middleware\TransformEnums::class,
    'permission' => Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role' => Spatie\Permission\Middlewares\RoleMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

/*
* Application Service Providers...
*/
// $app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
 * Package Service Providers...
 */
$app->register(LumenServiceProvider::class);
$app->register(LumenRepositoryServiceProvider::class);
$app->register(Spatie\Permission\PermissionServiceProvider::class);

/*
 * Custom Service Providers.
 */
$app->register(RepositoryServiceProvider::class);
$app->register(LoggerServiceProvider::class);
$app->register(RedisServiceProvider::class);
$app->register(EnumServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
