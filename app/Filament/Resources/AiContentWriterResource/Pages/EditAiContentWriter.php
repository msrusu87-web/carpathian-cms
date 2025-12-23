<?php

namespace App\Filament\Resources\AiContentWriterResource\Pages;

use App\Filament\Resources\AiContentWriterResource;
use Filament\Resources\Pages\EditRecord;

class EditAiContentWriter extends EditRecord
{
    protected static string $resource = AiContentWriterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
