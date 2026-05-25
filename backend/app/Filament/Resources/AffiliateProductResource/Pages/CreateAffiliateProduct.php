<?php

namespace App\Filament\Resources\AffiliateProductResource\Pages;

use App\Filament\Resources\AffiliateProductResource;
use Filament\Resources\Pages\CreateRecord;
// --- We will import everything we need directly ---
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Str;

class CreateAffiliateProduct extends CreateRecord
{
    // --- We are NOT using the trait here anymore ---
    // use ProcessesImages; 

    protected static string $resource = AffiliateProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $imageUrl = $data['main_image_url_snapshot'] ?? null;
        $asin = $data['amazon_asin'] ?? null;

        if ($imageUrl && $asin) {
            // Call the method that is now part of THIS class
            $imagePaths = $this->processImageAndGenerateSizes($imageUrl, $asin);

            if (!empty($imagePaths)) {
                $data['image_urls'] = $imagePaths;
                $data['local_image_path'] = $imagePaths['large'];
            }
        }

        return $data;
    }

    /**
     * This is the image processing logic, copied directly from the trait.
     * By having it here, we eliminate any possibility of a trait loading error.
     */
    protected function processImageAndGenerateSizes(string $imageUrl, string $baseFilename): array
    {
        // For this specific resource, we always use the 'affiliate-images' directory.
        $directory = 'affiliate-images';

        if (!Str::startsWith($imageUrl, ['http://', 'https://'])) {
            Log::warning('Skipping image download: Invalid URL provided.', ['url' => $imageUrl]);
            return [];
        }

        try {
            $response = Http::timeout(30)->get($imageUrl);
            if (!$response->successful()) {
                Log::error("Failed to download image.", ['url' => $imageUrl, 'status' => $response->status()]);
                return [];
            }

            $imageContent = $response->body();
            $manager = new ImageManager(new GdDriver());

            $sizes = ['large' => 1024, 'medium' => 768, 'small' => 400];
            $paths = [];

            foreach ($sizes as $name => $width) {
                $path = "{$directory}/{$baseFilename}-{$name}.webp";

                $processedImage = $manager->read($imageContent)
                    ->scaleDown(width: $width)
                    ->toWebp(quality: 85);

                Storage::disk('public')->put($path, (string) $processedImage);
                $paths[$name] = $path;
            }

            Log::info("Successfully processed image for ASIN: {$baseFilename}", $paths);
            return $paths;

        } catch (\Exception $e) {
            Log::error("An exception occurred while processing image for ASIN: {$baseFilename}", ['error' => $e->getMessage()]);
            report($e);
            return [];
        }
    }
}