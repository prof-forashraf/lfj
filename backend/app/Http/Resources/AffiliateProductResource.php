<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\SeoService;

/**
 * This resource transforms a single AffiliateProduct model into a JSON response
 * for your public-facing API (your React frontend).
 *
 * @mixin \App\Models\AffiliateProduct
 */
class AffiliateProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 1. Generate full, absolute URLs for each image size from the `image_urls` JSON column.
        // The `?? []` operator provides a safe fallback if `image_urls` is null in the database.
        $imageUrls = $this->resolveImageUrls();

        return [
            // --- Standard Product Data ---
            'id' => $this->id,
            'asin' => $this->amazon_asin,
            'name' => $this->product_name_snapshot,
            'price' => $this->price,
            'affiliate_url' => $this->amazon_url,
            'notes' => $this->your_notes,
            'item_type' => $this->item_type,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),

            // --- Processed Image Data for Frontend ---

            // A simple, single URL for basic display (defaults to the fast-loading small version).
            // This is what the frontend's `product.image_url` will be.
            'image_url' => $imageUrls->get('small')
                ?: $imageUrls->filter()->first()
                ?: ($this->local_image_path && Storage::disk('public')->exists($this->local_image_path)
                    ? Storage::disk('public')->url($this->local_image_path)
                    : ($this->main_image_url_snapshot
                        ? (Str::startsWith($this->main_image_url_snapshot, ['http://', 'https://', '//'])
                            ? $this->main_image_url_snapshot
                            : Storage::disk('public')->url(ltrim($this->main_image_url_snapshot, '/')))
                        : null)),

            // The complete object of all available image sizes.
            // This is for the <picture> element in the frontend's ProductCard.
            'image_urls' => $imageUrls,

            // Also provide the full URL for the try-on image, if it exists.
            'try_on_image_url' => $this->try_on_image_url ? (Str::startsWith($this->try_on_image_url, ['http://', 'https://', '//']) ? $this->try_on_image_url : Storage::disk('public')->url($this->try_on_image_url)) : null,
            'seo' => [], // Temporarily disabled: app(SeoService::class)->forProduct($this->resource),
        ];
    }

    private function resolveImageUrls(): \Illuminate\Support\Collection
    {
        $imageUrls = collect($this->image_urls ?? [])->map(function ($path) {
            if (!$path) {
                return null;
            }
            if (Str::startsWith($path, ['http://', 'https://', '//'])) {
                return $path;
            }
            return Storage::disk('public')->url($path);
        });

        if ($imageUrls->filter()->isNotEmpty()) {
            return $imageUrls;
        }

        if ($this->amazon_asin) {
            $sizes = ['small', 'medium', 'large'];
            $resolved = collect($sizes)->mapWithKeys(function ($size) {
                $candidate = "affiliate-images/{$this->amazon_asin}-{$size}.webp";
                return [$size => Storage::disk('public')->exists($candidate) ? Storage::disk('public')->url($candidate) : null];
            });

            if ($resolved->filter()->isNotEmpty()) {
                return $resolved;
            }
        }

        if ($this->local_image_path && Storage::disk('public')->exists($this->local_image_path)) {
            $url = Storage::disk('public')->url($this->local_image_path);
            return collect([
                'small' => $url,
                'medium' => $url,
                'large' => $url,
            ]);
        }

        return $imageUrls;
    }
}
