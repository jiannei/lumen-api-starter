<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories\Eloquent;

use App\Repositories\Presenters\Presenter;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository as BaseRepositoryEloquent;

abstract class BaseRepository extends BaseRepositoryEloquent
{
    /**
     * @var Presenter
     */
    protected $presenter;

    /**
     * Retrieve all data of repository, simple paginated.
     *
     * @param  null|int  $limit
     * @param  array  $columns
     * @return mixed
     */
    public function cursorPaginate($limit = null, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : (int) $limit;

        $results = $this->model->select($columns)->limit($limit)->get();

        if ($this->model instanceof Builder) {
            $primaryKey = $this->model->getModel()->getKeyName();
        } else {
            $primaryKey = $this->model->getKeyName();
        }

        $count = $results->count();
        $next = $count === $limit ? optional($results->last())->{$primaryKey} : null;

        $prev = request('prev');
        $this->presenter->makeCursor((int) request('cursor'), $prev ? (int) $prev : '', $next, $count);

        $this->resetModel();

        return $this->parserResult($results);
    }
}
