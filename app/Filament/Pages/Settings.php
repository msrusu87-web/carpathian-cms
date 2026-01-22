<?php

namespace App\Filament\Pages;
use App\Filament\Clusters\Settings as SettingsCluster;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class Settings extends Page
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $navigationIcon = "heroicon-o-cog-6-tooth";

    protected static string $view = "filament.pages.settings";
    protected static ?string $slug = "site-settings";



    public static function getNavigationLabel(): string
    {
        return __('Site Settings');
    }

    public function getTitle(): string
    {
        return __('Site Settings');
    }

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            "site_name" => Setting::get("site_name", "Carpathian CMS"),
            "site_url" => Setting::get("site_url", config("app.url")),
            "site_protocol" => Setting::get("site_protocol", "https"),
            "site_description" => Setting::get("site_description", ""),
            "admin_email" => Setting::get("admin_email", "admin@example.com"),
            "favicon" => Setting::get("favicon"),
            "logo" => Setting::get("logo"),
            "backup_schedule" => Setting::get("backup_schedule", "daily"),
            "backup_enabled" => Setting::get("backup_enabled", true),
            "maintenance_mode" => Setting::get("maintenance_mode", false),
            "default_language" => Setting::get("default_language", "en"),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__("General Settings"))
                    ->description(__("Basic website configuration"))
                    ->schema([
                        TextInput::make("site_name")
                            ->label(__("Site Name"))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__("Carpathian CMS")),
                        
                        Select::make("site_protocol")
                            ->label(__("Protocol"))
                            ->options([
                                "http" => __("HTTP"),
                                "https" => __("HTTPS (Recommended)"),
                            ])
                            ->required()
                            ->default("https"),
                        
                        TextInput::make("site_url")
                            ->label(__("Domain/URL"))
                            ->required()
                            ->placeholder(__("cms.carphatian.ro"))
                            ->helperText(__("Enter domain without protocol")),
                        
                        Textarea::make("site_description")
                            ->label(__("Site Description"))
                            ->rows(3),
                        
                        TextInput::make("admin_email")
                            ->label(__("Admin Email"))
                            ->email()
                            ->required(),
                    ])->columns(2),

                Section::make(__("Branding"))
                    ->description(__("Upload your logo and favicon"))
                    ->schema([
                        FileUpload::make("logo")
                            ->label(__("Logo"))
                            ->image()
                            ->maxSize(2048)
                            ->directory("branding")
                            ->helperText(__("Recommended: 200x60px PNG/SVG")),
                        
                        FileUpload::make("favicon")
                            ->label(__("Favicon"))
                            ->image()
                            ->maxSize(512)
                            ->directory("branding")
                            ->helperText(__("Recommended: 32x32px ICO/PNG")),
                    ])->columns(2),

                Section::make(__("Backup & Maintenance"))
                    ->description(__("Configure automatic backups"))
                    ->schema([
                        Toggle::make("backup_enabled")
                            ->label(__("Enable Automatic Backups"))
                            ->default(true),
                        
                        Select::make("backup_schedule")
                            ->label(__("Backup Schedule"))
                            ->options([
                                "hourly" => __("Every Hour"),
                                "daily" => __("Every Day"),
                                "weekly" => __("Once a Week"),
                                "monthly" => __("Once a Month"),
                            ])
                            ->required()
                            ->default("daily"),
                        
                        Toggle::make("maintenance_mode")
                            ->label(__("Maintenance Mode")),
                    ])->columns(3),
            ])
            ->statePath("data");
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make("save")
                ->label(__("Save Settings"))
                ->icon("heroicon-o-check")
                ->color("success")
                ->action("save"),
            
            Action::make("backup_database")
                ->label(__("Download Database"))
                ->icon("heroicon-o-arrow-down-tray")
                ->color("primary")
                ->action("downloadDatabaseBackup"),
            
            Action::make("restore_backup")
                ->label(__("Restore Backup"))
                ->icon("heroicon-o-arrow-up-tray")
                ->color("warning")
                ->form([
                    FileUpload::make("backup_file")
                        ->label(__("SQL File"))
                        ->acceptedFileTypes([".sql"])
                        ->required(),
                ])
                ->action("restoreBackup")
                ->requiresConfirmation(),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                Setting::set($key, $value ? "1" : "0", "boolean");
            } elseif (is_array($value)) {
                Setting::set($key, json_encode($value), "json");
            } else {
                Setting::set($key, $value);
            }
        }

        $this->updateEnvFile($data);

        Artisan::call("config:clear");
        Artisan::call("cache:clear");

        Notification::make()
            ->title(__("Settings saved successfully"))
            ->success()
            ->send();
    }

    protected function updateEnvFile(array $data): void
    {
        $envPath = base_path(".env");
        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);
        $protocol = $data["site_protocol"] ?? "https";
        $domain = preg_replace("#^https?://#", "", $data["site_url"] ?? "");
        $fullUrl = "{$protocol}://{$domain}";

        $envContent = preg_replace("/^APP_URL=.*/m", "APP_URL={$fullUrl}", $envContent);
        file_put_contents($envPath, $envContent);
    }

    public function downloadDatabaseBackup()
    {
        try {
            $filename = "backup-" . date("Y-m-d-His") . ".sql";
            $path = storage_path("app/backups/{$filename}");

            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            $cmd = sprintf(
                "mysqldump --user=%s --password=%s --host=%s %s > %s 2>&1",
                config("database.connections.mysql.username"),
                config("database.connections.mysql.password"),
                config("database.connections.mysql.host"),
                config("database.connections.mysql.database"),
                $path
            );
            
            exec($cmd, $output, $returnVar);

            if ($returnVar !== 0 || !file_exists($path)) {
                throw new \Exception("Backup failed");
            }

            return response()->download($path, $filename)->deleteFileAfterSend();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__("Backup failed"))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function restoreBackup(array $data): void
    {
        try {
            $path = Storage::path($data["backup_file"]);

            if (!file_exists($path)) {
                throw new \Exception(__("File not found"));
            }

            $cmd = sprintf(
                "mysql --user=%s --password=%s --host=%s %s < %s 2>&1",
                config("database.connections.mysql.username"),
                config("database.connections.mysql.password"),
                config("database.connections.mysql.host"),
                config("database.connections.mysql.database"),
                $path
            );
            
            exec($cmd, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception(__("Restore failed"));
            }

            Storage::delete($data["backup_file"]);

            Notification::make()
                ->title(__("Database restored successfully"))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__("Restore failed"))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
