<?php

namespace Plugins\Freelancer\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Plugins\Freelancer\Models\FreelancerProfile;

class FreelancerProfileResource extends Resource
{
    protected static ?string $model = FreelancerProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Freelancer';
    protected static ?string $navigationLabel = 'Freelancer Profiles';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->required()->searchable(),
            Forms\Components\TextInput::make('title')->required()->label('Titlu Profesional'),
            Forms\Components\Textarea::make('description')->rows(4)->label('Descriere'),
            Forms\Components\TagsInput::make('skills')->label('Competențe')->suggestions(['PHP', 'Laravel', 'JavaScript', 'React', 'Vue', 'Design', 'WordPress']),
            Forms\Components\TextInput::make('hourly_rate')->numeric()->prefix('$')->label('Tarif/Oră'),
            Forms\Components\TextInput::make('portfolio_url')->url()->label('URL Portfolio'),
            Forms\Components\FileUpload::make('avatar')->image()->label('Avatar'),
            Forms\Components\TextInput::make('rating')->numeric()->disabled()->label('Rating'),
            Forms\Components\TextInput::make('total_orders')->numeric()->disabled()->label('Total Comenzi'),
            Forms\Components\TextInput::make('total_earnings')->numeric()->disabled()->prefix('$')->label('Câștiguri Totale'),
            Forms\Components\Toggle::make('is_available')->default(true)->label('Disponibil'),
            Forms\Components\Toggle::make('is_verified')->default(false)->label('Verificat'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('avatar')->circular(),
            Tables\Columns\TextColumn::make('user.name')->searchable()->label('Nume')->weight('bold'),
            Tables\Columns\TextColumn::make('title')->searchable()->label('Titlu'),
            Tables\Columns\TextColumn::make('hourly_rate')->money('USD')->label('$/Oră'),
            Tables\Columns\TextColumn::make('rating')->badge()->color('warning')->label('Rating'),
            Tables\Columns\TextColumn::make('total_orders')->label('Comenzi'),
            Tables\Columns\IconColumn::make('is_available')->boolean()->label('Disponibil'),
            Tables\Columns\IconColumn::make('is_verified')->boolean()->label('Verificat'),
        ])->actions([Tables\Actions\EditAction::make()])
          ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Plugins\Freelancer\Filament\Resources\FreelancerProfileResource\Pages\ListFreelancerProfiles::route('/'),
            'create' => \Plugins\Freelancer\Filament\Resources\FreelancerProfileResource\Pages\CreateFreelancerProfile::route('/create'),
            'edit' => \Plugins\Freelancer\Filament\Resources\FreelancerProfileResource\Pages\EditFreelancerProfile::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'freelancer')->where('is_active', true)->exists();
    }
}
