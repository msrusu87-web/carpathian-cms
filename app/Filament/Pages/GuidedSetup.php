<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Models\Page as PageModel;
use App\Models\Setting;

class GuidedSetup extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';
    protected static ?int $navigationSort = -100;
    protected static string $view = 'filament.pages.guided-setup';

    public static function getNavigationLabel(): string
    {
        return __('Setup Wizard');
    }

    public function getTitle(): string
    {
        return __('Welcome to Carpathian CMS');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return !Cache::get('setup_completed', false);
    }

    public ?array $data = [];
    public int $currentStep = 1;
    public bool $setupComplete = false;

    public function mount(): void
    {
        if (Cache::get('setup_completed', false)) {
            $this->setupComplete = true;
        }

        $this->form->fill([
            // Site info
            'site_name' => config('app.name'),
            'site_description' => '',
            'site_email' => '',
            'site_phone' => '',
            // Branding
            'primary_color' => '#f59e0b',
            'secondary_color' => '#1f2937',
            // Features
            'enable_shop' => true,
            'enable_blog' => true,
            'enable_portfolio' => true,
            'enable_ai' => true,
            // Pages
            'create_about' => true,
            'create_contact' => true,
            'create_privacy' => true,
            'create_terms' => true,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make(__('Site Information'))
                        ->icon('heroicon-o-building-office')
                        ->description(__('Basic site details'))
                        ->schema([
                            Section::make(__('About Your Site'))
                                ->schema([
                                    TextInput::make('site_name')
                                        ->label(__('Site Name'))
                                        ->required()
                                        ->maxLength(100)
                                        ->placeholder(__('My Awesome Site')),

                                    Textarea::make('site_description')
                                        ->label(__('Site Description'))
                                        ->rows(3)
                                        ->maxLength(500)
                                        ->placeholder(__('A brief description of your website')),
                                ])
                                ->columns(1),

                            Section::make(__('Contact Information'))
                                ->schema([
                                    TextInput::make('site_email')
                                        ->label(__('Contact Email'))
                                        ->email()
                                        ->required(),

                                    TextInput::make('site_phone')
                                        ->label(__('Phone Number'))
                                        ->tel(),

                                    TextInput::make('site_address')
                                        ->label(__('Address'))
                                        ->maxLength(255),
                                ])
                                ->columns(2),
                        ]),

                    Wizard\Step::make(__('Branding'))
                        ->icon('heroicon-o-paint-brush')
                        ->description(__('Colors and logo'))
                        ->schema([
                            Section::make(__('Logo & Favicon'))
                                ->schema([
                                    FileUpload::make('logo')
                                        ->label(__('Site Logo'))
                                        ->image()
                                        ->directory('branding')
                                        ->helperText(__('Recommended size: 200x50px')),

                                    FileUpload::make('favicon')
                                        ->label(__('Favicon'))
                                        ->image()
                                        ->directory('branding')
                                        ->helperText(__('Recommended size: 32x32px')),
                                ])
                                ->columns(2),

                            Section::make(__('Brand Colors'))
                                ->schema([
                                    ColorPicker::make('primary_color')
                                        ->label(__('Primary Color'))
                                        ->helperText(__('Main brand color')),

                                    ColorPicker::make('secondary_color')
                                        ->label(__('Secondary Color'))
                                        ->helperText(__('Used for headings')),

                                    ColorPicker::make('accent_color')
                                        ->label(__('Accent Color'))
                                        ->helperText(__('For highlights')),
                                ])
                                ->columns(3),
                        ]),

                    Wizard\Step::make(__('Features'))
                        ->icon('heroicon-o-cog-6-tooth')
                        ->description(__('Enable site features'))
                        ->schema([
                            Section::make(__('Core Features'))
                                ->description(__('Choose which features to enable on your site'))
                                ->schema([
                                    Toggle::make('enable_shop')
                                        ->label(__('E-commerce Shop'))
                                        ->helperText(__('Enable product listings, cart, and checkout'))
                                        ->default(true),

                                    Toggle::make('enable_blog')
                                        ->label(__('Blog'))
                                        ->helperText(__('Enable blog posts and articles'))
                                        ->default(true),

                                    Toggle::make('enable_portfolio')
                                        ->label(__('Portfolio'))
                                        ->helperText(__('Showcase your work and projects'))
                                        ->default(true),

                                    Toggle::make('enable_ai')
                                        ->label(__('AI Features'))
                                        ->helperText(__('Enable AI-powered content generation'))
                                        ->default(true),

                                    Toggle::make('enable_multilingual')
                                        ->label(__('Multilingual'))
                                        ->helperText(__('Support multiple languages'))
                                        ->default(false),
                                ])
                                ->columns(2),
                        ]),

                    Wizard\Step::make(__('Pages'))
                        ->icon('heroicon-o-document-text')
                        ->description(__('Create essential pages'))
                        ->schema([
                            Section::make(__('Essential Pages'))
                                ->description(__('These pages will be created automatically'))
                                ->schema([
                                    Toggle::make('create_about')
                                        ->label(__('About Us'))
                                        ->helperText(__('Company information page'))
                                        ->default(true),

                                    Toggle::make('create_contact')
                                        ->label(__('Contact'))
                                        ->helperText(__('Contact form and details'))
                                        ->default(true),

                                    Toggle::make('create_privacy')
                                        ->label(__('Privacy Policy'))
                                        ->helperText(__('GDPR compliant privacy policy'))
                                        ->default(true),

                                    Toggle::make('create_terms')
                                        ->label(__('Terms & Conditions'))
                                        ->helperText(__('Service terms'))
                                        ->default(true),

                                    Toggle::make('create_faq')
                                        ->label(__('FAQ'))
                                        ->helperText(__('Frequently asked questions'))
                                        ->default(false),
                                ])
                                ->columns(2),
                        ]),

                    Wizard\Step::make(__('Social Media'))
                        ->icon('heroicon-o-share')
                        ->description(__('Connect your social profiles'))
                        ->schema([
                            Section::make(__('Social Links'))
                                ->schema([
                                    TextInput::make('social_facebook')
                                        ->label('Facebook')
                                        ->url()
                                        ->prefix('https://'),

                                    TextInput::make('social_instagram')
                                        ->label('Instagram')
                                        ->url()
                                        ->prefix('https://'),

                                    TextInput::make('social_twitter')
                                        ->label('Twitter / X')
                                        ->url()
                                        ->prefix('https://'),

                                    TextInput::make('social_linkedin')
                                        ->label('LinkedIn')
                                        ->url()
                                        ->prefix('https://'),

                                    TextInput::make('social_youtube')
                                        ->label('YouTube')
                                        ->url()
                                        ->prefix('https://'),

                                    TextInput::make('social_tiktok')
                                        ->label('TikTok')
                                        ->url()
                                        ->prefix('https://'),
                                ])
                                ->columns(2),
                        ]),

                    Wizard\Step::make(__('Complete'))
                        ->icon('heroicon-o-check-circle')
                        ->description(__('Finish setup'))
                        ->schema([
                            Section::make(__('Setup Complete!'))
                                ->description(__('Your site is ready. Here\'s a summary of what will be configured:'))
                                ->schema([
                                    \Filament\Forms\Components\Placeholder::make('summary')
                                        ->label('')
                                        ->content(fn ($get) => view('filament.components.setup-summary', [
                                            'siteName' => $get('site_name'),
                                            'features' => [
                                                'shop' => $get('enable_shop'),
                                                'blog' => $get('enable_blog'),
                                                'portfolio' => $get('enable_portfolio'),
                                                'ai' => $get('enable_ai'),
                                            ],
                                        ])),
                                ]),
                        ]),
                ])
                ->submitAction(view('filament.components.setup-submit-button'))
                ->persistStepInQueryString('step')
                ->skippable(),
            ])
            ->statePath('data');
    }

    public function completeSetup(): void
    {
        $data = $this->form->getState();

        try {
            // Save site settings
            $this->saveSettings($data);

            // Create essential pages
            $this->createPages($data);

            // Save theme settings
            $this->saveThemeSettings($data);

            // Mark setup as complete
            Cache::forever('setup_completed', true);
            $this->setupComplete = true;

            // Clear caches
            Artisan::call('optimize:clear');

            Notification::make()
                ->success()
                ->title(__('Setup Complete!'))
                ->body(__('Your site is ready to use'))
                ->send();

            $this->redirect('/admin');

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('Setup Failed'))
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function saveSettings(array $data): void
    {
        $settings = [
            'site_name' => $data['site_name'] ?? config('app.name'),
            'site_description' => $data['site_description'] ?? '',
            'site_email' => $data['site_email'] ?? '',
            'site_phone' => $data['site_phone'] ?? '',
            'site_address' => $data['site_address'] ?? '',
            'social_facebook' => $data['social_facebook'] ?? '',
            'social_instagram' => $data['social_instagram'] ?? '',
            'social_twitter' => $data['social_twitter'] ?? '',
            'social_linkedin' => $data['social_linkedin'] ?? '',
            'social_youtube' => $data['social_youtube'] ?? '',
            'social_tiktok' => $data['social_tiktok'] ?? '',
            'features_shop' => $data['enable_shop'] ?? true,
            'features_blog' => $data['enable_blog'] ?? true,
            'features_portfolio' => $data['enable_portfolio'] ?? true,
            'features_ai' => $data['enable_ai'] ?? true,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
            );
        }
    }

    protected function createPages(array $data): void
    {
        $pages = [
            'about' => [
                'slug' => 'about',
                'title' => __('About Us'),
                'content' => $this->getAboutPageTemplate($data),
            ],
            'contact' => [
                'slug' => 'contact',
                'title' => __('Contact'),
                'content' => $this->getContactPageTemplate($data),
            ],
            'privacy' => [
                'slug' => 'privacy-policy',
                'title' => __('Privacy Policy'),
                'content' => $this->getPrivacyPageTemplate($data),
            ],
            'terms' => [
                'slug' => 'terms-conditions',
                'title' => __('Terms & Conditions'),
                'content' => $this->getTermsPageTemplate($data),
            ],
            'faq' => [
                'slug' => 'faq',
                'title' => __('FAQ'),
                'content' => $this->getFaqPageTemplate($data),
            ],
        ];

        foreach ($pages as $key => $page) {
            if ($data["create_{$key}"] ?? false) {
                PageModel::updateOrCreate(
                    ['slug' => $page['slug']],
                    [
                        'title' => $page['title'],
                        'content' => $page['content'],
                        'status' => 'published',
                        'meta_title' => $page['title'] . ' - ' . ($data['site_name'] ?? ''),
                    ]
                );
            }
        }
    }

    protected function saveThemeSettings(array $data): void
    {
        Cache::forever('theme_settings', [
            'primary_color' => $data['primary_color'] ?? '#f59e0b',
            'secondary_color' => $data['secondary_color'] ?? '#1f2937',
            'accent_color' => $data['accent_color'] ?? '#10b981',
        ]);
    }

    protected function getAboutPageTemplate(array $data): string
    {
        $siteName = $data['site_name'] ?? 'Our Company';
        return "<h2>About {$siteName}</h2><p>Welcome to {$siteName}. We are dedicated to providing the best products and services to our customers.</p><h3>Our Mission</h3><p>Our mission is to deliver excellence in everything we do.</p><h3>Our Values</h3><ul><li>Quality</li><li>Integrity</li><li>Innovation</li><li>Customer Focus</li></ul>";
    }

    protected function getContactPageTemplate(array $data): string
    {
        return "<h2>Contact Us</h2><p>We'd love to hear from you. Get in touch with us using the form below or through our contact information.</p>";
    }

    protected function getPrivacyPageTemplate(array $data): string
    {
        $siteName = $data['site_name'] ?? 'Our Company';
        return "<h2>Privacy Policy</h2><p>Last updated: " . date('F j, Y') . "</p><p>{$siteName} operates the website. This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our Service.</p>";
    }

    protected function getTermsPageTemplate(array $data): string
    {
        $siteName = $data['site_name'] ?? 'Our Company';
        return "<h2>Terms and Conditions</h2><p>Last updated: " . date('F j, Y') . "</p><p>Please read these Terms and Conditions carefully before using the {$siteName} website.</p>";
    }

    protected function getFaqPageTemplate(array $data): string
    {
        return "<h2>Frequently Asked Questions</h2><h3>What products do you offer?</h3><p>We offer a wide range of products to meet your needs.</p><h3>How can I track my order?</h3><p>You can track your order through your account dashboard.</p>";
    }

    public function skipSetup(): void
    {
        Cache::forever('setup_completed', true);
        $this->redirect('/admin');
    }
}
