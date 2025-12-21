<?php

namespace Plugins\PaymentGateway\Filament\Resources\PaymentGatewayResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Plugins\PaymentGateway\Filament\Resources\PaymentGatewayResource;
use Filament\Notifications\Notification;

class EditPaymentGateway extends EditRecord
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Gateway actualizat')
            ->body('Gateway-ul de plată a fost actualizat cu succes.');
    }
}
