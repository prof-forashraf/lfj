<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PruneMetalPrices extends Command
{
    protected $signature = 'metal:prune-prices {--dry-run}';
    protected $description = 'Prune historical metal price records to keep the metal price data set bounded and operationally compact.';

    public function handle(): int
    {
        $dailyRetentionDays = (int) config('services.metal_sync.retention.daily_metal_prices_days', 365);
        $goldRetentionDays = (int) config('services.metal_sync.retention.gold_prices_days', 90);

        if ($dailyRetentionDays <= 0 || $goldRetentionDays <= 0) {
            throw new \RuntimeException('Metal sync retention thresholds must be configured as positive integers.');
        }

        $dailyCutoff = now()->subDays($dailyRetentionDays)->toDateString();
        $goldCutoff = now()->subDays($goldRetentionDays)->toDateString();

        if ($this->option('dry-run')) {
            $dailyCount = DB::table('daily_metal_prices')
                ->whereDate('price_date', '<', $dailyCutoff)
                ->count();

            $goldCount = DB::table('gold_prices')
                ->whereDate('date_recorded', '<', $goldCutoff)
                ->count();

            $this->info("Dry run: {$dailyCount} daily metal price rows older than {$dailyCutoff} would be pruned.");
            $this->info("Dry run: {$goldCount} gold price rows older than {$goldCutoff} would be pruned.");

            return 0;
        }

        $start = microtime(true);
        $dailyDeleted = 0;
        $goldDeleted = 0;

        $dailyPreserveIds = $this->getDailyPreserveIds();
        $goldPreserveIds = $this->getGoldPreserveIds();

        try {
            $dailyDeleted = $this->pruneTableInChunks(
                'daily_metal_prices',
                'price_date',
                $dailyCutoff,
                $dailyPreserveIds
            );

            $goldDeleted = $this->pruneTableInChunks(
                'gold_prices',
                'date_recorded',
                $goldCutoff,
                $goldPreserveIds
            );

            $durationMs = round((microtime(true) - $start) * 1000, 2);

            $this->info("Pruned {$dailyDeleted} daily metal price rows older than {$dailyCutoff}.");
            $this->info("Pruned {$goldDeleted} gold price rows older than {$goldCutoff}.");
            $this->info("Prune completed in {$durationMs} ms for tables: daily_metal_prices, gold_prices.");

            Cache::put('metal_sync:last_prune_run', now()->toISOString(), now()->addDays(7));
            Cache::put('metal_sync:last_prune_duration_ms', $durationMs, now()->addDays(7));
            Cache::put('metal_sync:last_pruned_rows', [
                'daily_metal_prices' => $dailyDeleted,
                'gold_prices' => $goldDeleted,
            ], now()->addDays(7));
            Cache::put('metal_sync:last_prune_status', 'success', now()->addDays(7));

            Log::channel('api')->info('Metal price pruning completed', [
                'daily_cutoff' => $dailyCutoff,
                'gold_cutoff' => $goldCutoff,
                'daily_deleted' => $dailyDeleted,
                'gold_deleted' => $goldDeleted,
                'duration_ms' => $durationMs,
                'tables' => ['daily_metal_prices', 'gold_prices'],
            ]);

            return 0;
        } catch (Throwable $e) {
            $durationMs = round((microtime(true) - $start) * 1000, 2);
            Cache::put('metal_sync:last_prune_run', now()->toISOString(), now()->addDays(7));
            Cache::put('metal_sync:last_prune_duration_ms', $durationMs, now()->addDays(7));
            Cache::put('metal_sync:last_pruned_rows', [
                'daily_metal_prices' => $dailyDeleted,
                'gold_prices' => $goldDeleted,
            ], now()->addDays(7));
            Cache::put('metal_sync:last_prune_status', 'failed', now()->addDays(7));

            Log::channel('api')->error('Metal price pruning failed', [
                'daily_cutoff' => $dailyCutoff,
                'gold_cutoff' => $goldCutoff,
                'daily_deleted' => $dailyDeleted,
                'gold_deleted' => $goldDeleted,
                'duration_ms' => $durationMs,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('Prune failed: ' . $e->getMessage());

            return 1;
        }
    }

    protected function getDailyPreserveIds(): array
    {
        return DB::table('daily_metal_prices as current')
            ->select('current.id')
            ->join(DB::raw('(select metal_symbol, base_currency, max(price_date) as max_price_date from daily_metal_prices group by metal_symbol, base_currency) as latest'), function ($join) {
                $join->on('current.metal_symbol', '=', 'latest.metal_symbol')
                    ->on('current.base_currency', '=', 'latest.base_currency')
                    ->on('current.price_date', '=', 'latest.max_price_date');
            })
            ->pluck('current.id')
            ->toArray();
    }

    protected function getGoldPreserveIds(): array
    {
        return DB::table('gold_prices as current')
            ->select('current.id')
            ->join(DB::raw('(select currency_code, max(date_recorded) as max_date_recorded from gold_prices group by currency_code) as latest'), function ($join) {
                $join->on('current.currency_code', '=', 'latest.currency_code')
                    ->on('current.date_recorded', '=', 'latest.max_date_recorded');
            })
            ->pluck('current.id')
            ->toArray();
    }

    protected function pruneTableInChunks(string $table, string $dateColumn, string $cutoffDate, array $preserveIds = []): int
    {
        $deleted = 0;

        DB::table($table)
            ->whereDate($dateColumn, '<', $cutoffDate)
            ->when(count($preserveIds) > 0, fn ($query) => $query->whereNotIn('id', $preserveIds))
            ->orderBy('id')
            ->chunkById(1000, function ($rows) use (&$deleted, $table) {
                $ids = $rows->pluck('id')->toArray();

                DB::transaction(function () use ($table, $ids, &$deleted) {
                    $count = DB::table($table)->whereIn('id', $ids)->delete();
                    $deleted += $count;
                });
            });

        return $deleted;
    }
}
