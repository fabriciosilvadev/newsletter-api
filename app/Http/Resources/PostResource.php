<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property \Carbon\Carbon $created_at
 */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'topic' => TopicResource::make($this->whenLoaded('topic')),
            'creator' => UserResource::make($this->whenLoaded('creator')),
            'created_at' => $this->created_at,
        ];
    }
}
