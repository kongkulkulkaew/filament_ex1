<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

/**
 * EditCustomer - หน้าแก้ไขลูกค้า
 * 
 * หลักการ:
 * - ใช้ Filament EditRecord page
 * - เพิ่ม custom methods และ validation ที่เขียนเอง
 * - ผสมระหว่าง Filament features กับ custom code
 */
class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    /**
     * getHeaderActions() - Actions ที่แสดงด้านบน
     * 
     * Custom actions: เพิ่ม actions ที่เขียนเอง
     */
    protected function getHeaderActions(): array
    {
        return [
            // Custom Action: ดูออเดอร์ของลูกค้า
            Action::make('view_orders')
                ->label('ดูออเดอร์')
                ->icon('heroicon-o-shopping-cart')
                ->color('info')
                ->url(fn () => \App\Filament\Resources\Orders\OrderResource::getUrl('index', [
                    'tableFilters' => [
                        'customer_id' => [
                            'value' => $this->record->id,
                        ],
                    ],
                ]))
                ->visible(fn () => $this->record->orders()->exists()),

            // Custom Action: สร้างออเดอร์ใหม่
            Action::make('create_order')
                ->label('สร้างออเดอร์ใหม่')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->url(fn () => \App\Filament\Resources\Orders\OrderResource::getUrl('create', [
                    'customer_id' => $this->record->id,
                ])),

            // Filament Action: ลบ
            DeleteAction::make()
                ->label('ลบลุกค้า')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('ยืนยันการลบ')
                ->modalDescription('คุณแน่ใจหรือไม่ว่าต้องการลบลุกค้านี้? การลบจะไม่สามารถยกเลิกได้')
                ->action(function () {
                    // Custom logic: ตรวจสอบว่ามีออเดอร์หรือไม่
                    if ($this->record->orders()->exists()) {
                        throw ValidationException::withMessages([
                            'error' => 'ไม่สามารถลบได้ เพราะลูกค้านี้มีออเดอร์อยู่',
                        ]);
                    }
                    
                    // ลบลูกค้า
                    $this->record->delete();
                    
                    // Redirect ไปที่หน้า list
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }

    /**
     * mutateFormDataBeforeSave() - แก้ไขข้อมูลก่อนบันทึก
     * 
     * Custom logic: เพิ่ม validation และ logic ก่อนบันทึก
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Custom logic: ตรวจสอบอีเมลซ้ำ (ยกเว้นตัวเอง)
        if (isset($data['email'])) {
            $existingCustomer = Customer::where('email', $data['email'])
                ->where('id', '!=', $this->record->id)
                ->first();
            
            if ($existingCustomer) {
                throw ValidationException::withMessages([
                    'email' => 'อีเมลนี้ถูกใช้งานแล้วโดยลูกค้าอื่น',
                ]);
            }
        }
        
        // Custom logic: แปลงชื่อเป็นตัวพิมพ์ใหญ่ตัวแรก
        if (isset($data['name'])) {
            $data['name'] = mb_convert_case($data['name'], MB_CASE_TITLE, 'UTF-8');
        }
        
        // Custom logic: แปลงอีเมลเป็นตัวพิมพ์เล็ก
        if (isset($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }
        
        // Custom logic: ลบเครื่องหมายขีดออกจากเบอร์โทร
        if (isset($data['phone'])) {
            $data['phone'] = str_replace('-', '', $data['phone']);
        }
        
        return $data;
    }

    /**
     * afterSave() - เรียกหลังจากบันทึกสำเร็จ
     * 
     * Custom logic: ทำอะไรบางอย่างหลังจากบันทึกสำเร็จ
     */
    protected function afterSave(): void
    {
        $customer = $this->record;
        
        // Custom logic: บันทึก log
        \Log::info('แก้ไขลูกค้า: ' . $customer->name, [
            'customer_id' => $customer->id,
            'changes' => $customer->getChanges(),
        ]);
        
        // Custom logic: สร้าง notification
        \Filament\Notifications\Notification::make()
            ->title('บันทึกการแก้ไขสำเร็จ!')
            ->body("แก้ไขข้อมูลลูกค้า: {$customer->name}")
            ->success()
            ->send();
    }

    /**
     * getRedirectUrl() - URL ที่จะ redirect หลังบันทึกเสร็จ
     * 
     * Custom logic: กำหนดว่าจะ redirect ไปที่ไหน
     */
    protected function getRedirectUrl(): string
    {
        // กลับไปที่หน้า list
        return $this->getResource()::getUrl('index');
        
        // หรืออยู่ที่หน้า edit เดิม
        // return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
