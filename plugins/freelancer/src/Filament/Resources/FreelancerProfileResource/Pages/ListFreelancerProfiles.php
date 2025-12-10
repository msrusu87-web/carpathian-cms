<?php
namespace Plugins\Freelancer\Filament\Resources\FreelancerProfileResource\Pages;
use Plugins\Freelancer\Filament\Resources\FreelancerProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListFreelancerProfiles extends ListRecords {
    protected static string $resource = FreelancerProfileResource::class;
    protected function getHeaderActions(): array {
        return [Actions\CreateAction::make()];
    }
}
