<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserGroupResource\Pages;
use App\Models\UserGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserGroupResource extends Resource
{
    protected static ?string $model = UserGroup::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Users & Permissions';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('User Groups');
    }

    public static function getModelLabel(): string
    {
        return __('User Group');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('Group Information'))
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Group Name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->label(__('Slug'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->label(__('Description'))
                        ->rows(2),
                    Forms\Components\Toggle::make('is_active')
                        ->label(__('Active'))
                        ->default(true),
                ])->columns(2),
            
            Forms\Components\Section::make(__('Permissions'))
                ->description(__('Select what this group can access'))
                ->schema([
                    Forms\Components\CheckboxList::make('permissions')
                        ->label(__('Available Permissions'))
                        ->options([
                            // CMS
                            'pages.view' => __('View Pages'),
                            'pages.create' => __('Create Pages'),
                            'pages.edit' => __('Edit Pages'),
                            'pages.delete' => __('Delete Pages'),
                            // Blog
                            'posts.view' => __('View Posts'),
                            'posts.create' => __('Create Posts'),
                            'posts.edit' => __('Edit Posts'),
                            'posts.delete' => __('Delete Posts'),
                            // Shop
                            'products.view' => __('View Products'),
                            'products.create' => __('Create Products'),
                            'products.edit' => __('Edit Products'),
                            'products.delete' => __('Delete Products'),
                            'orders.view' => __('View Orders'),
                            'orders.manage' => __('Manage Orders'),
                            // Media
                            'media.view' => __('View Media'),
                            'media.upload' => __('Upload Media'),
                            'media.delete' => __('Delete Media'),
                            // Users
                            'users.view' => __('View Users'),
                            'users.create' => __('Create Users'),
                            'users.edit' => __('Edit Users'),
                            'users.delete' => __('Delete Users'),
                            // Settings
                            'settings.view' => __('View Settings'),
                            'settings.edit' => __('Edit Settings'),
                            // Support
                            'support.view' => __('View Support Chats'),
                            'support.respond' => __('Respond to Support'),
                            // All
                            '*' => __('Full Access (Admin)'),
                        ])
                        ->columns(3),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('users_count')
                    ->label(__('Users'))
                    ->counts('users')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserGroups::route('/'),
            'create' => Pages\CreateUserGroup::route('/create'),
            'edit' => Pages\EditUserGroup::route('/{record}/edit'),
        ];
    }
}
