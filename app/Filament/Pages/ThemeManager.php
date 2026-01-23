<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\CMS;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class ThemeManager extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = CMS::class;
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.theme-manager';

    public static function getNavigationLabel(): string
    {
        return __('Theme Manager');
    }

    public function getTitle(): string
    {
        return __('Theme Manager');
    }

    public ?array $data = [];
    public array $availableThemes = [];
    public ?string $activeTheme = null;
    public ?string $previewTheme = null;

    public function mount(): void
    {
        $this->loadThemes();
        $this->activeTheme = $this->getActiveTheme();
        
        $this->form->fill([
            'primary_color' => $this->getThemeSetting('primary_color', '#f59e0b'),
            'secondary_color' => $this->getThemeSetting('secondary_color', '#1f2937'),
            'accent_color' => $this->getThemeSetting('accent_color', '#10b981'),
            'font_family' => $this->getThemeSetting('font_family', 'Inter'),
            'font_size_base' => $this->getThemeSetting('font_size_base', '16'),
            'border_radius' => $this->getThemeSetting('border_radius', '8'),
            'header_style' => $this->getThemeSetting('header_style', 'default'),
            'footer_style' => $this->getThemeSetting('footer_style', 'default'),
            'sidebar_position' => $this->getThemeSetting('sidebar_position', 'right'),
            'dark_mode' => $this->getThemeSetting('dark_mode', false),
        ]);
    }

    protected function loadThemes(): void
    {
        $themesPath = resource_path('themes');
        if (!File::exists($themesPath)) {
            File::makeDirectory($themesPath, 0755, true);
        }

        $this->availableThemes = [
            [
                'id' => 'default',
                'name' => 'Default Theme',
                'description' => 'Clean, modern design with amber accents',
                'thumbnail' => '/images/themes/default.png',
                'version' => '1.0.0',
                'author' => 'Carpathian CMS',
            ],
            [
                'id' => 'dark-professional',
                'name' => 'Dark Professional',
                'description' => 'Sleek dark theme for professional sites',
                'thumbnail' => '/images/themes/dark-professional.png',
                'version' => '1.0.0',
                'author' => 'Carpathian CMS',
            ],
            [
                'id' => 'ecommerce-modern',
                'name' => 'E-commerce Modern',
                'description' => 'Optimized for online stores with product focus',
                'thumbnail' => '/images/themes/ecommerce-modern.png',
                'version' => '1.0.0',
                'author' => 'Carpathian CMS',
            ],
            [
                'id' => 'minimal-blog',
                'name' => 'Minimal Blog',
                'description' => 'Content-focused design for blogs',
                'thumbnail' => '/images/themes/minimal-blog.png',
                'version' => '1.0.0',
                'author' => 'Carpathian CMS',
            ],
        ];

        // Load custom themes from filesystem
        $customThemes = File::directories($themesPath);
        foreach ($customThemes as $themePath) {
            $configFile = $themePath . '/theme.json';
            if (File::exists($configFile)) {
                $config = json_decode(File::get($configFile), true);
                if ($config) {
                    $this->availableThemes[] = array_merge([
                        'id' => basename($themePath),
                        'custom' => true,
                    ], $config);
                }
            }
        }
    }

    protected function getActiveTheme(): string
    {
        return Cache::get('active_theme', 'default');
    }

    protected function getThemeSetting(string $key, $default = null)
    {
        $settings = Cache::get('theme_settings', []);
        return $settings[$key] ?? $default;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Theme Settings')
                    ->tabs([
                        Tabs\Tab::make(__('Colors'))
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Section::make(__('Brand Colors'))
                                    ->schema([
                                        ColorPicker::make('primary_color')
                                            ->label(__('Primary Color'))
                                            ->helperText(__('Main brand color used for buttons, links, and accents')),

                                        ColorPicker::make('secondary_color')
                                            ->label(__('Secondary Color'))
                                            ->helperText(__('Used for headings and dark elements')),

                                        ColorPicker::make('accent_color')
                                            ->label(__('Accent Color'))
                                            ->helperText(__('Used for success states and highlights')),
                                    ])
                                    ->columns(3),
                            ]),

                        Tabs\Tab::make(__('Typography'))
                            ->icon('heroicon-o-language')
                            ->schema([
                                Section::make(__('Font Settings'))
                                    ->schema([
                                        Select::make('font_family')
                                            ->label(__('Font Family'))
                                            ->options([
                                                'Inter' => 'Inter',
                                                'Roboto' => 'Roboto',
                                                'Open Sans' => 'Open Sans',
                                                'Lato' => 'Lato',
                                                'Poppins' => 'Poppins',
                                                'Montserrat' => 'Montserrat',
                                                'Playfair Display' => 'Playfair Display',
                                            ]),

                                        TextInput::make('font_size_base')
                                            ->label(__('Base Font Size (px)'))
                                            ->numeric()
                                            ->minValue(12)
                                            ->maxValue(24)
                                            ->suffix('px'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make(__('Layout'))
                            ->icon('heroicon-o-view-columns')
                            ->schema([
                                Section::make(__('Layout Options'))
                                    ->schema([
                                        Select::make('header_style')
                                            ->label(__('Header Style'))
                                            ->options([
                                                'default' => __('Default'),
                                                'sticky' => __('Sticky'),
                                                'transparent' => __('Transparent'),
                                                'centered' => __('Centered Logo'),
                                            ]),

                                        Select::make('footer_style')
                                            ->label(__('Footer Style'))
                                            ->options([
                                                'default' => __('Default'),
                                                'minimal' => __('Minimal'),
                                                'extended' => __('Extended'),
                                                'dark' => __('Dark'),
                                            ]),

                                        Select::make('sidebar_position')
                                            ->label(__('Sidebar Position'))
                                            ->options([
                                                'left' => __('Left'),
                                                'right' => __('Right'),
                                                'none' => __('No Sidebar'),
                                            ]),

                                        TextInput::make('border_radius')
                                            ->label(__('Border Radius'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(24)
                                            ->suffix('px'),

                                        Toggle::make('dark_mode')
                                            ->label(__('Enable Dark Mode Toggle'))
                                            ->helperText(__('Allow users to switch between light and dark mode')),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function saveSettings(): void
    {
        $settings = $this->form->getState();
        Cache::forever('theme_settings', $settings);

        $this->generateThemeCss($settings);

        Notification::make()
            ->success()
            ->title(__('Settings Saved'))
            ->body(__('Theme settings have been updated'))
            ->send();
    }

    protected function generateThemeCss(array $settings): void
    {
        $css = ":root {\n";
        $css .= "    --color-primary: {$settings['primary_color']};\n";
        $css .= "    --color-secondary: {$settings['secondary_color']};\n";
        $css .= "    --color-accent: {$settings['accent_color']};\n";
        $css .= "    --font-family: '{$settings['font_family']}', sans-serif;\n";
        $css .= "    --font-size-base: {$settings['font_size_base']}px;\n";
        $css .= "    --border-radius: {$settings['border_radius']}px;\n";
        $css .= "}\n";

        File::put(public_path('css/theme-variables.css'), $css);
    }

    public function activateTheme(string $themeId): void
    {
        Cache::forever('active_theme', $themeId);
        $this->activeTheme = $themeId;

        // Copy theme files if it's a custom theme
        $themePath = resource_path("themes/{$themeId}");
        if (File::exists($themePath)) {
            // Copy theme assets
            $assetsPath = $themePath . '/assets';
            if (File::exists($assetsPath)) {
                File::copyDirectory($assetsPath, public_path('themes/' . $themeId));
            }

            // Copy theme views
            $viewsPath = $themePath . '/views';
            if (File::exists($viewsPath)) {
                File::copyDirectory($viewsPath, resource_path('views/themes/' . $themeId));
            }
        }

        Notification::make()
            ->success()
            ->title(__('Theme Activated'))
            ->body(__('Theme ":name" is now active', ['name' => $themeId]))
            ->send();
    }

    public function previewTheme(string $themeId): void
    {
        $this->previewTheme = $themeId;
    }

    public function closePreview(): void
    {
        $this->previewTheme = null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save Settings'))
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action('saveSettings'),

            Action::make('reset')
                ->label(__('Reset to Default'))
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    Cache::forget('theme_settings');
                    $this->mount();
                    Notification::make()
                        ->success()
                        ->title(__('Settings Reset'))
                        ->send();
                })
                ->requiresConfirmation(),
        ];
    }
}
