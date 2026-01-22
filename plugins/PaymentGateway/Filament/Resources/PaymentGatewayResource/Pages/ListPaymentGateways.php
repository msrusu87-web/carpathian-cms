<?php

namespace Plugins\PaymentGateway\Filament\Resources\PaymentGatewayResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Plugins\PaymentGateway\Filament\Resources\PaymentGatewayResource;
use Filament\Actions;

class ListPaymentGateways extends ListRecords
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
