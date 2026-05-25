<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Filament\Traits\ProcessesImages;
use App\Models\Post; // Import the Post model
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Import Str facade

class CreatePost extends CreateRecord
{
    use ProcessesImages;

    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // --- THIS IS THE FIX ---

        // Step 1: Force a slug to exist.
        // If the form's live-update slug is missing, generate it right here from the title.
        if (empty($data['slug']) && !empty($data['title'])) {
            // We use the model's own unique slug generator for perfect consistency.
            $data['slug'] = Post::createUniqueSlug($data['title']);
        }

        $imagePaths = [];
        $slug = $data['slug'] ?? null;

        // Step 2: Now that a slug is guaranteed to exist, proceed with image logic.
        if ($slug) {
            // Prioritize the local file upload
            if (!empty($data['featured_image_upload'])) {
                $imageContent = Storage::disk('local')->get($data['featured_image_upload']);
                if ($imageContent) {
                    $imagePaths = $this->processImageFromContent($imageContent, $slug, 'post-featured-images');
                }
            }
            // Fallback to the URL if no file was uploaded
            elseif (!empty($data['featured_image_url_snapshot'])) {
                $imageUrl = $data['featured_image_url_snapshot'];
                $imagePaths = $this->processImageFromUrl($imageUrl, $slug, 'post-featured-images');
            }
        }

        // Step 3: Populate the image data if processing was successful.
        if (!empty($imagePaths)) {
            $data['image_urls'] = $imagePaths;
            $data['featured_image'] = $imagePaths['large'];
        }

        // Step 4: Always unset the temporary upload field.
        unset($data['featured_image_upload']);

        return $data;
    }
}