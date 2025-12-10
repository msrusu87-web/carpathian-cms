<?php
namespace Plugins\Freelancer\Filament\Resources;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Plugins\Freelancer\Models\FreelancerOrder;

class FreelancerOrderResource extends Resource
{
    protected static ?string $model = FreelancerOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Freelancer';
    protected static ?string $navigationLabel = 'Freelancer Orders';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('gig_id')->relationship('gig', 'title')->required()->searchable(),
            Forms\Components\Select::make('buyer_id')->relationship('buyer', 'name')->required()->searchable(),
            Forms\Components\Select::make('seller_id')->relationship('seller', 'name')->required()->searchable(),
            Forms\Components\TextInput::make('order_number')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('amount')->numeric()->prefix('$')->required(),
            Forms\Components\Select::make('status')->options([
                'pending' => 'În Așteptare',
                'in_progress' => 'În Lucru',
                'delivered' => 'Livrat',
                'completed' => 'Finalizat',
                'cancelled' => 'Anulat',
                'disputed' => 'Disputat',
            ])->required(),
            Forms\Components\Textarea::make('requirements')->rows(3)->label('Cerințe'),
            Forms\Components\Textarea::make('delivery_note')->rows(3)->label('Notă Livrare'),
            Forms\Components\FileUpload::make('delivery_files')->multiple()->label('Fișiere Livrare'),
            Forms\Components\DateTimePicker::make('delivered_at')->label('Data Livrare'),
            Forms\Components\DateTimePicker::make('completed_at')->label('Data Finalizare'),
            Forms\Components\TextInput::make('rating')->numeric()->minValue(1)->maxValue(5)->label('Rating'),
            Forms\Components\Textarea::make('review')->rows(3)->label('Review'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('order_number')->searchable()->copyable()->label('Nr. Comandă'),
            Tables\Columns\TextColumn::make('gig.title')->searchable()->label('Serviciu')->limit(25),
            Tables\Columns\TextColumn::make('buyer.name')->searchable()->label('Cumpărător'),
            Tables\Columns\TextColumn::make('seller.name')->searchable()->label('Vânzător'),
            Tables\Columns\TextColumn::make('amount')->money('USD')->label('Sumă'),
            Tables\Columns\BadgeColumn::make('status')->label('Status')->colors([
                'warning' => 'pending',
                'primary' => 'in_progress',
                'info' => 'delivered',
                'success' => 'completed',
                'danger' => fn ($state) => in_array($state, ['cancelled', 'disputed']),
            ]),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Data')->since(),
        ])->actions([Tables\Actions\EditAction::make(), Tables\Actions\ViewAction::make()])
          ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Plugins\Freelancer\Filament\Resources\FreelancerOrderResource\Pages\ListFreelancerOrders::route('/'),
            'create' => \Plugins\Freelancer\Filament\Resources\FreelancerOrderResource\Pages\CreateFreelancerOrder::route('/create'),
            'edit' => \Plugins\Freelancer\Filament\Resources\FreelancerOrderResource\Pages\EditFreelancerOrder::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'freelancer')->where('is_active', true)->exists();
    }
}
