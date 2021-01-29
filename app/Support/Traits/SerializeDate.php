<?php


namespace App\Support\Traits;


use Carbon\Carbon;
use DateTimeInterface;

trait SerializeDate
{
    /**
     * 为数组 / JSON 序列化准备日期。(Laravel 7).
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: Carbon::DEFAULT_TO_STRING_FORMAT);
    }
}
