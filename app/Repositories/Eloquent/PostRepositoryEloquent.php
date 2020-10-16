<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\PostRepository;
use App\Repositories\Enums\PermissionEnum;
use App\Repositories\Models\Post;
use App\Repositories\Presenters\PostPresenter;
use App\Repositories\Validators\PostValidator;
use Illuminate\Support\Arr;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Permission\Exceptions\UnauthorizedException;

/**
 * Class PostRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class PostRepositoryEloquent extends BaseRepository implements PostRepository
{
    public function model()
    {
        return Post::class;
    }

    public function presenter()
    {
        return PostPresenter::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function checkPermission()
    {
        $authUser = auth('api')->user();

        // 登录用户是否有 Posts 数据表操作权限
        $permission = PermissionEnum::DATA_POSTS()->name;
        if (!$authUser->can($permission)) {
            throw UnauthorizedException::forPermissions(Arr::wrap($permission));
        }
    }

    public function searchPage()
    {
        $posts = $this->model->published()->paginate(10);

        return $this->parserResult($posts);
    }
}
