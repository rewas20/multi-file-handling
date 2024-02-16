<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FileResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'files' => $this->getMedia('post_attachments')?FileResource::collection($this->getMedia('post_attachments')):null,
        ];
    }
}
