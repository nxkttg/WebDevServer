<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Blog\Admin\PostResource;

class PostController extends BaseController
{
    private BlogCategoryRepository $blogCategoryRepository;

    public function __construct(
        private BlogPostRepository $blogPostRepository,
        BlogCategoryRepository $blogCategoryRepository
    ) {
        // parent::__construct();

        $this->blogCategoryRepository = $blogCategoryRepository;
    }

    public function index(Request $request)
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate(
            (int) $request->input('per_page', 10),
            (int) $request->input('page', 1),
            $request->input('search'),
            $request->input('sort_by', 'id'),
            $request->input('sort_dir', 'desc')
        );

        return PostResource::collection($paginator);
    }

    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();

        $item = (new BlogPost())->create($data);

        if ($item) {
            BlogPostAfterCreateJob::dispatch($item);

            return [
                'success' => true,
                'message' => 'Успішно збережено',
                'data' => $item,
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка збереження',
        ];
    }

    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return [
                'success' => false,
                'message' => "Запис id=[{$id}] не знайдено",
            ];
        }

        $data = $request->all();

        $result = $item->update($data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено',
                'data' => $item,
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка збереження',
        ];
    }

    public function destroy(string $id)
    {
        $result = BlogPost::destroy($id);

        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return [
                'success' => true,
                'message' => 'Успішно видалено',
            ];
        }

        return [
            'success' => false,
            'message' => 'Помилка видалення або запис не знайдено',
        ];
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

        return new PostResource($item);
    }
}
