<?php
namespace Plugins\Freelancer\Filament\Resources\FreelancerGigResource\Pages;
use Plugins\Freelancer\Filament\Resources\FreelancerGigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditFreelancerGig extends EditRecord {
    protected static string $resource = FreelancerGigResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make()];
    }
}
