<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;

trait HandlesEventImages
{
    /**
     * Store event images in multiple sizes as webp
     */
    protected function storeEventImages(UploadedFile $file, string $folder = 'event-featured-images'): array
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $timestamp = time();
        $sizes = [
            'large' => 1200,
            'medium' => 600,
            'small' => 300,
        ];

        $imageUrls = [];

        foreach ($sizes as $sizeName => $width) {
            $resized = Image::read($file->getRealPath())
                ->scale(width: $width) // maintain aspect ratio
                ->toWebp(90); // quality 90%

            $path = "{$folder}/{$filename}-{$timestamp}-{$sizeName}.webp";

            Storage::disk('public')->put($path, (string) $resized);
            $imageUrls[$sizeName] = $path;
        }

        return $imageUrls;
    }

    /**
     * Delete all image versions
     */
    protected function deleteEventImages(?array $imageUrls): void
    {
        if (empty($imageUrls)) {
            return;
        }

        foreach ($imageUrls as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
