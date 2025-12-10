<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LanguageResource\Pages;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationGroup = 'Setări';
    protected static ?string $navigationLabel = 'Limbi';
    protected static ?string $pluralModelLabel = 'Limbi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informații Limbă')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nume')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Română, English'),
                        
                        Forms\Components\TextInput::make('code')
                            ->label('Cod Limbă')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ex: ro, en')
                            ->helperText('Codul ISO 639-1 al limbii (2 caractere)'),
                        
                        Forms\Components\TextInput::make('locale')
                            ->label('Locale')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('Ex: ro_RO, en_US')
                            ->helperText('Codul locale complet (ex: ro_RO, en_US)'),
                        
                        Forms\Components\Select::make('direction')
                            ->label('Direcție Text')
                            ->options([
                                'ltr' => 'Stânga-Dreapta (LTR)',
                                'rtl' => 'Dreapta-Stânga (RTL)',
                            ])
                            ->default('ltr')
                            ->required(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Setări')
                    ->schema([
                        Forms\Components\Toggle::make('is_default')
                            ->label('Limbă Implicită')
                            ->helperText('Doar o limbă poate fi setată ca implicită')
                            ->default(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    // When setting as default, ensure is_active is true
                                    $set('is_active', true);
                                }
                            }),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activă')
                            ->helperText('Doar limbile active sunt disponibile pe site')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nume')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('code')
                    ->label('Cod')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('locale')
                    ->label('Locale')
                    ->badge()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('direction')
                    ->label('Direcție')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->color(fn (string $state): string => $state === 'rtl' ? 'warning' : 'success'),
                
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Implicită')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activă')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creată la')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Limbă Implicită'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activă'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('set_default')
                    ->label('Setează Implicită')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->hidden(fn (Language $record) => $record->is_default)
                    ->requiresConfirmation()
                    ->action(function (Language $record) {
                        // Remove default from all other languages
                        Language::where('is_default', true)->update(['is_default' => false]);
                        // Set this as default
                        $record->update(['is_default' => true, 'is_active' => true]);
                    })
                    ->successNotificationTitle('Limba implicită a fost actualizată'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            // Prevent deleting default language
                            $defaultLanguage = $records->firstWhere('is_default', true);
                            if ($defaultLanguage) {
                                throw new \Exception('Nu puteți șterge limba implicită!');
                            }
                            $records->each->delete();
                        }),
                ]),
            ])
            ->defaultSort('is_default', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLanguages::route('/'),
            'create' => Pages\CreateLanguage::route('/create'),
            'edit' => Pages\EditLanguage::route('/{record}/edit'),
        ];
    }
}
