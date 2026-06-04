<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;

class PostController extends BaseController
{
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
