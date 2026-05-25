<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = '🛍️ Orders';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                TextInput::make('order_number')->required()->disabled(),
                Select::make('order_status')->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                    'returned' => 'Returned',
                ])->required(),
                Select::make('payment_status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'refunded' => 'Refunded',
                ])->required(),
                TextInput::make('customer_name')->columnSpan(2),
                TextInput::make('customer_email')->email()->columnSpan(1),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('order_number')->label('Order #')->searchable()->sortable(),
            TextColumn::make('customer_name')->label('Customer')->searchable()->sortable()->limit(30),
            TextColumn::make('total')->label('Total')->money('usd')->sortable(),
            TextColumn::make('payment_method')->label('Payment Method')->sortable(),
            TextColumn::make('payment_status')->label('Payment')->badge()->color(fn (string $state): string => match ($state) {
                'failed' => 'danger',
                'pending' => 'warning',
                'paid' => 'success',
                'refunded' => 'gray',
                default => 'primary',
            })->sortable(),
            TextColumn::make('order_status')->label('Status')->sortable()->badge()->color(fn (string $state): string => match ($state) {
                'pending' => 'warning',
                'processing' => 'primary',
                'shipped' => 'info',
                'delivered' => 'success',
                'cancelled' => 'danger',
                default => 'primary',
            }),
            TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('order_status')->options([
                'pending' => 'Pending',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ]),
            Tables\Filters\SelectFilter::make('payment_status')->options([
                'pending' => 'Pending',
                'paid' => 'Paid',
                'failed' => 'Failed',
                'refunded' => 'Refunded',
            ]),
        ])->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
