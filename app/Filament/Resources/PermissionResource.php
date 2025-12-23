<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\UsersPermissions;

use App\Filament\Resources\PermissionResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $cluster = UsersPermissions::class;
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('Permissions');
    }

    public static function getModelLabel(): string
    {
        return __('Permission');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Permission Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Permission Name'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText(__('e.g., edit users, delete posts, view dashboard')),
                        
                        Forms\Components\TextInput::make('guard_name')
                            ->label(__('Guard'))
                            ->default('web')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make(__('Assign to Roles'))
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->label(__('Select Roles'))
                            ->relationship('roles', 'name')
                            ->columns(3)
                            ->gridDirection('row')
                            ->bulkToggleable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Permission Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('guard_name')
                    ->label(__('Guard'))
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('roles_count')
                    ->label(__('Roles'))
                    ->counts('roles')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->label(__('Guard'))
                    ->options([
                        'web' => 'Web',
                        'api' => 'API',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
