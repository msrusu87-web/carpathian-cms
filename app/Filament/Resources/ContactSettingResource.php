<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSettingResource\Pages;
use App\Models\ContactSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactSettingResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('Contact Settings');
    }

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    
    protected static ?int $navigationSort = 2;
    protected static ?string $model = ContactSetting::class;
    protected static ?string $navigationGroup = 'Settings';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                                                ->label(__('Email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                                                ->label(__('Phone'))
                            ->tel()
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zip_code')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('country')
                            ->maxLength(255),
                    ])->columns(4),

                Forms\Components\Section::make('Social Media')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Settings')
                    ->schema([
                        Forms\Components\Textarea::make('working_hours')
                            ->label('Working Hours')
                            ->rows(3)
                            ->placeholder('Monday - Friday: 9:00 AM - 6:00 PM')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('map_embed')
                            ->label('Google Maps Embed Code')
                            ->rows(3)
                            ->placeholder('<iframe src="..."></iframe>')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('receive_emails')
                            ->label('Receive Contact Form Emails')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                                        ->label(__('Email'))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                                        ->label(__('Phone'))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\IconColumn::make('receive_emails')
                    ->label('Emails')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                                       ->label(__('Updated At'))
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('messages.settings');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContactSettings::route('/'),
        ];
    }
}
