<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;
use App\Http\Controllers\Api\Blog\Admin\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'blog'], function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});

// Адмінка
$groupData = [
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    // BlogCategory
    $methods = ['index', 'store', 'update'];

    Route::apiResource('categories', CategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');

    // BlogPost
    Route::apiResource('posts', PostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});
