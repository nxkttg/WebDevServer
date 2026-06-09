<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogPostUpdateRequest;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
// use Carbon\Carbon;
// use Illuminate\Support\Str;

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

    public function store()
    {
        //
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

        if (empty($data['content_html'])) {
            $data['content_html'] = $data['content_raw'];
        }

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
        //
    }
}
