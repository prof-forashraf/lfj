<?php

// 1. CORRECT NAMESPACE FOR YOUR FILE LOCATION
namespace App\Filament\Resources\Spatie\Permission\Models;

// 2. CORRECT 'USE' FOR THE PAGES DIRECTORY
use App\Filament\Resources\Spatie\Permission\Models\PermissionResource\Pages;

// 3. ***** CORRECT 'USE' FOR THE SPATIE PERMISSION MODEL *****
use Spatie\Permission\Models\Permission; // <--- THIS IS THE CRUCIAL FIX

// 4. FILAMENT SPECIFIC 'USE' STATEMENTS
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class PermissionResource extends Resource
{
    // This will now correctly point to \Spatie\Permission\Models\Permission
    // because of the corrected 'use' statement above.
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = '👥 Admin & Users'; // Consolidated with user admin
    protected static ?string $navigationLabel = 'Permissions'; // Explicit label
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('guard_name')
                    ->default(config('auth.defaults.guard'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('guard_name')->sortable(),
                TextColumn::make('created_at')->dateTime('d-M-Y')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Permissions don't usually have relations to manage directly here
        ];
    }

    public static function getPages(): array
    {
        // This 'Pages' will correctly resolve due to the 'use' statement
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}