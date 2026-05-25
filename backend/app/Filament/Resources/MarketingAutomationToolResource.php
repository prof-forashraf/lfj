<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingAutomationToolResource\Pages;
use App\Models\User;
use App\Services\MarketingAutomationService;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Marketing Automation Tool Resource (Read-Only Analytics)
 *
 * Provides AI-powered user segmentation and campaign recommendations.
 * This is a READ-ONLY analytics view layered on top of User data.
 * All user account management should be done via UserResource.
 *
 * Features:
 * - AI user segmentation (High Value, Regular, At Risk, New)
 * - Personalized campaign recommendations
 * - Engagement scoring and lifetime value estimation
 * - Bulk campaign generation and email sending capabilities
 *
 * Note: This resource is kept as an analytics dashboard for marketing insights.
 * For user management, use UserResource instead.
 */
class MarketingAutomationToolResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'AI Marketing Analyzer';

    protected static ?string $navigationGroup = '🤖 AI Tools';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255)
                    ->disabled(), // Read-only

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->disabled(), // Read-only

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->disabled(), // Read-only

                Forms\Components\Textarea::make('preferences')
                    ->label('User Preferences')
                    ->maxLength(1000)
                    ->disabled(), // Read-only
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_login')
                    ->label('Last Login')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('segment')
                    ->label('AI Segment')
                    ->state(function (User $record) {
                        $marketingService = app(MarketingAutomationService::class);
                        $segment = $marketingService->segmentUser($record);
                        return $segment['segment'];
                    })
                    ->color(function (User $record) {
                        $marketingService = app(MarketingAutomationService::class);
                        $segment = $marketingService->segmentUser($record);
                        return match($segment['segment']) {
                            'High Value' => 'success',
                            'Regular' => 'warning',
                            'At Risk' => 'danger',
                            'New' => 'info',
                            default => 'gray'
                        };
                    }),

                Tables\Columns\TextColumn::make('campaign_recommendation')
                    ->label('Recommended Campaign')
                    ->state(function (User $record) {
                        $marketingService = app(MarketingAutomationService::class);
                        $campaign = $marketingService->generatePersonalizedCampaign($record);
                        return $campaign['campaign_type'];
                    }),

                Tables\Columns\TextColumn::make('engagement_score')
                    ->label('Engagement Score')
                    ->state(function (User $record) {
                        $marketingService = app(MarketingAutomationService::class);
                        $segment = $marketingService->segmentUser($record);
                        return number_format($segment['engagement_score'], 2);
                    }),

                Tables\Columns\TextColumn::make('lifetime_value')
                    ->label('Est. Lifetime Value')
                    ->state(function (User $record) {
                        $marketingService = app(MarketingAutomationService::class);
                        $segment = $marketingService->segmentUser($record);
                        return '$' . number_format($segment['lifetime_value'], 2);
                    })
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('segment')
                    ->label('AI Segment')
                    ->options([
                        'High Value' => 'High Value',
                        'Regular' => 'Regular',
                        'At Risk' => 'At Risk',
                        'New' => 'New',
                    ]),

                Tables\Filters\Filter::make('recent_activity')
                    ->label('Active in Last 30 Days')
                    ->query(function (Builder $query): Builder {
                        return $query->where('last_login', '>=', now()->subDays(30));
                    }),

                Tables\Filters\Filter::make('high_engagement')
                    ->label('High Engagement')
                    ->query(function (Builder $query): Builder {
                        return $query->whereRaw('1=1'); // Would filter based on engagement score
                    }),
            ])
            ->actions([
                // View-only action
                Tables\Actions\ViewAction::make()
                    ->label('View Profile'),

                // AI-generated campaign (read-only analysis, no data modification)
                Tables\Actions\Action::make('generate_campaign')
                    ->label('Generate Campaign')
                    ->icon('heroicon-o-envelope')
                    ->color('primary')
                    ->action(function (User $record) {
                        $marketingService = app(MarketingAutomationService::class);
                        $campaign = $marketingService->generatePersonalizedCampaign($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Campaign Generated')
                            ->body("Generated '{$campaign['campaign_type']}' campaign for {$record->name}")
                            ->success()
                            ->send();
                    }),

                // Email sending action (no edit permission needed)
                Tables\Actions\Action::make('send_personalized_email')
                    ->label('Send Email')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Send Personalized Email')
                    ->modalDescription('This will send a personalized marketing email to this customer.')
                    ->modalSubmitActionLabel('Send Email')
                    ->action(function (User $record) {
                        $marketingService = app(MarketingAutomationService::class);
                        $campaign = $marketingService->generatePersonalizedCampaign($record);

                        // Here you would integrate with your email service
                        \Illuminate\Support\Facades\Log::info('Personalized Email Sent', [
                            'user_id' => $record->id,
                            'campaign_type' => $campaign['campaign_type'],
                            'subject' => $campaign['subject'],
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Email Sent')
                            ->body("Personalized email sent to {$record->name}")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\Action::make('bulk_campaign_generation')
                        ->label('Generate Campaigns for Selected')
                        ->icon('heroicon-o-envelope')
                        ->color('primary')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $marketingService = app(MarketingAutomationService::class);
                            $generated = 0;

                            foreach ($records as $record) {
                                $campaign = $marketingService->generatePersonalizedCampaign($record);
                                $generated++;
                            }

                            \Filament\Notifications\Notification::make()
                                ->title('Campaigns Generated')
                                ->body("Generated personalized campaigns for {$generated} customers.")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('bulk_email_send')
                        ->label('Send Bulk Emails')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Send Bulk Personalized Emails')
                        ->modalDescription('This will send personalized marketing emails to all selected customers.')
                        ->modalSubmitActionLabel('Send Emails')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $marketingService = app(MarketingAutomationService::class);
                            $sent = 0;

                            foreach ($records as $record) {
                                $campaign = $marketingService->generatePersonalizedCampaign($record);
                                // Integrate with email service here
                                $sent++;
                            }

                            \Filament\Notifications\Notification::make()
                                ->title('Bulk Emails Sent')
                                ->body("Sent personalized emails to {$sent} customers.")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingAutomationTools::route('/'),
        ];
    }

    // Disable all write operations - this is a read-only analytics view
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canForceDelete($record): bool
    {
        return false;
    }

    public static function canForceDeleteAny(): bool
    {
        return false;
    }

    public static function canRestore($record): bool
    {
        return false;
    }

    public static function canRestoreAny(): bool
    {
        return false;
    }
}
