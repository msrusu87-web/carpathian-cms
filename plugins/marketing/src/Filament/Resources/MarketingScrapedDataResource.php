<?php

namespace Plugins\Marketing\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use App\Filament\Clusters\Marketing;
use Plugins\Marketing\Models\MarketingScrapedData;
use Plugins\Marketing\Models\MarketingList;
use Illuminate\Database\Eloquent\Collection;

class MarketingScrapedDataResource extends Resource
{
    protected static ?string $model = MarketingScrapedData::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $cluster = Marketing::class;
    protected static ?string $navigationLabel = 'Scraped Data';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Source')
                ->schema([
                    Forms\Components\TextInput::make('url')
                        ->disabled(),
                    Forms\Components\TextInput::make('domain')
                        ->disabled(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'processed' => 'Processed',
                            'failed' => 'Failed',
                            'duplicate' => 'Duplicate',
                        ])
                        ->disabled(),
                ])->columns(3),

            Forms\Components\Section::make('Extracted Data')
                ->schema([
                    Forms\Components\KeyValue::make('extracted_data')
                        ->label('Data')
                        ->keyLabel('Field')
                        ->valueLabel('Value'),
                ]),

            Forms\Components\Section::make('Error')
                ->schema([
                    Forms\Components\Textarea::make('error_message')
                        ->disabled()
                        ->rows(3),
                ])
                ->visible(fn (?MarketingScrapedData $record) => !empty($record?->error_message)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('job.name')
                    ->label('Job')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn (MarketingScrapedData $record) => $record->url),
                Tables\Columns\TextColumn::make('extracted_data.company_name')
                    ->label('Company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('extracted_data.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('extracted_data.phone')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'pending',
                        'success' => 'processed',
                        'danger' => 'failed',
                        'warning' => 'duplicate',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processed' => 'Processed',
                        'failed' => 'Failed',
                        'duplicate' => 'Duplicate',
                    ]),
                Tables\Filters\SelectFilter::make('job_id')
                    ->relationship('job', 'name')
                    ->label('Scrape Job'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('createContact')
                    ->label('Create Contact')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn (MarketingScrapedData $record) => 
                        $record->status === 'pending' && !empty($record->extracted_data['email']))
                    ->action(function (MarketingScrapedData $record) {
                        $contact = $record->toContact();
                        if ($contact) {
                            Notification::make()
                                ->title('Contact created')
                                ->body("Created contact: {$contact->email}")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Contact not created')
                                ->body('Duplicate email or missing data')
                                ->warning()
                                ->send();
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('createContacts')
                        ->label('Create Contacts')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Forms\Components\Select::make('list_id')
                                ->label('Add to List (optional)')
                                ->options(MarketingList::pluck('name', 'id')),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $created = 0;
                            $list = !empty($data['list_id']) ? MarketingList::find($data['list_id']) : null;
                            
                            foreach ($records as $record) {
                                if ($record->status !== 'pending') continue;
                                
                                $contact = $record->toContact();
                                if ($contact && $list) {
                                    $list->addContact($contact);
                                }
                                if ($contact) $created++;
                            }
                            
                            Notification::make()
                                ->title("Created {$created} contacts")
                                ->success()
                                ->send();
                        }),
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
            'index' => \Plugins\Marketing\Filament\Resources\MarketingScrapedDataResource\Pages\ListMarketingScrapedData::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'marketing')->where('is_active', true)->exists();
    }
}
