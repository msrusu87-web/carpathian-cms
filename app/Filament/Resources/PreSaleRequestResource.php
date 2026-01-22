<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Shop;

use App\Filament\Resources\PreSaleRequestResource\Pages;
use App\Models\PreSaleRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class PreSaleRequestResource extends Resource
{
    protected static ?string $model = PreSaleRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $cluster = Shop::class;
    
    protected static ?string $navigationLabel = 'Cereri Pre-Comandă';
    
    protected static ?string $modelLabel = 'Cerere Pre-Comandă';
    
    protected static ?string $pluralModelLabel = 'Cereri Pre-Comandă';
    
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informații Client')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nume')
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->disabled(),
                    ])
                    ->columns(3),
                    
                Forms\Components\Section::make('Produs')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Produs')
                            ->relationship('product', 'name')
                            ->disabled(),
                    ]),
                    
                Forms\Components\Section::make('Mesaj Client')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('Mesaj')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Administrare')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'În așteptare',
                                'contacted' => 'Contactat',
                                'responded' => 'Răspuns primit',
                                'closed' => 'Închis',
                            ])
                            ->required(),
                        Forms\Components\DateTimePicker::make('responded_at')
                            ->label('Data răspunsului'),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Note administrare')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produs')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nume Client')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->copyable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'contacted',
                        'success' => 'responded',
                        'secondary' => 'closed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'În așteptare',
                        'contacted' => 'Contactat',
                        'responded' => 'Răspuns primit',
                        'closed' => 'Închis',
                        default => $state,
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'În așteptare',
                        'contacted' => 'Contactat',
                        'responded' => 'Răspuns primit',
                        'closed' => 'Închis',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('markContacted')
                    ->label('Contactat')
                    ->icon('heroicon-o-phone')
                    ->color('info')
                    ->action(function (PreSaleRequest $record) {
                        $record->update(['status' => 'contacted']);
                        Notification::make()
                            ->title('Status actualizat')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PreSaleRequest $record) => $record->status === 'pending'),
                Tables\Actions\Action::make('sendEmail')
                    ->label('Trimite Email')
                    ->icon('heroicon-o-envelope')
                    ->url(fn (PreSaleRequest $record) => "mailto:{$record->email}?subject=Re: Pre-comandă {$record->product->name}")
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPreSaleRequests::route('/'),
            'edit' => Pages\EditPreSaleRequest::route('/{record}/edit'),
        ];
    }
}
