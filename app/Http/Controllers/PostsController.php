<?php

namespace App\Http\Controllers;

use App\Repositories\Models\Post;
use App\Services\PostService;

class PostsController extends Controller
{
    private $service;

    public function __construct(PostService $service)
    {
        $this->middleware('auth:api', ['except' => []]);

        $this->service = $service;
    }


    public function index()
    {
        $this->authorize('viewAny', Post::class);

        $posts = $this->service->handleList();

        return $this->response->success($posts);
    }
}
