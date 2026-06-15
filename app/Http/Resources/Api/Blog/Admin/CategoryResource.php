<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Трансформація ресурсу в масив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'parent_id'    => $this->parent_id,
            'parent_title' => $this->parent_title,

            'parent_category' => $this->whenLoaded('parentCategory', function () {
                return [
                    'id'    => $this->parentCategory->id,
                    'title' => $this->parentCategory->title,
                ];
            }),
        ];
    }
}
