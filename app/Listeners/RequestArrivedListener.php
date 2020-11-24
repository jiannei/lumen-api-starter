<?php


namespace App\Listeners;


use App\Events\RequestArrivedEvent;
use Illuminate\Support\Str;

class RequestArrivedListener
{
    public function handle(RequestArrivedEvent $event): void
    {
        $uniqueId = $event->request->headers->get('X-Unique-Id') ?: Str::uuid()->toString();

        $event->request->server->set('UNIQUE_ID', $uniqueId);
    }
}
