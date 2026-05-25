<?php
// app/Http/Resources/StudioProductResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StudioProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->product_name_snapshot,
            // ✅ FIX: Provide both image URLs, converted to full paths
            'image_url' => $this->main_image_url_snapshot ? Storage::disk('public')->url($this->main_image_url_snapshot) : null,
            'try_on_image_url' => $this->try_on_image_url ? Storage::disk('public')->url($this->try_on_image_url) : null,
            'item_type' => $this->item_type,
            'tags' => explode(',', $this->your_notes) ?? [], // Simple tag simulation from notes
        ];
    }
}