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
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria as BaseRequestCriteria;

class RequestCriteria extends BaseRequestCriteria
{
    /**
     * Apply criteria in query repository.
     *
     * @param  Builder|Model  $model
     * @param  RepositoryInterface  $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $cursor = $this->request->get(config('repository.criteria.params.cursor', 'cursor'), null);
        if ($cursor) {
            $keyType = $model->getKeyType();
            $key = $model->getKeyName();
            $cursor = in_array($keyType, ['int', 'integer']) ? (int) $cursor : $cursor;

            $model = $model->where($key, '>', $cursor);
        }

        return parent::apply($model, $repository);
    }
}
