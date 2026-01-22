<?php

namespace Plugins\Marketing\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use App\Filament\Clusters\Marketing;
use Plugins\Marketing\Models\MarketingContact;
use Plugins\Marketing\Models\MarketingList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MarketingContactResource extends Resource
{
    protected static ?string $model = MarketingContact::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $cluster = Marketing::class;
    protected static ?string $navigationLabel = 'Contacts';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Contact Information')
                ->schema([
                    Forms\Components\TextInput::make('company_name')
                        ->label('Company Name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('contact_name')
                        ->label('Contact Person')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(50),
                    Forms\Components\TextInput::make('website')
                        ->url()
                        ->maxLength(255),
                ])->columns(2),
                
            Forms\Components\Section::make('Address')
                ->schema([
                    Forms\Components\TextInput::make('address')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('city')
                        ->maxLength(100),
                    Forms\Components\TextInput::make('country')
                        ->default('Romania')
                        ->maxLength(100),
                ])->columns(3),
                
            Forms\Components\Section::make('Organization')
                ->schema([
                    Forms\Components\Select::make('lists')
                        ->relationship('lists', 'name')
                        ->multiple()
                        ->preload()
                        ->label('Contact Lists'),
                    Forms\Components\TagsInput::make('tags')
                        ->label('Tags')
                        ->suggestions(['prospect', 'lead', 'customer', 'vip', 'cold', 'warm', 'hot']),
                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'unsubscribed' => 'Unsubscribed',
                            'bounced' => 'Bounced',
                            'invalid' => 'Invalid',
                        ])
                        ->default('active')
                        ->required(),
                ])->columns(3),
                
            Forms\Components\Section::make('Consent & Source')
                ->schema([
                    Forms\Components\Toggle::make('has_consent')
                        ->label('Has Marketing Consent')
                        ->helperText('Required for GDPR compliance'),
                    Forms\Components\DateTimePicker::make('consent_date')
                        ->label('Consent Date'),
                    Forms\Components\Select::make('source')
                        ->options([
                            'manual' => 'Manual Entry',
                            'scraper' => 'Web Scraper',
                            'import' => 'CSV Import',
                            'form' => 'Contact Form',
                        ])
                        ->default('manual'),
                    Forms\Components\TextInput::make('source_url')
                        ->url()
                        ->label('Source URL'),
                ])->columns(2),
                
            Forms\Components\Section::make('Notes')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('contact_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->icon('heroicon-o-phone'),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'unsubscribed',
                        'warning' => 'bounced',
                        'gray' => 'invalid',
                    ]),
                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('has_consent')
                    ->boolean()
                    ->label('Consent')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('emails_sent')
                    ->label('Sent')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('emails_opened')
                    ->label('Opened')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_contacted')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'unsubscribed' => 'Unsubscribed',
                        'bounced' => 'Bounced',
                        'invalid' => 'Invalid',
                    ]),
                SelectFilter::make('source')
                    ->options([
                        'manual' => 'Manual',
                        'scraper' => 'Scraper',
                        'import' => 'Import',
                    ]),
                SelectFilter::make('lists')
                    ->relationship('lists', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('has_consent')
                    ->label('Has Consent'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('unsubscribe')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (MarketingContact $record) => $record->status === 'active')
                    ->action(fn (MarketingContact $record) => $record->markAsUnsubscribed('Manual unsubscribe')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('addToList')
                        ->label('Add to List')
                        ->icon('heroicon-o-folder-plus')
                        ->form([
                            Forms\Components\Select::make('list_id')
                                ->label('Select List')
                                ->options(MarketingList::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $list = MarketingList::find($data['list_id']);
                            foreach ($records as $contact) {
                                $list->addContact($contact);
                            }
                            Notification::make()
                                ->title('Added ' . $records->count() . ' contacts to list')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('markActive')
                        ->label('Mark as Active')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'active'])),
                    Tables\Actions\BulkAction::make('markBounced')
                        ->label('Mark as Bounced')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'bounced'])),
                    Tables\Actions\BulkAction::make('markInvalid')
                        ->label('Mark as Invalid')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'invalid'])),
                    Tables\Actions\BulkAction::make('cleanupBounced')
                        ->label('Delete Bounced')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Bounced Contacts')
                        ->modalDescription('This will permanently delete all selected contacts with bounced status. Are you sure?')
                        ->action(function (Collection $records) {
                            $deleted = $records->where('status', 'bounced')->count();
                            $records->where('status', 'bounced')->each->delete();
                            Notification::make()
                                ->title("Deleted {$deleted} bounced contacts")
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            $csv = "Company,Contact,Email,Phone,City,Status\n";
                            foreach ($records as $contact) {
                                $csv .= implode(',', [
                                    '"' . ($contact->company_name ?? '') . '"',
                                    '"' . ($contact->contact_name ?? '') . '"',
                                    $contact->email ?? '',
                                    $contact->phone ?? '',
                                    $contact->city ?? '',
                                    $contact->status,
                                ]) . "\n";
                            }
                            
                            $filename = 'contacts-export-' . now()->format('Y-m-d-His') . '.csv';
                            $path = storage_path('app/public/' . $filename);
                            file_put_contents($path, $csv);
                            
                            Notification::make()
                                ->title('Export complete')
                                ->body('Download: /storage/' . $filename)
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->headerActions([
                Action::make('import')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('CSV File')
                            ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel'])
                            ->required(),
                        Forms\Components\Select::make('list_id')
                            ->label('Add to List (optional)')
                            ->options(MarketingList::pluck('name', 'id')),
                    ])
                    ->action(function (array $data) {
                        $path = storage_path('app/public/' . $data['csv_file']);
                        if (!file_exists($path)) {
                            Notification::make()->title('File not found')->danger()->send();
                            return;
                        }
                        
                        $handle = fopen($path, 'r');
                        $header = fgetcsv($handle);
                        $imported = 0;
                        
                        while (($row = fgetcsv($handle)) !== false) {
                            $rowData = array_combine($header, $row);
                            
                            $email = $rowData['email'] ?? $rowData['Email'] ?? null;
                            if (!$email || MarketingContact::findByEmail($email)) continue;
                            
                            $contact = MarketingContact::create([
                                'email' => $email,
                                'company_name' => $rowData['company_name'] ?? $rowData['Company'] ?? null,
                                'contact_name' => $rowData['contact_name'] ?? $rowData['Contact'] ?? $rowData['Name'] ?? null,
                                'phone' => $rowData['phone'] ?? $rowData['Phone'] ?? null,
                                'city' => $rowData['city'] ?? $rowData['City'] ?? null,
                                'source' => 'import',
                            ]);
                            
                            if (!empty($data['list_id'])) {
                                MarketingList::find($data['list_id'])?->addContact($contact);
                            }
                            
                            $imported++;
                        }
                        
                        fclose($handle);
                        unlink($path);
                        
                        Notification::make()
                            ->title("Imported {$imported} contacts")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Plugins\Marketing\Filament\Resources\MarketingContactResource\Pages\ListMarketingContacts::route('/'),
            'create' => \Plugins\Marketing\Filament\Resources\MarketingContactResource\Pages\CreateMarketingContact::route('/create'),
            'edit' => \Plugins\Marketing\Filament\Resources\MarketingContactResource\Pages\EditMarketingContact::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'marketing')->where('is_active', true)->exists();
    }
}
