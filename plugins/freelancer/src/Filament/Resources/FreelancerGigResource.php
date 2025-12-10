<?php
namespace Plugins\Freelancer\Filament\Resources;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Plugins\Freelancer\Models\FreelancerGig;

class FreelancerGigResource extends Resource
{
    protected static ?string $model = FreelancerGig::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Freelancer';
    protected static ?string $navigationLabel = 'Freelancer Gigs';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('freelancer_profile_id')->relationship('profile', 'title')->required()->searchable(),
            Forms\Components\TextInput::make('title')->required()->label('Titlu Serviciu'),
            Forms\Components\Textarea::make('description')->rows(5)->label('Descriere'),
            Forms\Components\TextInput::make('category')->required()->label('Categorie'),
            Forms\Components\TagsInput::make('tags')->label('Tag-uri'),
            Forms\Components\TextInput::make('price')->numeric()->prefix('$')->required()->label('Preț'),
            Forms\Components\TextInput::make('delivery_days')->numeric()->required()->suffix('zile')->label('Termen Livrare'),
            Forms\Components\FileUpload::make('images')->multiple()->image()->label('Imagini'),
            Forms\Components\TextInput::make('revisions')->numeric()->default(1)->label('Revizii Incluse'),
            Forms\Components\Toggle::make('is_active')->default(true)->label('Activ'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('profile.user.name')->searchable()->label('Freelancer'),
            Tables\Columns\TextColumn::make('title')->searchable()->label('Serviciu')->limit(30),
            Tables\Columns\TextColumn::make('category')->badge()->label('Categorie'),
            Tables\Columns\TextColumn::make('price')->money('USD')->label('Preț'),
            Tables\Columns\TextColumn::make('delivery_days')->suffix(' zile')->label('Livrare'),
            Tables\Columns\TextColumn::make('total_orders')->label('Comenzi'),
            Tables\Columns\IconColumn::make('is_active')->boolean()->label('Activ'),
        ])->actions([Tables\Actions\EditAction::make()])
          ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Plugins\Freelancer\Filament\Resources\FreelancerGigResource\Pages\ListFreelancerGigs::route('/'),
            'create' => \Plugins\Freelancer\Filament\Resources\FreelancerGigResource\Pages\CreateFreelancerGig::route('/create'),
            'edit' => \Plugins\Freelancer\Filament\Resources\FreelancerGigResource\Pages\EditFreelancerGig::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'freelancer')->where('is_active', true)->exists();
    }
}
