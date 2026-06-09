<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Repositories\BlogPostRepository;

class PostController extends BaseController
{
    public function __construct(private BlogPostRepository $blogPostRepository)
    {
        // parent::__construct();
    }

    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return $paginator;
    }

    public function store()
    {
        //
    }

    public function update(string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
