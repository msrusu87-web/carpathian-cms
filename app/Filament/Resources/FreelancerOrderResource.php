<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FreelancerOrderResource\Pages;
use Plugins\Freelancer\Models\FreelancerOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FreelancerOrderResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('Freelancer');
    }
    protected static ?string $model = FreelancerOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Freelancer';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detalii Comandă')->schema([
                Forms\Components\Select::make('buyer_id')
                    ->relationship('buyer', 'name')
                    ->required()
                    ->searchable()
                    ->label('Cumpărător'),
                Forms\Components\Select::make('seller_id')
                    ->relationship('seller', 'name')
                    ->required()
                    ->searchable()
                    ->label('Vânzător (Freelancer)'),
                Forms\Components\TextInput::make('gig_title')
                    ->required()
                    ->maxLength(255)
                    ->label('Titlu Serviciu'),
                Forms\Components\Textarea::make('gig_description')
                    ->rows(3)
                    ->label('Descriere'),
            ]),
            Forms\Components\Section::make('Financiar')->schema([
                Forms\Components\TextInput::make('price')
                                        ->label(__('Price'))
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Preț'),
                Forms\Components\TextInput::make('delivery_time')
                                        ->label(__('Delivery Time'))
                    ->required()
                    ->numeric()
                    ->suffix('zile')
                    ->label('Termen Livrare'),
            ]),
            Forms\Components\Section::make('Status & Evaluare')->schema([
                Forms\Components\Select::make('status')
                                        ->label(__('Status'))
                    ->options([
                        'pending' => 'În Așteptare',
                        'in_progress' => 'În Lucru',
                        'delivered' => 'Livrat',
                        'completed' => 'Finalizat',
                        'cancelled' => 'Anulat',
                        'disputed' => 'Disputat',
                    ])
                    ->required()
                    ->label('Status'),
                Forms\Components\Textarea::make('buyer_review')
                    ->rows(3)
                    ->label('Review Cumpărător'),
                Forms\Components\TextInput::make('buyer_rating')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->label('Rating Cumpărător'),
                Forms\Components\DateTimePicker::make('completed_at')
                    ->label('Data Finalizare'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
            Tables\Columns\TextColumn::make('buyer.name')
                ->searchable()
                ->label('Cumpărător'),
            Tables\Columns\TextColumn::make('seller.name')
                ->searchable()
                ->label('Freelancer'),
            Tables\Columns\TextColumn::make('gig_title')
                ->searchable()
                ->label('Serviciu')
                ->limit(30),
            Tables\Columns\TextColumn::make('price')
                                    ->label(__('Price'))
                ->money('USD')
                ->sortable()
                ->label('Preț'),
            Tables\Columns\BadgeColumn::make('status')
                                   ->label(__('Status'))
                ->label('Status')
                ->colors([
                    'warning' => 'pending',
                    'primary' => 'in_progress',
                    'info' => 'delivered',
                    'success' => 'completed',
                    'danger' => fn ($state) => in_array($state, ['cancelled', 'disputed']),
                ]),
            Tables\Columns\TextColumn::make('delivery_time')
                                    ->label(__('Delivery Time'))
                ->suffix(' zile')
                ->label('Livrare'),
            Tables\Columns\TextColumn::make('created_at')
                                    ->label(__('Created At'))
                ->dateTime()
                ->sortable()
                ->since()
                ->label('Data'),
        ])->defaultSort('created_at', 'desc')
          ->actions([
              Tables\Actions\ViewAction::make(),
              Tables\Actions\EditAction::make(),
          ])->bulkActions([
              Tables\Actions\BulkActionGroup::make([
                  Tables\Actions\DeleteBulkAction::make(),
              ]),
          ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFreelancerOrders::route('/'),
            'create' => Pages\CreateFreelancerOrder::route('/create'),
            'edit' => Pages\EditFreelancerOrder::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'freelancer')->where('is_active', true)->exists();
    }
}
