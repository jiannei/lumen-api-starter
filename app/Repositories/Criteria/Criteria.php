<?php


namespace App\Repositories\Criteria;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

abstract class Criteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where(function ($query) {
            /** @var Builder $query */

            $this->defaultCondition($query);

            $this->condition($query);
        });

        return $model;
    }

    protected function defaultCondition(Builder $query, $nullValue = 0): void
    {
        // 默认的筛选条件，防止返回数据过多（没有传任何参数时返回空数据）
        if (!$this->request->all()) {
            $query->where($query->getModel()->getKeyName(), $nullValue);
        }
    }

    protected function condition(Builder $query): void
    {
        // 根据 request 中的参数拼接 query
    }
}
