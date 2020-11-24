<?php


namespace App\Listeners;


use App\Events\RequestHandledEvent;
use App\Repositories\Enums\LogEnum;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RequestHandledListener
{
    public function handle(RequestHandledEvent $event): void
    {
        Log::channel('stack')->debug('ggggg');
        if (config('logging.request.enabled')) {
            $request = $event->request;
            $response = $event->response;
            Log::channel('stack')->debug('ggg2gg');
            $start = $request->server('REQUEST_TIME_FLOAT');
            $end = microtime(true);
            $context = [
                'request' => $request->all(),
                'response' => $response instanceof SymfonyResponse ? json_decode($response->getContent(), true) : (string) $response,
                'start' => $start,
                'end' => $end,
                'duration' => formatDuration($end - $start)
            ];

            logger(LogEnum::LOG_REQUEST_TIME, $context);
        }
    }
}
