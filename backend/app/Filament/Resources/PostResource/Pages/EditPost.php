<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Filament\Traits\ProcessesImages;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditPost extends EditRecord
{
    use ProcessesImages;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Provides a strong type-hint for the $this->record property.
     */
    public function getRecord(): Post
    {
        return $this->record;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();
        $slug = $record->slug; // Always use the existing record's slug for consistency
        $imagePaths = [];
        $newImageUploaded = false;

        // --- NEW LOGIC TO HANDLE DUAL INPUTS ---

        // Step 1: Prioritize the local file upload.
        // The 'featured_image_upload' will contain a temporary path string if a new file is uploaded.
        if (!empty($data['featured_image_upload'])) {
            $newImageUploaded = true;

            // Read the content of the temporary uploaded file
            $imageContent = Storage::disk('local')->get($data['featured_image_upload']);

            if ($imageContent) {
                $imagePaths = $this->processImageFromContent(
                    imageContent: $imageContent,
                    baseFilename: $slug,
                    directory: 'post-featured-images'
                );
            }
        }
        // Step 2: Fallback to the URL if no file was uploaded AND the URL has changed.
        elseif ($record->featured_image_url_snapshot !== $data['featured_image_url_snapshot']) {
            $newImageUploaded = true; // Mark that an image change occurred
            $newImageUrl = $data['featured_image_url_snapshot'];

            if ($newImageUrl) {
                $imagePaths = $this->processImageFromUrl(
                    imageUrl: $newImageUrl,
                    baseFilename: $slug,
                    directory: 'post-featured-images'
                );
            }
        }

        // Step 3: If a new image was processed (from either file or URL), clean up old files and update data.
        if ($newImageUploaded) {
            // Delete the old physical image files from storage, if they exist.
            if (is_array($record->image_urls)) {
                Storage::disk('public')->delete(array_values($record->image_urls));
            }

            // If the new image processing was successful, update the data array.
            if (!empty($imagePaths)) {
                $data['image_urls'] = $imagePaths;
                $data['featured_image'] = $imagePaths['large'];
            } else {
                // If processing failed or the fields were cleared, ensure data is nulled.
                $data['image_urls'] = null;
                $data['featured_image'] = null;
            }
        }

        // IMPORTANT: Always unset the temporary upload field so Filament doesn't try to save it.
        unset($data['featured_image_upload']);

        return $data;
    }
}