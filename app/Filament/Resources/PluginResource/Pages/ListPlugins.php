<?php

namespace App\Filament\Resources\PluginResource\Pages;

use App\Filament\Resources\PluginResource;
use App\Models\Plugin;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ListPlugins extends ListRecords
{
    protected static string $resource = PluginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make("upload")
                ->label("Upload Plugin Package")
                ->icon("heroicon-o-arrow-up-tray")
                ->color("success")
                ->form([
                    FileUpload::make("package")
                        ->label("Plugin ZIP Package")
                        ->acceptedFileTypes(["application/zip"])
                        ->maxSize(10240) // 10MB
                        ->required()
                        ->helperText("Upload a ZIP file containing package.json and plugin.php")
                ])
                ->action(function (array $data) {
                    try {
                        $path = Storage::path($data["package"]);
                        $zip = new ZipArchive();
                        
                        if ($zip->open($path) !== true) {
                            throw new \Exception("Failed to open ZIP file");
                        }
                        
                        // Read package.json
                        $manifestContent = $zip->getFromName("package.json");
                        if (!$manifestContent) {
                            $zip->close();
                            throw new \Exception("package.json not found in ZIP");
                        }
                        
                        $manifest = json_decode($manifestContent, true);
                        if (!$manifest) {
                            $zip->close();
                            throw new \Exception("Invalid package.json format");
                        }
                        
                        // Read plugin.php
                        $pluginCode = $zip->getFromName("plugin.php");
                        if (!$pluginCode) {
                            $zip->close();
                            throw new \Exception("plugin.php not found in ZIP");
                        }
                        
                        // Extract hooks if defined
                        $hooks = $manifest["hooks"] ?? [];
                        
                        // Create plugin record
                        Plugin::create([
                            "name" => $manifest["name"],
                            "slug" => $manifest["slug"],
                            "description" => $manifest["description"] ?? "",
                            "version" => $manifest["version"],
                            "author" => $manifest["author"] ?? null,
                            "author_url" => $manifest["author_url"] ?? null,
                            "code" => $pluginCode,
                            "hooks" => json_encode($hooks),
                            "dependencies" => json_encode($manifest["dependencies"] ?? []),
                            "is_active" => false,
                            "ai_generated" => false,
                        ]);
                        
                        $zip->close();
                        Storage::delete($data["package"]);
                        
                        Notification::make()
                            ->title("Plugin uploaded successfully")
                            ->body("Plugin \"{$manifest["name"]}\" has been uploaded. Activate it to enable.")
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title("Upload failed")
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
