<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;

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

    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return $paginator;
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
}
