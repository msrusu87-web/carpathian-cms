<?php

namespace App\Filament\Resources\AiContentWriterResource\Pages;

use App\Filament\Resources\AiContentWriterResource;
use Filament\Resources\Pages\ListRecords;

class ListAiContentWriters extends ListRecords
{
    protected static string $resource = AiContentWriterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('New AI Content'),
        ];
    }
}
