<?php

namespace App\Console\Commands;

use App\Models\AffiliateProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-existing-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans all affiliate products and downloads any linked Amazon images to local storage.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to process existing product images...');

        // Prepare a failure log CSV
        $logPath = storage_path('logs/failed_image_downloads.csv');
        if (!file_exists($logPath)) {
            file_put_contents($logPath, "asin,url,error,type\n");
        }

        // Get products that have an external URL in the snapshot field (any http/https)
        // Skip entries that already reference a local storage path.
        $productsToProcess = AffiliateProduct::whereNotNull('main_image_url_snapshot')
            ->where('main_image_url_snapshot', 'like', 'http%')
            ->get();

        if ($productsToProcess->isEmpty()) {
            $this->info('No products with external Amazon images found to process. All good!');
            return 0;
        }

        // Create a progress bar for better user experience
        $progressBar = $this->output->createProgressBar($productsToProcess->count());
        $progressBar->start();

        foreach ($productsToProcess as $product) {
            $imageUrl = $product->main_image_url_snapshot;

            try {
                // Download the image from Amazon
                $response = Http::withoutVerifying()->get($imageUrl);

                if ($response->successful()) {
                    // Only attempt to save when the downloaded body is valid
                    $body = $response->body();
                    if ($body) {
                        // Use a directory for affiliate product images so they're easy to find
                        $filename = 'affiliate_products/' . Str::random(40) . '.jpg';

                        // Save the image to the public disk
                        Storage::disk('public')->put($filename, $body);

                        // Update the product record with the new local path
                        $product->main_image_url_snapshot = $filename;
                        // If try_on_image_url is empty or still pointing to remote, set it to the local image
                        if (empty($product->try_on_image_url) || str_starts_with($product->try_on_image_url, 'http')) {
                            $product->try_on_image_url = $filename;
                        }
                        $product->save();
                        $this->info("Downloaded and saved image for ASIN {$product->amazon_asin} -> {$filename}");
                    } else {
                        $this->warn("Empty body when downloading image for ASIN {$product->amazon_asin}");
                    }
                } else {
                    $this->warn("\nSkipping ASIN {$product->amazon_asin}: Could not download image (HTTP Status: {$response->status()})");
                    $line = [
                        $product->amazon_asin ?? '',
                        $imageUrl ?? '',
                        'HTTP ' . $response->status(),
                        'http_error',
                    ];
                    $csv = '"' . implode('","', array_map(function ($v) { return str_replace('"', '""', (string) $v); }, $line)) . "" . "\n";
                    file_put_contents($logPath, $csv, FILE_APPEND);
                }
            } catch (\Exception $e) {
                // If download fails, log the error and continue
                $this->error("\nFailed to download for ASIN {$product->amazon_asin}: " . $e->getMessage());
                $line = [
                    $product->amazon_asin ?? '',
                    $imageUrl ?? '',
                    $e->getMessage(),
                    'exception',
                ];
                $csv = '"' . implode('","', array_map(function ($v) { return str_replace('"', '""', (string) $v); }, $line)) . "" . "\n";
                file_put_contents($logPath, $csv, FILE_APPEND);
            }

            // Advance the progress bar
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\nImage processing complete!");

        return 0;
    }
}