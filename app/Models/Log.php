<?php

namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;

class Log extends Model
{
    protected $connection = 'mongodb';
    protected $guarded = [];

    protected $dates = [
        'datetime'
    ];
}
