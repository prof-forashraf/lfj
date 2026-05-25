<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VideoCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => VideoResource::collection($this->collection),
            'links' => [ /* ... standard pagination links ... */],
            'meta' => [ /* ... standard pagination meta ... */],
        ];
    }
}