<?php

namespace App\Filament\Pages;

use App\Models\AuditLog;
use App\Models\DailyMetalPrice;
use App\Services\AnalyticsService;
use App\Services\GoldApiService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MetalPriceManagement extends Page
{
    use InteractsWithTable;

    protected static string $view = 'filament.pages.metal-price-management';
    protected static ?string $navigationIcon = 'heroicon-o-trending-up';
    protected static ?string $navigationGroup = 'Pricing Management';
    protected static ?string $navigationLabel = 'Metal Price Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'metal-price-management';

    protected array $latestMarketMetrics = [];

    protected function getTableQuery(): Builder
    {
        return DailyMetalPrice::query()->orderByDesc('price_date');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('price_date')
                ->label('Date')
                ->date()
                ->sortable(),

            TextColumn::make('metal_symbol')
                ->label('Metal')
                ->sortable()
                ->searchable(),

            TextColumn::make('base_currency')
                ->label('Currency')
                ->sortable(),

            TextInputColumn::make('price_per_unit')
                ->label('Price per Unit')
                ->numeric()
                ->prefix('$')
                ->saveOnBlur()
                ->sortable(),

            TextColumn::make('unit')
                ->label('Unit')
                ->sortable(),

            TextInputColumn::make('source')
                ->label('Source')
                ->saveOnBlur()
                ->sortable(),

            TextInputColumn::make('notes')
                ->label('Notes')
                ->saveOnBlur()
                ->contentLimit(60)
                ->sortable(false),

            BadgeColumn::make('created_at')
                ->label('Created')
                ->dateTime()
                ->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('metal_symbol')
                ->label('Metal')
                ->options(DailyMetalPrice::query()->distinct()->pluck('metal_symbol', 'metal_symbol')->toArray()),

            SelectFilter::make('base_currency')
                ->label('Currency')
                ->options(DailyMetalPrice::query()->distinct()->pluck('base_currency', 'base_currency')->toArray()),

            DateFilter::make('price_date')
                ->label('Price Date'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refreshMetrics')
                ->label('Refresh Metrics')
                ->action('refreshLiveMetrics')
                ->icon('heroicon-o-refresh'),

            Action::make('queueSync')
                ->label('Queue Price Sync')
                ->icon('heroicon-o-arrow-path')
                ->action('dispatchPriceSync')
                ->requiresConfirmation()
                ->color('warning'),

            Action::make('resetCircuitBreaker')
                ->label('Reset Circuit Breaker')
                ->icon('heroicon-o-lightning-bolt')
                ->action('resetCircuitBreaker')
                ->requiresConfirmation()
                ->color('danger'),
        ];
    }

    public function mount(): void
    {
        $this->latestMarketMetrics = $this->loadLivePriceMetrics();
    }

    public function refreshLiveMetrics(): void
    {
        $this->latestMarketMetrics = $this->loadLivePriceMetrics();
        Notification::make()
            ->title('Live market metrics refreshed')
            ->success()
            ->send();
    }

    public function dispatchPriceSync(): void
    {
        Artisan::queue('metal:sync-prices');

        $this->logAdminActivity('Queued metal price synchronization', [
            'trigger' => 'filament',
        ]);

        Notification::make()
            ->title('Metal price sync has been queued')
            ->success()
            ->send();
    }

    public function resetCircuitBreaker(GoldApiService $goldApiService): void
    {
        $goldApiService->resetCircuitBreakerState();

        $this->logAdminActivity('Reset metal API circuit breaker', [
            'trigger' => 'filament',
        ]);

        $this->latestMarketMetrics = $this->loadLivePriceMetrics();

        Notification::make()
            ->title('Circuit breaker reset successfully')
            ->success()
            ->send();
    }

    protected function loadLivePriceMetrics(): array
    {
        $analytics = app(AnalyticsService::class)->getMetalSyncHealth();
        $health = $analytics['health'] ?? [];
        $circuitBreaker = $analytics['circuit_breaker'] ?? [];

        $latest = [];
        foreach (['XAU', 'XAG', 'XPT', 'XPD'] as $symbol) {
            $latest[$symbol] = Cache::get(sprintf('metal_prices:%s:USD:latest', $symbol), [
                'price_per_unit' => null,
                'currency' => 'USD',
                'timestamp' => null,
            ]);
        }

        return [
            'live' => $latest,
            'health' => $health,
            'circuit_breaker' => $circuitBreaker,
        ];
    }

    protected function logAdminActivity(string $message, array $metadata = []): void
    {
        if (! Auth::check()) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => DailyMetalPrice::class,
            'model_id' => null,
            'action' => $message,
            'old_values' => [],
            'new_values' => $metadata,
        ]);

        Log::info($message, array_merge($metadata, ['user_id' => Auth::id()]));
    }
}
