<?php

namespace Plugins\Marketing\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Marketing;
use Plugins\Marketing\Models\MarketingTemplate;

class MarketingTemplateResource extends Resource
{
    protected static ?string $model = MarketingTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $cluster = Marketing::class;
    protected static ?string $navigationLabel = 'Email Templates';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Template Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->label('Active'),
                ])->columns(2),

            Forms\Components\Section::make('Email Content')
                ->schema([
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Use {{company_name}}, {{contact_name}} for personalization'),
                    Forms\Components\RichEditor::make('body_html')
                        ->label('Email Body (HTML)')
                        ->required()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'link', 'bulletList', 'orderedList',
                            'h2', 'h3', 'blockquote',
                            'redo', 'undo',
                        ])
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('body_text')
                        ->label('Plain Text Version')
                        ->rows(5)
                        ->helperText('Optional: Auto-generated from HTML if empty')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Available Variables')
                ->schema([
                    Forms\Components\Placeholder::make('variables_help')
                        ->content(fn () => collect(MarketingTemplate::getAvailableVariables())
                            ->map(fn ($desc, $var) => "<code>{$var}</code> - {$desc}")
                            ->join('<br>'))
                        ->columnSpanFull(),
                ])
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (MarketingTemplate $record) {
                        $new = $record->replicate();
                        $new->name = $record->name . ' (Copy)';
                        $new->save();
                    }),
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
            'index' => \Plugins\Marketing\Filament\Resources\MarketingTemplateResource\Pages\ListMarketingTemplates::route('/'),
            'create' => \Plugins\Marketing\Filament\Resources\MarketingTemplateResource\Pages\CreateMarketingTemplate::route('/create'),
            'edit' => \Plugins\Marketing\Filament\Resources\MarketingTemplateResource\Pages\EditMarketingTemplate::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'marketing')->where('is_active', true)->exists();
    }
}
