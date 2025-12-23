<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Shop;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $cluster = Shop::class;
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Invoice Information')
                ->schema([
                    Forms\Components\TextInput::make('invoice_number')
                        ->label('Invoice Number')
                        ->default(fn () => Invoice::generateInvoiceNumber())
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                    
                    Forms\Components\Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            if ($state) {
                                $user = User::find($state);
                                if ($user) {
                                    // Autopopulate customer billing details
                                    $set('client_name', trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->name);
                                    $set('client_email', $user->email);
                                    $set('client_phone', $user->phone);
                                    $set('client_company', $user->company_name);
                                    $set('client_company_reg', $user->company_reg_number);
                                    $set('client_vat_number', $user->vat_number);
                                    $set('client_address', $user->billing_address);
                                    $set('client_city', $user->billing_city);
                                    $set('client_postal_code', $user->billing_postal_code);
                                    $set('client_country', $user->billing_country);
                                }
                            }
                        }),

                    Forms\Components\Select::make('order_id')
                        ->label('Related Order (Optional)')
                        ->relationship('order', 'id')
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'sent' => 'Sent',
                            'paid' => 'Paid',
                            'overdue' => 'Overdue',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('draft')
                        ->required(),

                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'unpaid' => 'Unpaid',
                            'partial' => 'Partial',
                            'paid' => 'Paid',
                            'refunded' => 'Refunded',
                        ])
                        ->default('unpaid')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Customer Details')
                ->schema([
                    Forms\Components\TextInput::make('client_name')
                        ->label('Client Name')
                        ->required()
                        ->maxLength(255),
                    
                    Forms\Components\TextInput::make('client_email')
                        ->label('Client Email')
                        ->email()
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('client_phone')
                        ->label('Client Phone')
                        ->maxLength(50),

                    Forms\Components\TextInput::make('client_company')
                        ->label('Company Name')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('client_company_reg')
                        ->label('Company Registration (CUI)')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('client_vat_number')
                        ->label('VAT Number')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('client_address')
                        ->label('Address')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('client_city')
                        ->label('City')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('client_postal_code')
                        ->label('Postal Code')
                        ->maxLength(20),

                    Forms\Components\TextInput::make('client_country')
                        ->label('Country')
                        ->default('România')
                        ->maxLength(100),
                ])->columns(3)->collapsible(),

            Forms\Components\Section::make('Invoice Items')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Forms\Components\TextInput::make('description')
                                ->label('Description')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->default(1)
                                ->minValue(0.01)
                                ->step(0.01)
                                ->required()
                                ->reactive(),

                            Forms\Components\TextInput::make('unit_price')
                                ->label('Unit Price (RON)')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->step(0.01)
                                ->required()
                                ->reactive(),

                            Forms\Components\TextInput::make('tax_rate')
                                ->label('Tax Rate (%)')
                                ->numeric()
                                ->default(19)
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(0.01)
                                ->required()
                                ->suffix('%'),

                            Forms\Components\Placeholder::make('item_total')
                                ->label('Total')
                                ->content(function (Get $get): string {
                                    $quantity = (float) ($get('quantity') ?? 0);
                                    $unitPrice = (float) ($get('unit_price') ?? 0);
                                    $taxRate = (float) ($get('tax_rate') ?? 0);
                                    
                                    $subtotal = $quantity * $unitPrice;
                                    $tax = $subtotal * ($taxRate / 100);
                                    $total = $subtotal + $tax;
                                    
                                    return number_format($total, 2) . ' RON';
                                }),
                        ])
                        ->columns(5)
                        ->defaultItems(1)
                        ->addActionLabel('Add Item')
                        ->reorderableWithButtons()
                        ->collapsible(),
                ]),

            Forms\Components\Section::make('Dates & Amounts')
                ->schema([
                    Forms\Components\DatePicker::make('invoice_date')
                        ->label('Invoice Date')
                        ->default(now())
                        ->required(),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Due Date')
                        ->default(now()->addDays(30))
                        ->required(),

                    Forms\Components\TextInput::make('tax_rate')
                        ->label('Default Tax Rate (%)')
                        ->numeric()
                        ->default(19)
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01)
                        ->suffix('%'),

                    Forms\Components\TextInput::make('discount_amount')
                        ->label('Discount Amount (RON)')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->step(0.01)
                        ->prefix('RON'),

                    Forms\Components\DateTimePicker::make('paid_at')
                        ->label('Paid At')
                        ->hidden(fn (Get $get) => $get('payment_status') !== 'paid'),

                    Forms\Components\TextInput::make('payment_method')
                        ->label('Payment Method')
                        ->maxLength(100)
                        ->hidden(fn (Get $get) => $get('payment_status') !== 'paid'),

                    Forms\Components\TextInput::make('payment_reference')
                        ->label('Payment Reference')
                        ->maxLength(255)
                        ->hidden(fn (Get $get) => $get('payment_status') !== 'paid'),
                ])->columns(3),

            Forms\Components\Section::make('Additional Information')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notes')
                        ->rows(3),

                    Forms\Components\Textarea::make('terms')
                        ->label('Terms & Conditions')
                        ->default('Plata se va efectua în termen de 30 de zile de la data emiterii facturii.')
                        ->rows(3),

                    Forms\Components\TextInput::make('footer_text')
                        ->label('Footer Text')
                        ->default('Mulțumim pentru colaborare! | www.carphatian.ro')
                        ->maxLength(255),
                ])->columns(1)->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('client_company')
                    ->label('Company')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('invoice_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('RON')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'primary' => 'sent',
                        'success' => 'paid',
                        'warning' => 'overdue',
                        'danger' => 'cancelled',
                    ]),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'danger' => 'unpaid',
                        'warning' => 'partial',
                        'success' => 'paid',
                        'secondary' => 'refunded',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('invoice_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Invoice $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo Pdf::loadView('invoices.pdf', ['invoice' => $record])
                                ->stream();
                        }, $record->invoice_number . '.pdf');
                    }),
                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn (Invoice $record) => $record->payment_status === 'paid')
                    ->requiresConfirmation()
                    ->action(fn (Invoice $record) => $record->markAsPaid()),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
