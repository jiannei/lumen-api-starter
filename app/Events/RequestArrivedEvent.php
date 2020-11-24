<?php


namespace App\Events;


use Illuminate\Http\Request;

class RequestArrivedEvent extends Event
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
