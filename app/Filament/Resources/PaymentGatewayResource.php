<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentGatewayResource\Pages;
use App\Models\PaymentGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('Shop');
    }

    public static function getNavigationLabel(): string
    {
        return __('Gateway-uri de plată');
    }

    public static function getModelLabel(): string
    {
        return __('Gateway de plată');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Gateway-uri de plată');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Gateway Configuration')
                    ->tabs([
                        // TAB 1: Informații de bază
                        Forms\Components\Tabs\Tab::make('Informații de bază')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nume Gateway')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ex: Stripe Checkout'),
                                
                                Forms\Components\Select::make('provider')
                                    ->label('Provider')
                                    ->required()
                                    ->options([
                                        'stripe' => 'Stripe',
                                        'paypal' => 'PayPal',
                                        'euplatesc' => 'EuPlatesc',
                                        'netopia' => 'Netopia (MobilPay)',
                                        'bank_transfer' => 'Transfer Bancar',
                                    ])
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => 
                                        $set('slug', \Str::slug($state))
                                    ),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Gateway Activ')
                                            ->helperText('Activează pentru a permite plăți')
                                            ->default(false)
                                            ->inline(false),

                                        Forms\Components\Toggle::make('test_mode')
                                            ->label('Mod Test')
                                            ->helperText('Folosește credențiale de test')
                                            ->default(true)
                                            ->inline(false),
                                    ]),
                            ]),

                        // TAB 2: Credențiale
                        Forms\Components\Tabs\Tab::make('Credențiale')
                            ->icon('heroicon-o-key')
                            ->schema([
                                // STRIPE
                                Forms\Components\Section::make('Stripe API Keys')
                                    ->description('Găsești aceste chei în Stripe Dashboard → Developers → API keys')
                                    ->icon('heroicon-o-credit-card')
                                    ->schema([
                                        Forms\Components\TextInput::make('credentials.test_secret_key')
                                            ->label('Test Secret Key')
                                            ->placeholder('sk_test_...')
                                            ->password()
                                            ->revealable()
                                            ->helperText('Cheia secretă pentru modul test'),

                                        Forms\Components\TextInput::make('credentials.test_publishable_key')
                                            ->label('Test Publishable Key')
                                            ->placeholder('pk_test_...')
                                            ->helperText('Cheia publică pentru modul test'),

                                        Forms\Components\TextInput::make('credentials.live_secret_key')
                                            ->label('Live Secret Key')
                                            ->placeholder('sk_live_...')
                                            ->password()
                                            ->revealable()
                                            ->helperText('Cheia secretă pentru modul LIVE (producție)'),

                                        Forms\Components\TextInput::make('credentials.live_publishable_key')
                                            ->label('Live Publishable Key')
                                            ->placeholder('pk_live_...')
                                            ->helperText('Cheia publică pentru modul LIVE'),

                                        Forms\Components\TextInput::make('credentials.webhook_secret')
                                            ->label('Webhook Secret')
                                            ->placeholder('whsec_...')
                                            ->password()
                                            ->revealable()
                                            ->helperText('Secret pentru validarea webhook-urilor'),
                                    ])
                                    ->visible(fn ($get) => $get('provider') === 'stripe')
                                    ->columns(2),

                                // PAYPAL
                                Forms\Components\Section::make('PayPal API Credentials')
                                    ->description('Găsești aceste credențiale în PayPal Developer Dashboard → My Apps & Credentials')
                                    ->icon('heroicon-o-currency-dollar')
                                    ->schema([
                                        Forms\Components\TextInput::make('credentials.sandbox_client_id')
                                            ->label('Sandbox Client ID')
                                            ->placeholder('AXh...')
                                            ->helperText('Client ID pentru Sandbox (test)'),

                                        Forms\Components\TextInput::make('credentials.sandbox_secret')
                                            ->label('Sandbox Secret')
                                            ->placeholder('ED...')
                                            ->password()
                                            ->revealable()
                                            ->helperText('Secret pentru Sandbox'),

                                        Forms\Components\TextInput::make('credentials.live_client_id')
                                            ->label('Live Client ID')
                                            ->placeholder('AYh...')
                                            ->helperText('Client ID pentru producție'),

                                        Forms\Components\TextInput::make('credentials.live_secret')
                                            ->label('Live Secret')
                                            ->placeholder('EO...')
                                            ->password()
                                            ->revealable()
                                            ->helperText('Secret pentru producție'),

                                        Forms\Components\TextInput::make('credentials.webhook_id')
                                            ->label('Webhook ID')
                                            ->placeholder('5KJ...')
                                            ->helperText('ID-ul webhook-ului configurat în PayPal')
                                            ->columnSpanFull(),
                                    ])
                                    ->visible(fn ($get) => $get('provider') === 'paypal')
                                    ->columns(2),

                                // EUPLATESC
                                Forms\Components\Section::make('EuPlatesc Credentials')
                                    ->description('Primești aceste date de la EuPlatesc după înregistrarea contului de merchant')
                                    ->icon('heroicon-o-banknotes')
                                    ->schema([
                                        Forms\Components\TextInput::make('credentials.merchant_id')
                                            ->label('Merchant ID')
                                            ->placeholder('12345')
                                            ->helperText('ID-ul tău de merchant EuPlatesc'),

                                        Forms\Components\TextInput::make('credentials.secret_key')
                                            ->label('Secret Key')
                                            ->placeholder('xxxxxxxxxxxxxxxx')
                                            ->password()
                                            ->revealable()
                                            ->helperText('Cheia secretă pentru semnarea tranzacțiilor'),
                                    ])
                                    ->visible(fn ($get) => $get('provider') === 'euplatesc')
                                    ->columns(2),

                                // NETOPIA
                                Forms\Components\Section::make('Netopia (MobilPay) Credentials')
                                    ->description('Configurare pentru Netopia Payments (fost MobilPay)')
                                    ->icon('heroicon-o-device-phone-mobile')
                                    ->schema([
                                        Forms\Components\TextInput::make('credentials.signature')
                                            ->label('Signature (Account ID)')
                                            ->placeholder('XXXX-XXXX-XXXX-XXXX-XXXX')
                                            ->helperText('Signature-ul contului tău Netopia')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('credentials.public_key_path')
                                            ->label('Public Key Path')
                                            ->placeholder('/path/to/public.cer')
                                            ->helperText('Calea către fișierul public.cer'),

                                        Forms\Components\TextInput::make('credentials.private_key_path')
                                            ->label('Private Key Path')
                                            ->placeholder('/path/to/private.key')
                                            ->helperText('Calea către fișierul private.key'),

                                        Forms\Components\TextInput::make('credentials.private_key_password')
                                            ->label('Private Key Password')
                                            ->password()
                                            ->revealable()
                                            ->helperText('Parola pentru cheia privată (dacă există)')
                                            ->columnSpanFull(),
                                    ])
                                    ->visible(fn ($get) => $get('provider') === 'netopia')
                                    ->columns(2),

                                // BANK TRANSFER
                                Forms\Components\Section::make('Detalii Cont Bancar')
                                    ->description('Completează datele contului bancar pentru primirea plăților')
                                    ->icon('heroicon-o-building-library')
                                    ->schema([
                                        Forms\Components\TextInput::make('credentials.bank_name')
                                            ->label('Numele Băncii')
                                            ->placeholder('Ex: Banca Transilvania')
                                            ->required(fn ($get) => $get('provider') === 'bank_transfer'),

                                        Forms\Components\TextInput::make('credentials.account_holder')
                                            ->label('Titular Cont')
                                            ->placeholder('Ex: SC Compania SRL')
                                            ->required(fn ($get) => $get('provider') === 'bank_transfer'),

                                        Forms\Components\TextInput::make('credentials.iban')
                                            ->label('IBAN')
                                            ->placeholder('RO49AAAA1B31007593840000')
                                            ->required(fn ($get) => $get('provider') === 'bank_transfer')
                                            ->helperText('Formatul IBAN: RO + 2 cifre + cod bancar + cont'),

                                        Forms\Components\TextInput::make('credentials.swift_bic')
                                            ->label('Cod SWIFT/BIC')
                                            ->placeholder('BTRLRO22')
                                            ->required(fn ($get) => $get('provider') === 'bank_transfer')
                                            ->helperText('Cod SWIFT/BIC de 8 sau 11 caractere'),

                                        Forms\Components\TextInput::make('credentials.bank_address')
                                            ->label('Adresa Băncii')
                                            ->placeholder('Ex: Cluj-Napoca, Romania')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('credentials.account_currency')
                                            ->label('Moneda Contului')
                                            ->placeholder('RON')
                                            ->default('RON')
                                            ->maxLength(3),
                                    ])
                                    ->visible(fn ($get) => $get('provider') === 'bank_transfer')
                                    ->columns(2),
                            ]),

                        // TAB 3: Comisioane
                        Forms\Components\Tabs\Tab::make('Comisioane')
                            ->icon('heroicon-o-calculator')
                            ->schema([
                                Forms\Components\Section::make('Configurare Comisioane')
                                    ->description('Setează comisioanele pentru acest gateway de plată')
                                    ->schema([
                                        Forms\Components\TextInput::make('fee_percentage')
                                            ->label('Comision Procentual (%)')
                                            ->numeric()
                                            ->default(0)
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->helperText('Exemplu: 2.5 pentru 2.5%'),

                                        Forms\Components\TextInput::make('fee_fixed')
                                            ->label('Comision Fix (RON)')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix('RON')
                                            ->minValue(0)
                                            ->helperText('Comision fix pe tranzacție'),
                                    ])
                                    ->columns(2),
                            ]),

                        // TAB 4: Configurare Avansată
                        Forms\Components\Tabs\Tab::make('Setări Avansate')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Section::make('Opțiuni Gateway')
                                    ->schema([
                                        Forms\Components\Toggle::make('supports_quick_links')
                                            ->label('Suportă Link-uri de Plată Rapidă')
                                            ->helperText('Permite generarea de link-uri directe de plată')
                                            ->default(true)
                                            ->inline(false),

                                        Forms\Components\Toggle::make('supports_product_checkout')
                                            ->label('Suportă Checkout pentru Produse')
                                            ->helperText('Poate fi folosit în procesul de checkout')
                                            ->default(true)
                                            ->inline(false),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Configurare Suplimentară')
                                    ->description('Setări în format JSON (doar pentru utilizatori avansați)')
                                    ->schema([
                                        Forms\Components\Textarea::make('config')
                                            ->label('Config JSON')
                                            ->rows(5)
                                            ->placeholder('{"auto_cancel_unpaid": false, "payment_deadline_days": 3}')
                                            ->helperText('Format JSON valid')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsed()
                                    ->collapsible(),
                            ]),
                    ])
                    ->columnSpanFull(),
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

                Tables\Columns\BadgeColumn::make('provider')
                    ->label('Provider')
                    ->colors([
                        'success' => 'stripe',
                        'primary' => 'paypal',
                        'warning' => 'euplatesc',
                        'info' => 'netopia',
                        'secondary' => 'bank_transfer',
                    ]),

                Tables\Columns\IconColumn::make('test_mode')
                    ->label('Test')
                    ->boolean(),
                
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Activ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creat')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activ'),
                Tables\Filters\SelectFilter::make('provider')
                    ->label('Provider')
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'euplatesc' => 'EuPlatesc',
                        'netopia' => 'Netopia',
                        'bank_transfer' => 'Transfer Bancar',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentGateways::route('/'),
            'create' => Pages\CreatePaymentGateway::route('/create'),
            'edit' => Pages\EditPaymentGateway::route('/{record}/edit'),
        ];
    }
}
