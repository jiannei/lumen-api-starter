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

use Illuminate\Container\Container;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use Prettus\Repository\Presenter\FractalPresenter;

abstract class Presenter extends FractalPresenter
{
    /**
     * @param  AbstractPaginator|LengthAwarePaginator|Paginator  $paginator
     *
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
