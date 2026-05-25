<?php
namespace App\Filament\Traits;
//app/Filament/Traits/ProcessesImages.php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Str;

trait ProcessesImages
{
    /**
     * Public method to process an image from a URL.
     */
    protected function processImageFromUrl(string $imageUrl, string $baseFilename, string $directory): array
    {
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
            // Call the core processing logic with the downloaded content
            return $this->processImageContent($response->body(), $baseFilename, $directory);
        } catch (\Exception $e) {
            Log::error("An exception occurred while downloading image.", ['error' => $e->getMessage(), 'url' => $imageUrl]);
            report($e);
            return [];
        }
    }

    /**
     * Public method to process an image from an uploaded file's content.
     */
    protected function processImageFromContent(string $imageContent, string $baseFilename, string $directory): array
    {
        // Call the core processing logic with the provided file content
        return $this->processImageContent($imageContent, $baseFilename, $directory);
    }

    /**
     * Private core logic that resizes, converts, and saves the image content.
     */
    private function processImageContent(string $imageContent, string $baseFilename, string $directory): array
    {
        try {
            $manager = new ImageManager(new GdDriver());
            $image = $manager->read($imageContent);

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

            Log::info("Successfully processed image for {$baseFilename} into directory: {$directory}", $paths);
            return $paths;

        } catch (\Exception $e) {
            Log::error("An exception occurred while processing image content for {$baseFilename}", ['error' => $e->getMessage()]);
            report($e);
            return [];
        }
    }
}