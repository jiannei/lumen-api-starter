<?php


namespace App\Services;


use App\Contracts\Repositories\PostRepository;

class PostService
{
    private $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handleList()
    {
        return $this->repository->searchPage();
    }
}
