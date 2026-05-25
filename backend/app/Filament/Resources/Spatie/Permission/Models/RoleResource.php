<?php

// 1. CORRECT NAMESPACE FOR YOUR FILE LOCATION
namespace App\Filament\Resources\Spatie\Permission\Models;

// 2. CORRECT 'USE' FOR THE PAGES DIRECTORY
use App\Filament\Resources\Spatie\Permission\Models\RoleResource\Pages;
// Note: We don't need RelationManagers yet, so I've removed that 'use' statement for now.

// 3. CORRECT 'USE' FOR THE SPATIE MODELS
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission as SpatiePermissionModel; // Using an alias to avoid conflict if you had a local Permission model

// 4. FILAMENT SPECIFIC 'USE' STATEMENTS
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
// We don't need these for now:
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class; // This correctly points to \Spatie\Permission\Models\Role due to the 'use' statement above

    protected static ?string $navigationIcon = 'heroicon-o-finger-print'; // Changed icon
    protected static ?string $navigationGroup = '👥 Admin & Users'; // Consolidated with user admin
    protected static ?string $navigationLabel = 'Roles'; // Explicit label for the sidebar
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true) // Unique role name
                    ->maxLength(255),
                TextInput::make('guard_name')
                    ->default(config('auth.defaults.guard')) // Default to 'web'
                    ->required()
                    ->maxLength(255),
                Select::make('permissions') // Field to manage permissions for the role
                    ->multiple()
                    ->relationship('permissions', 'name') // 'permissions' is the relationship method on Role model, 'name' is the display column on Permission model
                    ->preload() // Preload options for better UX
                    ->searchable()
                    ->label('Permissions'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('guard_name')->sortable(),
                TextColumn::make('permissions_count')->counts('permissions')->label('Permissions Count'), // Show count of permissions
                TextColumn::make('created_at')->dateTime('d-M-Y')->sortable(),
            ])
            ->filters([
                // You can add filters here if needed
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
            // We can add relation managers here later if needed
        ];
    }

    public static function getPages(): array
    {
        // This 'Pages' will correctly resolve to App\Filament\Resources\Spatie\Permission\Models\RoleResource\Pages
        // because of the 'use' statement at the top of the file.
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}