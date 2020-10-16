<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repositories\Transformers;

use App\Repositories\Enums\PermissionEnum;
use App\Repositories\Models\Post;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'author',
    ];

    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $this->checkColumnPermission() ? $post->body : Str::limit($post->body, 120), // 没有文章详情查看权限时，返回截取的部分内容
            'published' => $post->published,
        ];
    }

    protected function checkColumnPermission()
    {
        return auth('api')->user()->can(PermissionEnum::ROUTE_POSTS_VIEW()->name);
    }

    public function includeAuthor(Post $post)
    {
        $author = $post->author;

        return $this->item($author, new UserTransformer());
    }
}
