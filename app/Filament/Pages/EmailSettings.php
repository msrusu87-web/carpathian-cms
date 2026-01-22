<?php

namespace App\Filament\Pages;
use App\Filament\Clusters\Communications;

use App\Models\EmailSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class EmailSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $cluster = Communications::class;
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.email-settings';

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('Email Settings');
    }

    public function getTitle(): string
    {
        return __('Email & SMTP Settings');
    }

    public function mount(): void
    {
        $settings = EmailSetting::first();
        
        $this->form->fill([
            'mail_driver' => $settings?->mail_driver ?? 'smtp',
            'mail_host' => $settings?->mail_host ?? '',
            'mail_port' => $settings?->mail_port ?? 587,
            'mail_username' => $settings?->mail_username ?? '',
            'mail_password' => $settings?->mail_password ?? '',
            'mail_encryption' => $settings?->mail_encryption ?? 'tls',
            'mail_from_address' => $settings?->mail_from_address ?? '',
            'mail_from_name' => $settings?->mail_from_name ?? config('app.name'),
            'admin_notification_email' => $settings?->admin_notification_email ?? '',
            'notification_preferences' => $settings?->notification_preferences ?? [
                'contact_form' => true,
                'new_order' => true,
                'support_chat' => true,
                'new_user' => true,
            ],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('SMTP Configuration'))
                            ->icon('heroicon-o-server')
                            ->schema([
                                Forms\Components\Select::make('mail_driver')
                                    ->label(__('Mail Driver'))
                                    ->options([
                                        'smtp' => 'SMTP',
                                        'sendmail' => 'Sendmail',
                                        'mailgun' => 'Mailgun',
                                        'ses' => 'Amazon SES',
                                        'log' => 'Log (Testing)',
                                    ])
                                    ->default('smtp'),
                                Forms\Components\TextInput::make('mail_host')
                                    ->label(__('SMTP Host'))
                                    ->placeholder('smtp.example.com'),
                                Forms\Components\TextInput::make('mail_port')
                                    ->label(__('SMTP Port'))
                                    ->numeric()
                                    ->default(587),
                                Forms\Components\TextInput::make('mail_username')
                                    ->label(__('SMTP Username'))
                                    ->placeholder('username@example.com'),
                                Forms\Components\TextInput::make('mail_password')
                                    ->label(__('SMTP Password'))
                                    ->password()
                                    ->revealable(),
                                Forms\Components\Select::make('mail_encryption')
                                    ->label(__('Encryption'))
                                    ->options([
                                        'tls' => 'TLS',
                                        'ssl' => 'SSL',
                                        '' => 'None',
                                    ])
                                    ->default('tls'),
                            ])->columns(2),
                        
                        Forms\Components\Tabs\Tab::make(__('Sender Information'))
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\TextInput::make('mail_from_address')
                                    ->label(__('From Email Address'))
                                    ->email()
                                    ->placeholder('noreply@example.com'),
                                Forms\Components\TextInput::make('mail_from_name')
                                    ->label(__('From Name'))
                                    ->placeholder(config('app.name')),
                            ])->columns(2),
                        
                        Forms\Components\Tabs\Tab::make(__('Admin Notifications'))
                            ->icon('heroicon-o-bell')
                            ->schema([
                                Forms\Components\TextInput::make('admin_notification_email')
                                    ->label(__('Admin Email for Notifications'))
                                    ->email()
                                    ->placeholder('admin@example.com')
                                    ->helperText(__('All notifications will be sent to this email')),
                                
                                Forms\Components\Section::make(__('Notification Types'))
                                    ->description(__('Choose which notifications to receive via email'))
                                    ->schema([
                                        Forms\Components\Toggle::make('notification_preferences.contact_form')
                                            ->label(__('Contact Form Messages'))
                                            ->default(true),
                                        Forms\Components\Toggle::make('notification_preferences.new_order')
                                            ->label(__('New Orders'))
                                            ->default(true),
                                        Forms\Components\Toggle::make('notification_preferences.support_chat')
                                            ->label(__('Support Chat Messages'))
                                            ->default(true),
                                        Forms\Components\Toggle::make('notification_preferences.new_user')
                                            ->label(__('New User Registrations'))
                                            ->default(true),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        EmailSetting::updateOrCreate(
            ['id' => 1],
            $data
        );

        Notification::make()
            ->title(__('Settings saved successfully'))
            ->success()
            ->send();
    }

    public function testEmail(): void
    {
        $settings = EmailSetting::first();
        if (!$settings || !$settings->admin_notification_email) {
            Notification::make()
                ->title(__('Please configure admin email first'))
                ->danger()
                ->send();
            return;
        }

        try {
            \App\Services\EmailService::sendRaw(
                $settings->admin_notification_email,
                'Test Email from ' . config('app.name'),
                '<h1>Test Email</h1><p>This is a test email to verify your SMTP configuration is working correctly.</p>'
            );

            Notification::make()
                ->title(__('Test email sent successfully'))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Failed to send test email'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('test')
                ->label(__('Send Test Email'))
                ->icon('heroicon-o-paper-airplane')
                ->action('testEmail')
                ->color('gray'),
            Action::make('save')
                ->label(__('Save Settings'))
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}
