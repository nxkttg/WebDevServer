<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;
use App\Repositories\BlogPostRepository;

class PostController extends BaseController
{
    public function __construct(private BlogPostRepository $blogPostRepository)
    {
        //
    }

    public function index()
    {
        $items = BlogPost::all();

        return $items;
    }

    public function store()
    {
        //
    }

    public function show(string $id)
    {
        $item = $this->blogPostRepository->getForView($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Пост id=[{$id}] не знайдено",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $item,
        ]);
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
