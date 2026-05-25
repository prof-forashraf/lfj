<?php

namespace App\Filament\Resources\AffiliateProductResource\Pages;

use App\Filament\Resources\AffiliateProductResource;
use App\Filament\Traits\ProcessesImages;
use App\Models\AffiliateProduct;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditAffiliateProduct extends EditRecord
{
    use ProcessesImages;

    protected static string $resource = AffiliateProductResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    /**
     * Provides a strong type-hint for the $this->record property.
     */
    public function getRecord(): AffiliateProduct
    {
        return $this->record;
    }

    /**
     * This method runs just before the data is saved to the database.
     * It handles image reprocessing if the source URL has been changed.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();

        // Trigger only if the source URL has been modified by the user.
        if ($record->main_image_url_snapshot !== $data['main_image_url_snapshot']) {

            // Step 1: Clean up old data first.
            if (is_array($record->image_urls)) {
                Storage::disk('public')->delete(array_values($record->image_urls));
            }
            $data['image_urls'] = null;
            $data['local_image_path'] = null;

            // Step 2: Attempt to process the NEW URL, but only if it's not empty.
            $newImageUrl = $data['main_image_url_snapshot'] ?? null;
            $asin = $record->amazon_asin;

            if ($newImageUrl && $asin) {
                // Call our reusable trait to download, resize, and save the new images.
                $imagePaths = $this->processImageAndGenerateSizes($newImageUrl, $asin);

                // If processing was successful, update the data array with the new paths.
                if (!empty($imagePaths)) {
                    $data['image_urls'] = $imagePaths;
                    $data['local_image_path'] = $imagePaths['large'];
                }
            }
        }

        return $data;
    }
}