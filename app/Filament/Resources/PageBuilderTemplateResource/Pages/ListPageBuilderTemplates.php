<?php

namespace App\Filament\Resources\PageBuilderTemplateResource\Pages;

use App\Filament\Resources\PageBuilderTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPageBuilderTemplates extends ListRecords
{
    protected static string $resource = PageBuilderTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
