<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Blog\PostController as BlogPostController;
use App\Http\Controllers\Api\Blog\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController as AdminPostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'blog'], function () {
    Route::apiResource('posts', BlogPostController::class)->names('blog.posts');
});

$groupData = [
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    // BlogCategory
    $methods = ['index', 'store', 'update'];

    Route::apiResource('categories', AdminCategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');

    // BlogPost
    Route::apiResource('posts', AdminPostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});
