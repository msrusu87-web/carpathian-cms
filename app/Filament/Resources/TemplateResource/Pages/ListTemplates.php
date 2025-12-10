<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use App\Models\Template;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ListTemplates extends ListRecords
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make("upload")
                ->label("Upload Template Package")
                ->icon("heroicon-o-arrow-up-tray")
                ->color("success")
                ->form([
                    FileUpload::make("package")
                        ->label("Template ZIP Package")
                        ->acceptedFileTypes(["application/zip"])
                        ->maxSize(20480) // 20MB
                        ->required()
                        ->helperText("Upload a ZIP file containing package.json, style.css, and layouts/")
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
                        
                        // Read style.css
                        $css = $zip->getFromName("style.css");
                        if (!$css) {
                            $zip->close();
                            throw new \Exception("style.css not found in ZIP");
                        }
                        
                        // Read optional script.js
                        $js = $zip->getFromName("script.js");
                        
                        // Read layouts
                        $layouts = [];
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $filename = $zip->getNameIndex($i);
                            if (str_starts_with($filename, "layouts/") && str_ends_with($filename, ".json")) {
                                $layoutName = basename($filename, ".json");
                                $layoutContent = $zip->getFromIndex($i);
                                $layouts[$layoutName] = json_decode($layoutContent, true);
                            }
                        }
                        
                        // Create template record
                        Template::create([
                            "name" => $manifest["name"],
                            "slug" => $manifest["slug"],
                            "description" => $manifest["description"] ?? "",
                            "css" => $css,
                            "js" => $js,
                            "layouts" => json_encode($layouts),
                            "color_scheme" => json_encode($manifest["color_scheme"] ?? []),
                            "typography" => json_encode($manifest["typography"] ?? []),
                            "is_active" => false,
                            "is_default" => false,
                        ]);
                        
                        $zip->close();
                        Storage::delete($data["package"]);
                        
                        Notification::make()
                            ->title("Template uploaded successfully")
                            ->body("Template \"{$manifest["name"]}\" has been uploaded. Activate it to enable.")
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
