<?php


namespace App\Traits;

/**
 * Trait Helpers
 * @package App\Traits
 * @property \App\Http\Response $response
 */
trait Helpers
{
    public function __get($key)
    {
        $callable = [
            'response',
        ];

        if (in_array($key, $callable) && method_exists($this, $key)) {
            return $this->$key();
        }

        throw new \ErrorException('Undefined property '.get_class($this).'::'.$key);
    }

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
        return app(\App\Http\Response::class);
    }
}
