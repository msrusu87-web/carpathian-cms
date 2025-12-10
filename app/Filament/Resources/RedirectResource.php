<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    
    protected static ?string $navigationGroup = 'SEO & Settings';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Redirect Details')
                    ->schema([
                        Forms\Components\TextInput::make('from_url')
                            ->label('From URL')
                            ->required()
                            ->placeholder('/old-page')
                            ->helperText('The old URL path (e.g., /old-page)')
                            ->maxLength(500)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('to_url')
                            ->label('To URL')
                            ->required()
                            ->placeholder('/new-page')
                            ->helperText('The new URL path or full URL')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('status_code')
                            ->label('Redirect Type')
                            ->required()
                            ->options([
                                301 => '301 - Permanent Redirect',
                                302 => '302 - Temporary Redirect',
                                307 => '307 - Temporary Redirect (Method Preserved)',
                                308 => '308 - Permanent Redirect (Method Preserved)',
                            ])
                            ->default(301)
                            ->helperText('301 for permanent, 302 for temporary redirects'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Enable or disable this redirect'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Optional notes about this redirect')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('hits')
                            ->label('Hit Count')
                            ->disabled()
                            ->default(0),
                        
                        Forms\Components\DateTimePicker::make('last_hit_at')
                            ->label('Last Hit')
                            ->disabled(),
                    ])->columns(2)
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_url')
                    ->label('From URL')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('to_url')
                    ->label('To URL')
                    ->searchable()
                    ->limit(50)
                    ->copyable(),
                
                Tables\Columns\BadgeColumn::make('status_code')
                    ->label('Type')
                    ->colors([
                        'success' => 301,
                        'warning' => 302,
                        'info' => [307, 308],
                    ]),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('hits')
                    ->label('Hits')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('last_hit_at')
                    ->label('Last Hit')
                    ->dateTime()
                    ->sortable()
                    ->since(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                Tables\Filters\SelectFilter::make('status_code')
                    ->label('Redirect Type')
                    ->options([
                        301 => '301 - Permanent',
                        302 => '302 - Temporary',
                        307 => '307 - Temporary (Method Preserved)',
                        308 => '308 - Permanent (Method Preserved)',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('test')
                    ->label('Test')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Redirect $record): string => $record->from_url)
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}
