<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return TopicResource::make($this)->toArray($request);
    }
}
