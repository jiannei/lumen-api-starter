<?php


namespace App\Jobs;


use Illuminate\Support\Facades\Log;

class LogJob extends Job
{
    private $context;
    private $message;

    public function __construct(string $message, array $context)
    {
        $this->message = $message;
        $this->context = $context;
    }

    public function handle()
    {
        Log::debug($this->message, $this->context);
    }
}
