<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FreelancerProfileResource\Pages;
use Plugins\Freelancer\Models\FreelancerProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FreelancerProfileResource extends Resource
{
    protected static ?string $model = FreelancerProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Freelancer';
    protected static ?string $navigationLabel = 'Profiluri Freelancer';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informații Profil')->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->label('Utilizator'),
                Forms\Components\Textarea::make('bio')
                    ->rows(4)
                    ->label('Biografie'),
                Forms\Components\TextInput::make('tagline')
                    ->maxLength(255)
                    ->label('Slogan'),
                Forms\Components\TagsInput::make('skills')
                    ->label('Competențe')
                    ->placeholder('Adaugă competențe'),
                Forms\Components\TagsInput::make('languages')
                    ->label('Limbi Vorbite'),
                Forms\Components\Textarea::make('certifications')
                    ->label('Certificări'),
            ]),
            Forms\Components\Section::make('Disponibilitate & Tarife')->schema([
                Forms\Components\Select::make('availability')
                    ->options([
                        'full-time' => 'Full Time',
                        'part-time' => 'Part Time',
                        'as-needed' => 'La cerere',
                    ])
                    ->label('Disponibilitate'),
                Forms\Components\TextInput::make('hourly_rate')
                    ->numeric()
                    ->prefix('$')
                    ->label('Tarif/Oră'),
                Forms\Components\TextInput::make('response_time')
                    ->numeric()
                    ->suffix('ore')
                    ->label('Timp Răspuns'),
            ]),
            Forms\Components\Section::make('Statistici')->schema([
                Forms\Components\TextInput::make('total_earnings')
                    ->numeric()
                    ->prefix('$')
                    ->disabled()
                    ->label('Total Câștiguri'),
                Forms\Components\TextInput::make('completed_projects')
                    ->numeric()
                    ->disabled()
                    ->label('Proiecte Finalizate'),
                Forms\Components\TextInput::make('rating')
                    ->numeric()
                    ->disabled()
                    ->label('Rating'),
                Forms\Components\Toggle::make('is_verified')
                    ->label('Verificat'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->searchable()
                ->label('Nume')
                ->weight('bold'),
            Tables\Columns\TextColumn::make('tagline')
                ->searchable()
                ->label('Slogan')
                ->limit(40),
            Tables\Columns\TextColumn::make('hourly_rate')
                ->money('USD')
                ->label('$/Oră'),
            Tables\Columns\TextColumn::make('completed_projects')
                ->label('Proiecte')
                ->badge()
                ->color('success'),
            Tables\Columns\TextColumn::make('rating')
                ->label('Rating')
                ->badge()
                ->color('warning'),
            Tables\Columns\IconColumn::make('is_verified')
                ->boolean()
                ->label('Verificat'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFreelancerProfiles::route('/'),
            'create' => Pages\CreateFreelancerProfile::route('/create'),
            'edit' => Pages\EditFreelancerProfile::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'freelancer')->where('is_active', true)->exists();
    }
}
