<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;

/**
 * Class BlogPostRepository.
 */
class BlogPostRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Отримати список статей
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate(
        int $perPage = 10,
        int $page = 1,
        ?string $search = null,
        string $sortBy = 'id',
        string $sortDir = 'desc'
    ) {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
        ];

        $allowedSortColumns = [
            'id',
            'title',
            'published_at',
            'is_published',
            'user_id',
            'category_id',
        ];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }

        $sortDir = strtolower($sortDir) === 'asc' ? 'asc' : 'desc';

        $query = $this->startConditions()
            ->select($columns)
            ->with([
                'category' => function ($query) {
                    $query->select(['id', 'title']);
                },
                'user:id,name',
            ]);

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', $search . '%')
                    ->orWhere('slug', 'like', $search . '%');
            });
        }

        return $query
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Отримати модель для редагування в адмінці
     *
     * @param int $id
     * @return Model|null
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Отримати один пост для перегляду.
     *
     * @param int $id
     * @return Model|null
     */
    public function getForView($id)
    {
        $columns = [
            'id',
            'title',
            'slug',
            'excerpt',
            'content_raw',
            'content_html',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
            'created_at',
            'updated_at',
        ];

        return $this->startConditions()
            ->select($columns)
            ->with([
                'category:id,title',
                'user:id,name',
            ])
            ->find($id);
    }
}
