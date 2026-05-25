<?php

namespace App\Filament\Resources\PredictiveAnalyticsToolResource\Pages;

use App\Filament\Resources\PredictiveAnalyticsToolResource;
use App\Services\PredictiveAnalyticsService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Cache;

class PredictiveAnalyticsDashboard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PredictiveAnalyticsToolResource::class;

    protected static string $view = 'filament.resources.predictive-analytics-tool-resource.pages.predictive-analytics-dashboard';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('forecast_start_date')
                    ->label('Forecast Start Date')
                    ->default(now()->toDateString())
                    ->required(),

                DatePicker::make('forecast_end_date')
                    ->label('Forecast End Date')
                    ->default(now()->addMonths(6)->toDateString())
                    ->required(),

                Select::make('forecast_period')
                    ->label('Forecast Period')
                    ->options([
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                    ])
                    ->default('monthly')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function generateForecast(): void
    {
        $data = $this->form->getState();

        $analyticsService = app(PredictiveAnalyticsService::class);

        $forecast = $analyticsService->predictSales(
            $data['forecast_start_date'],
            $data['forecast_end_date'],
            $data['forecast_period']
        );

        // Cache the forecast for display
        Cache::put('predictive_forecast', $forecast, now()->addMinutes(30));

        $this->redirect(request()->header('Referer'));
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('generateForecast')
                ->label('Generate Forecast')
                ->action('generateForecast')
                ->color('primary'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_forecast')
                ->label('Export Forecast')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    // Export logic would go here
                    \Filament\Notifications\Notification::make()
                        ->title('Export Started')
                        ->body('Forecast data export has been initiated.')
                        ->success()
                        ->send();
                }),
        ];
    }
}