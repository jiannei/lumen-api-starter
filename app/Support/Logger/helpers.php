<?php

use App\Support\Logger\Jobs\LogJob;
use Laravel\Lumen\Bus\PendingDispatch;

if (!function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return PendingDispatch|mixed
     */
    function logger(string $message, array $context = [])
    {
        return dispatch(new LogJob($message, $context, request()->server()));
    }
}

if (!function_exists('formatDuration')) {
    /**
     * Format duration.
     *
     * @param  float  $seconds
     *
     * @return string
     */
    function formatDuration(float $seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000).'μs';
        }

        if ($seconds < 1) {
            return round($seconds * 1000, 2).'ms';
        }

        return round($seconds, 2).'s';
    }
}
