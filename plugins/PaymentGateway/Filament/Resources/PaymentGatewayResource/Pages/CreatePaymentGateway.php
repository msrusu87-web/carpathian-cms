<?php

namespace Plugins\PaymentGateway\Filament\Resources\PaymentGatewayResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Plugins\PaymentGateway\Filament\Resources\PaymentGatewayResource;
use Filament\Notifications\Notification;

class CreatePaymentGateway extends CreateRecord
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Gateway de plată creat')
            ->body('Gateway-ul de plată a fost creat cu succes.');
    }
}
