<?php

namespace Plugins\Marketing\Filament\Resources\MarketingScraperResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Plugins\Marketing\Filament\Resources\MarketingScraperResource;

class CreateMarketingScraper extends CreateRecord
{
    protected static string $resource = MarketingScraperResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Parse URLs from textarea
        $urls = array_filter(array_map('trim', explode("\n", $data['urls'] ?? '')));
        $data['urls'] = json_encode($urls);
        $data['total_urls'] = count($urls);
        $data['status'] = 'pending';
        
        // Store list_id in settings if provided
        if (!empty($data['add_to_list'])) {
            $data['settings'] = json_encode(['add_to_list' => $data['add_to_list']]);
        }
        unset($data['add_to_list'], $data['skip_duplicates']);
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
