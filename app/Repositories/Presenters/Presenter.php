<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories\Presenters;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use Prettus\Repository\Presenter\FractalPresenter;

abstract class Presenter extends FractalPresenter
{
    protected $cursor = null;

    public function makeCursor($current, $previous, $next, $count)
    {
        $this->cursor = new Cursor(...func_get_args());
    }

    /**
     * Prepare data to present.
     *
     * @param $data
     * @return mixed
     *
     * @throws Exception
     */
    public function present($data)
    {
        if (! class_exists('League\Fractal\Manager')) {
            throw new Exception(trans('repository::packages.league_fractal_required'));
        }

        if ($data instanceof EloquentCollection) {
            $this->resource = $this->transformCollection($data);
        } elseif ($data instanceof AbstractPaginator) {
            $this->resource = $this->transformPaginator($data);
        } else {
            $this->resource = $this->transformItem($data);
        }

        return $this->fractal->createData($this->resource)->toArray();
    }

    /**
     * @param $data
     * @return \League\Fractal\Resource\Collection
     */
    protected function transformCollection($data)
    {
        $resource = new Collection($data, $this->getTransformer(), $this->resourceKeyCollection);
        if ($this->cursor) {
            $resource->setCursor($this->cursor);
        }

        return $resource;
    }

    /**
     * @param  AbstractPaginator|LengthAwarePaginator|Paginator  $paginator
     * @return \League\Fractal\Resource\Collection
     */
    protected function transformPaginator($paginator)
    {
        $collection = $paginator->getCollection();
        $resource = new Collection($collection, $this->getTransformer(), $this->resourceKeyCollection);

        if ($paginator instanceof Paginator) {
            $items = $paginator->items();
            $total = 0;
            $perPage = $paginator->perPage();
            $currentPage = $paginator->currentPage();
            $options = array_merge(['hasMore' => $paginator->hasMorePages()], $paginator->getOptions());

            $paginator = Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
                'items', 'total', 'perPage', 'currentPage', 'options'
            ));
        }

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $resource;
    }
}
