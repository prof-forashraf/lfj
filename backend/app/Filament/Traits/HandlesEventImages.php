<?php
//app/Filament/Traits/HandlesEventImages.php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

trait HandlesEventImages
{
    /**
     * Process and store the uploaded image in multiple sizes and WebP.
     */
    protected function processAndStoreImage($file, string $directory = 'events', ?array $resize = null): string
    {
        $extension = 'webp'; // Always convert to webp
        $filename = uniqid() . '.' . $extension;
        $path = "{$directory}/{$filename}";

        // Read original image
        $image = Image::read($file);

        if ($resize) {
            // In v3, use scaleDown() instead of resize() with closure
            $image = $image->scaleDown($resize[0], $resize[1]);
        }

        Storage::disk('public')->put($path, (string) $image->toWebp(85));

        // Also store thumbnail (example 300x300, scale down keeping ratio)
        $thumbPath = "{$directory}/thumb_{$filename}";
        $thumbnail = Image::read($file)->scaleDown(300, 300);

        Storage::disk('public')->put($thumbPath, (string) $thumbnail->toWebp(85));

        return $filename;
    }

    /**
     * Delete old images (original + thumbnails).
     */
    protected function deleteOldImages(?string $filename, string $directory = 'events'): void
    {
        if (!$filename) {
            return;
        }

        $paths = [
            "{$directory}/{$filename}",
            "{$directory}/thumb_{$filename}",
        ];

        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Automatically delete event images when the model is deleted.
     */
    public static function bootHandlesEventImages()
    {
        static::deleting(function ($model) {
            if (isset($model->image)) {
                $model->deleteOldImages($model->image, 'events');
            }
        });
    }
}
