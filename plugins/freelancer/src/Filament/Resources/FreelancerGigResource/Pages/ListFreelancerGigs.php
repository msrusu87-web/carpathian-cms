<?php
namespace Plugins\Freelancer\Filament\Resources\FreelancerGigResource\Pages;
use Plugins\Freelancer\Filament\Resources\FreelancerGigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListFreelancerGigs extends ListRecords {
    protected static string $resource = FreelancerGigResource::class;
    protected function getHeaderActions(): array {
        return [Actions\CreateAction::make()];
    }
}
