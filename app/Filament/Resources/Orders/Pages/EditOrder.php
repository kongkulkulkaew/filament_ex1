<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('ลบออเดอร์')
                ->requiresConfirmation()
                ->visible(fn ($record) => auth()->user()?->can('delete', $record)),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Validate status change - can't cancel if already shipped
        if (isset($data['status'])) {
            // Check if status is already an enum instance or needs conversion
            $newStatus = $data['status'] instanceof OrderStatus 
                ? $data['status'] 
                : OrderStatus::from($data['status']);
            
            $currentStatus = $this->record->status;
            
            if ($newStatus === OrderStatus::Cancelled && !$currentStatus->canBeCancelled()) {
                throw ValidationException::withMessages([
                    'status' => 'ไม่สามารถยกเลิกออเดอร์ที่จัดส่งแล้วได้',
                ]);
            }
        }
        
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
    
    protected function afterSave(): void
    {
        // Recalculate totals after order items are saved
        $this->record->calculateTotals();
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
