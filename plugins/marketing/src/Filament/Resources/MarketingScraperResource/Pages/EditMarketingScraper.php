<?php

namespace Plugins\Marketing\Filament\Resources\MarketingScraperResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Plugins\Marketing\Filament\Resources\MarketingScraperResource;

class EditMarketingScraper extends EditRecord
{
    protected static string $resource = MarketingScraperResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Convert JSON urls back to textarea format
        if (!empty($data['urls'])) {
            $urls = json_decode($data['urls'], true);
            $data['urls'] = is_array($urls) ? implode("\n", $urls) : $data['urls'];
        }
        
        // Restore settings
        if (!empty($data['settings'])) {
            $settings = json_decode($data['settings'], true);
            $data['add_to_list'] = $settings['add_to_list'] ?? null;
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Parse URLs from textarea
        $urls = array_filter(array_map('trim', explode("\n", $data['urls'] ?? '')));
        $data['urls'] = json_encode($urls);
        $data['total_urls'] = count($urls);
        
        if (!empty($data['add_to_list'])) {
            $data['settings'] = json_encode(['add_to_list' => $data['add_to_list']]);
        }
        unset($data['add_to_list'], $data['skip_duplicates']);
        
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
