<?php

namespace App\Filament\Resources\PageBuilderTemplateResource\Pages;

use App\Filament\Resources\PageBuilderTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPageBuilderTemplate extends EditRecord
{
    protected static string $resource = PageBuilderTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
