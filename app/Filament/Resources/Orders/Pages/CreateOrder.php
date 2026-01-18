<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Calculate totals from order items
        $subtotal = 0;
        if (isset($data['orderItems']) && is_array($data['orderItems'])) {
            foreach ($data['orderItems'] as $item) {
                $subtotal += ($item['line_total'] ?? 0);
            }
        }
        
        $data['subtotal'] = $subtotal;
        $data['total'] = $subtotal;
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        // Recalculate totals after order items are saved
        $this->record->calculateTotals();
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
