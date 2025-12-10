<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['file'])) {
            $file = $data['file'];
            $filePath = $file;
            
            // Get file information
            $fullPath = Storage::disk('public')->path($filePath);
            
            if (file_exists($fullPath)) {
                $data['file_name'] = basename($filePath);
                $data['file_path'] = $filePath;
                $data['disk'] = 'public';
                $data['mime_type'] = Storage::disk('public')->mimeType($filePath);
                $data['size'] = Storage::disk('public')->size($filePath);
                $data['type'] = $this->getFileType($data['mime_type']);
                $data['user_id'] = auth()->id();

                // Get image dimensions if it's an image
                if (str_starts_with($data['mime_type'], 'image/')) {
                    $imageSize = getimagesize($fullPath);
                    if ($imageSize) {
                        $data['width'] = $imageSize[0];
                        $data['height'] = $imageSize[1];
                    }
                }
            }
            
            unset($data['file']);
        }

        return $data;
    }

    protected function getFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } else {
            return 'document';
        }
    }
}
