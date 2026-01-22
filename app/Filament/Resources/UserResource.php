<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\UsersPermissions;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $cluster = UsersPermissions::class;
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('User Information'))
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label(__('Email'))
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->label(__('Password'))
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create'),
                ])->columns(2),
            
            Forms\Components\Section::make('Personal Information')
                ->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->label('First Name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->label('Last Name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('Phone')
                        ->tel()
                        ->maxLength(50),
                ])->columns(3),

            Forms\Components\Section::make('Company Information')
                ->schema([
                    Forms\Components\TextInput::make('company_name')
                        ->label('Company Name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('company_reg_number')
                        ->label('Company Registration (CUI)')
                        ->maxLength(100),
                    Forms\Components\TextInput::make('vat_number')
                        ->label('VAT Number')
                        ->maxLength(100),
                ])->columns(3)->collapsible(),

            Forms\Components\Section::make('Billing Address')
                ->schema([
                    Forms\Components\TextInput::make('billing_address')
                        ->label('Address')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('billing_city')
                        ->label('City')
                        ->maxLength(100),
                    Forms\Components\TextInput::make('billing_postal_code')
                        ->label('Postal Code')
                        ->maxLength(20),
                    Forms\Components\TextInput::make('billing_country')
                        ->label('Country')
                        ->maxLength(100)
                        ->default('România'),
                ])->columns(2),
            
            Forms\Components\Section::make(__('Roles & Groups'))
                ->schema([
                    Forms\Components\Select::make('roles')
                        ->label(__('Roles'))
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->preload(),
                    Forms\Components\Select::make('userGroups')
                        ->label(__('User Groups'))
                        ->multiple()
                        ->relationship('userGroups', 'name')
                        ->preload(),
                ])->columns(2),
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
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Company')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('billing_city')
                    ->label('City')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('Roles'))
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('userGroups.name')
                    ->label(__('Groups'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\Filter::make('has_company')
                    ->label('Has Company')
                    ->query(fn ($query) => $query->whereNotNull('company_name')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
