<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\SeoService;

class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'youtube_video_id' => $this->youtube_video_id,
            'description' => $this->description,
            'duration_seconds' => $this->duration_seconds,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'thumbnail_url' => $this->youtube_thumbnail_url, // Uses the accessor
            'embed_url' => $this->youtube_embed_url,       // Uses the accessor
            'seo' => app(SeoService::class)->forVideo($this->resource),
            'created_at_human' => $this->created_at->diffForHumans(),
        ];
    }
}
