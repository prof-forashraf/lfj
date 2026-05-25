<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['image'])) {
            // Delete old images if they exist
            if (is_array($this->record->image_urls)) {
                foreach ($this->record->image_urls as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            // Process new image
            $filePath = $data['image'];
            $disk = Storage::disk('public');
            $original = $disk->get($filePath);

            $sizes = [
                'small' => 300,
                'medium' => 600,
                'large' => 1200,
            ];

            $imageUrls = [];
            foreach ($sizes as $label => $width) {
                $resized = Image::read($original)->scale(width: $width);
                $filename = 'event-featured-images/' . uniqid() . "-{$label}.webp";
                $disk->put($filename, (string) $resized->encodeByExtension('webp', 85));
                $imageUrls[$label] = $filename;
            }

            // Save JSON of all sizes
            $data['image_urls'] = $imageUrls;
            
            // ✅ Also store the large version separately for compatibility
            $data['featured_image'] = $imageUrls['large'];

            // Clean up temp file
            $disk->delete($filePath);
            unset($data['image']);
        }

        return $data;
    }
}
