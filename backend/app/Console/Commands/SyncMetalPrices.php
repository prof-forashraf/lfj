<?php

namespace App\Console\Commands;

use App\Jobs\SyncGoldApiPricesJob;
use Illuminate\Console\Command;

class SyncMetalPrices extends Command
{
    protected $signature = 'metal:sync-prices {--currency=USD} {--metals=*} {--sync}';

    protected $description = 'Queue a synchronization job to refresh precious metal prices from GoldAPI.io.';

    public function handle(): int
    {
        $currency = strtoupper($this->option('currency') ?? 'USD');
        $metals = $this->option('metals') ?: ['XAU', 'XAG'];

        if ($this->option('sync')) {
            SyncGoldApiPricesJob::dispatchSync($metals, $currency);
            $this->info(sprintf('Metal price sync executed inline for %s in %s.', implode(', ', $metals), $currency));
        } else {
            SyncGoldApiPricesJob::dispatch($metals, $currency)
                ->onQueue(config('services.metal_sync.queue_name', 'metal-sync'));
            $this->info(sprintf('Metal price sync queued for %s in %s.', implode(', ', $metals), $currency));
        }

        return 0;
    }
}
