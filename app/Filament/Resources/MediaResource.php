<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Content;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class MediaResource extends Resource


{
    public static function getNavigationGroup(): ?string
    {
        return __('CMS');
    }

    public static function getNavigationLabel(): string
    {
        return __('Media');
    }

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $cluster = Content::class;
        protected static ?int $navigationSort = 1;
    protected static ?string $model = Media::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file')
                    ->label('Upload File')
                    ->image()
                    ->disk('public')
                    ->directory('media')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->maxSize(10240)
                    ->required(fn ($operation) => $operation === 'create')
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('name', pathinfo($state->getClientOriginalName(), PATHINFO_FILENAME));
                            $set('file_name', $state->getClientOriginalName());
                        }
                    }),
                    
                Forms\Components\TextInput::make('name')
                                        ->label(__('Name'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('alt_text')
                    ->label('Alt Text')
                    ->helperText('Describe the image for accessibility and SEO')
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('caption')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->size(60),
                    
                Tables\Columns\TextColumn::make('name')
                                        ->label(__('Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('mime_type')
                                       ->label(__('Type'))
                    ->label('Type')
                    ->badge()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('size')
                                        ->label(__('Size'))
                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 2) . ' KB')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('width')
                    ->label('Dimensions')
                    ->formatStateUsing(fn ($record) => $record->width && $record->height ? "{$record->width}×{$record->height}" : 'N/A'),
                    
                Tables\Columns\TextColumn::make('user.name')
                                       ->label(__('Author'))
                    ->label('Uploaded By')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                                       ->label(__('Created At'))
                    ->label('Uploaded')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'document' => 'Document',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Media $record) => Storage::disk('public')->url($record->file_path))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Media $record) {
                        if (Storage::disk('public')->exists($record->file_path)) {
                            Storage::disk('public')->delete($record->file_path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function ($records) {
                            foreach ($records as $record) {
                                if (Storage::disk('public')->exists($record->file_path)) {
                                    Storage::disk('public')->delete($record->file_path);
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
