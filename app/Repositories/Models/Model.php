<?php


namespace App\Repositories\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    /**
     * 为数组 / JSON 序列化准备日期。(Laravel 7)
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: Carbon::DEFAULT_TO_STRING_FORMAT);
    }
}
