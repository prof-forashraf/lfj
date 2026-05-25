<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage; // <-- Add this import
use Illuminate\Support\Str;
use App\Services\SeoService;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageUrls = collect($this->image_urls ?? [])
            ->map(fn ($path) => $path ? Storage::disk('public')->url($path) : null)
            ->all();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description_html' => $this->description,
            'start_datetime_iso' => $this->start_datetime ? $this->start_datetime->toIso8601String() : null,
            'start_datetime_formatted' => $this->start_datetime ? $this->start_datetime->format('F j, Y - g:i A') : null,
            'end_datetime_iso' => $this->end_datetime ? $this->end_datetime->toIso8601String() : null,
            'end_datetime_formatted' => $this->end_datetime ? $this->end_datetime->format('F j, Y - g:i A') : null,
            'location_name' => $this->location_name,
            'location_address' => $this->location_address,
            'event_url' => $this->event_url,

            // THIS IS THE FIX: Use Storage::url() instead of asset()
            'featured_image_url' => $this->featured_image ? Storage::disk('public')->url($this->featured_image) : ($imageUrls['large'] ?? null),
            'image_urls' => $imageUrls,

            'status' => $this->status,
            'seo' => [],
            // You can remove these if they are not used in the frontend
            'created_at_human' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'updated_at_iso' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
