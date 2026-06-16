<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'excerpt'        => $this->excerpt,
            'content_raw'    => $this->content_raw,
            'content_html'   => $this->content_html,
            'is_published'   => (bool) $this->is_published,

            'date_published' => $this->published_at
                ? Carbon::parse($this->published_at)->format('Y-m-d H:i:s')
                : null,

            'published_at'   => $this->published_at,

            'user_id'        => $this->user_id,
            'category_id'    => $this->category_id,

            'category'       => $this->whenLoaded('category', function () {
                return [
                    'id'    => $this->category->id,
                    'title' => $this->category->title,
                ];
            }),

            'user'           => $this->whenLoaded('user', function () {
                return [
                    'id'   => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
        ];
    }
}
