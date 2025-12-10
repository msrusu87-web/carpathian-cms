<?php
namespace Plugins\Freelancer\Filament\Resources\FreelancerProfileResource\Pages;
use Plugins\Freelancer\Filament\Resources\FreelancerProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditFreelancerProfile extends EditRecord {
    protected static string $resource = FreelancerProfileResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make()];
    }
}
