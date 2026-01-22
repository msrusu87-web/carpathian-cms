<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('manage_translations')
                ->label('Editează Traduceri')
                ->icon('heroicon-o-language')
                ->color('success')
                ->url(fn () => LanguageResource::getUrl('translations')),
            Actions\CreateAction::make(),
        ];
    }
}
