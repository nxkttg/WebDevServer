<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Resources\Api\Blog\Admin\CategoryResource;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        // parent::__construct();
    }

    public function index(Request $request)
    {
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(
            (int) $request->input('per_page', 10),
            (int) $request->input('page', 1),
            $request->input('search')
        );

        return CategoryResource::collection($paginator);
    }

    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        $item = BlogCategory::create($data);

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено',
                'data' => new CategoryResource($item),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Помилка збереження',
        ], 500);
    }

    public function show(string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Категорію id=[{$id}] не знайдено",
            ], 404);
        }

        return new CategoryResource($item);
    }

    public function update(BlogCategoryUpdateRequest $request, string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Категорію id=[{$id}] не знайдено",
            ], 404);
        }

        $data = $request->input();

        $result = $item->update($data);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено',
                'data' => new CategoryResource($item->fresh(['parentCategory'])),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Помилка збереження',
        ], 500);
    }

    public function destroy(string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'message' => "Категорію id=[{$id}] не знайдено",
            ], 404);
        }

        $result = $item->delete();

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Категорію успішно видалено',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Помилка видалення категорії',
        ], 500);
    }
}
