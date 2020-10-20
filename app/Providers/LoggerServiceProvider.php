<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Providers;

use App\Jobs\LogJob;
use App\Repositories\Enums\LogEnum;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!$this->app['config']->get('app.debug') && !$this->app['config']->get('logging.query.enabled', false)) {
            return;
        }

        DB::listen(function (QueryExecuted $query) {
            if ($query->time < $this->app['config']->get('logging.query.slower_than', 0)) {
                return;
            }

            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

            $bindings = $query->connection->prepareBindings($query->bindings);
            $pdo = $query->connection->getPdo();
            $realSql = $sqlWithPlaceholders;
            $duration = formatDuration($query->time / 1000);

            if (count($bindings) > 0) {
                $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
            }

            $context = [
                'database' => $query->connection->getDatabaseName(),
                'duration' => $duration,
                'sql' => $realSql,
            ];

            dispatch(new LogJob(LogEnum::LOG_SQL, $context, request()->server()));
        });
    }
}
