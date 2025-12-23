<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate invoice number if not set
        if (empty($data['invoice_number'])) {
            $data['invoice_number'] = \App\Models\Invoice::generateInvoiceNumber();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Recalculate totals after creating invoice with items
        $this->record->calculateTotals();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
