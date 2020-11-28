<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Providers;

use App\Support\Logger\Events\RequestArrivedEvent;
use App\Support\Logger\Events\RequestHandledEvent;
use App\Support\Logger\Listeners\RequestArrivedListener;
use App\Support\Logger\Listeners\RequestHandledListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ExampleEvent::class => [
            \App\Listeners\ExampleListener::class,
        ],
        RequestArrivedEvent::class => [
            RequestArrivedListener::class,
        ],
        RequestHandledEvent::class => [
            RequestHandledListener::class,
        ],
    ];
}
