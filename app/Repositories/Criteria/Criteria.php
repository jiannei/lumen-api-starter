<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

abstract class Criteria implements CriteriaInterface
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $this->before($model);

        $model = $model->where(function ($query) {
            /** @var Builder $query */
            $this->condition($query);
        });

        return $this->after($model);
    }

    /**
     * Splice the query according to the parameters in the request.
     *
     * @param  Builder  $query
     */
    protected function condition(Builder $query): void
    {
    }

    protected function before($model)
    {
        return $model;
    }

    protected function after($model)
    {
        return $model;
    }
}
