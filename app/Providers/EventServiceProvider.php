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

use App\Events\RequestArrivedEvent;
use App\Events\RequestHandledEvent;
use App\Listeners\RequestArrivedListener;
use App\Listeners\RequestHandledListener;
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
