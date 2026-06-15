<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

class BlogCategoryRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
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
     * Отримати список категорій для виводу в випадаючий список
     *
     * @return \Illuminate\Support\Collection
     */
    public function getForComboBox()
    {
        $columns = implode(', ', [
            'id',
            'CONCAT(id, ". ", title) AS id_title',
        ]);

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();

        return $result;
    }

    /**
     * Отримати категорії для виводу пагінатором.
     *
     * @param int|null $perPage
     * @param int $page
     * @param string|null $search
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = 10, int $page = 1, ?string $search = null)
    {
        $columns = [
            'id',
            'title',
            'slug',
            'parent_id',
        ];

        $query = $this
            ->startConditions()
            ->select($columns)
            ->with(['parentCategory:id,title']);

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', $search . '%')
                    ->orWhere('slug', 'like', $search . '%');
            });
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
