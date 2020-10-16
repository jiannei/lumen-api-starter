<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Policies;

use App\Repositories\Enums\PermissionEnum;
use App\Repositories\Models\Post;
use App\Repositories\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any posts.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->can(PermissionEnum::ROUTE_POSTS_VIEW_ANY()->name)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param  User|null  $user
     * @param  Post  $post
     * @return mixed
     */
    public function view(?User $user, Post $post)
    {
        if ($post->published) {
            return true;
        }

        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        if ($user->can(PermissionEnum::ROUTE_POSTS_VIEW()->name)) {
            return true;
        }

        // authors can view their own unpublished posts
        return $user->isOwnerOf($post);
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can(PermissionEnum::ROUTE_POSTS_CREATE()->name)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  User  $user
     * @param  Post  $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        if ($user->isOwnerOf($post) || $user->can('edit all posts')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  User  $user
     * @param  Post  $post
     * @return mixed
     */
    public function delete(User $user, Post $post)
    {
        if ($user->isOwnerOf($post) || $user->can(PermissionEnum::ROUTE_POSTS_DELETE()->name)) {
            return true;
        }

        return false;
    }
}
