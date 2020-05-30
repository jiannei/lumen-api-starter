<?php


namespace App\Traits;


use App\Http\Response;

trait Helpers
{
    /**
     * Format duration.
     *
     * @param  float  $seconds
     *
     * @return string
     */
    protected function formatDuration(float $seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000).'μs';
        } elseif ($seconds < 1) {
            return round($seconds * 1000, 2).'ms';
        }

        return round($seconds, 2).'s';
    }

    protected function response()
    {
        return app(Response::class);
    }
}
