<?php

namespace App\Repositories;

use App\Models\GoldPrice;

class GoldPriceRepository
{
    public function upsert(array $data): GoldPrice
    {
        return GoldPrice::updateOrCreate(
            [
                'date_recorded' => $data['date_recorded'],
                'currency_code' => $data['currency_code'],
            ],
            $data
        );
    }

    public function latest(string $currency = 'USD'): ?GoldPrice
    {
        return GoldPrice::where('currency_code', strtoupper($currency))
            ->orderByDesc('date_recorded')
            ->orderByDesc('timestamp_recorded')
            ->first();
    }
}
