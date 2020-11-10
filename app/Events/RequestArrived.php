<?php


namespace App\Events;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestArrived extends Event
{
    public function __construct(Request $request)
    {
        $uniqueId = $request->headers->get('X-Unique-Id') ?: Str::uuid()->toString();

        $request->server->set('UNIQUE_ID', $uniqueId);
    }
}
