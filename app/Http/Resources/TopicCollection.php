<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TopicCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
