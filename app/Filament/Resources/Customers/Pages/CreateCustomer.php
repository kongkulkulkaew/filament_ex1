<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

/**
 * CreateCustomer - หน้าสร้างลูกค้าใหม่
 * 
 * หลักการ:
 * - ใช้ Filament CreateRecord page
 * - เพิ่ม custom methods และ validation ที่เขียนเอง
 * - ผสมระหว่าง Filament features กับ custom code
 */
class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    /**
     * mutateFormDataBeforeCreate() - แก้ไขข้อมูลก่อนบันทึก
     * 
     * Custom logic: เพิ่มข้อมูลเพิ่มเติมก่อนบันทึก
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Custom logic: สร้าง customer code อัตโนมัติ
        $data['customer_code'] = $this->generateCustomerCode();
        
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
     * afterCreate() - เรียกหลังจากสร้างสำเร็จ
     * 
     * Custom logic: ทำอะไรบางอย่างหลังจากสร้างสำเร็จ
     */
    protected function afterCreate(): void
    {
        $customer = $this->record;
        
        // Custom logic: ส่งอีเมลต้อนรับ (ตัวอย่าง)
        // Mail::to($customer->email)->send(new WelcomeEmail($customer));
        
        // Custom logic: บันทึก log
        \Log::info('สร้างลูกค้าใหม่: ' . $customer->name, [
            'customer_id' => $customer->id,
            'email' => $customer->email,
        ]);
        
        // Custom logic: สร้าง notification
        \Filament\Notifications\Notification::make()
            ->title('สร้างลูกค้าใหม่สำเร็จ!')
            ->body("ลูกค้า: {$customer->name} (รหัส: {$customer->customer_code})")
            ->success()
            ->send();
    }

    /**
     * getRedirectUrl() - URL ที่จะ redirect หลังสร้างเสร็จ
     * 
     * Custom logic: กำหนดว่าจะ redirect ไปที่ไหน
     */
    protected function getRedirectUrl(): string
    {
        // กลับไปที่หน้า list
        return $this->getResource()::getUrl('index');
        
        // หรือไปที่หน้า edit
        // return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    /**
     * generateCustomerCode() - สร้างรหัสลูกค้าอัตโนมัติ
     * 
     * Custom method: เขียนเอง
     */
    protected function generateCustomerCode(): string
    {
        // Custom logic: สร้างรหัสลูกค้าแบบ CUS-000001
        $lastCustomer = Customer::orderBy('id', 'desc')->first();
        $nextId = $lastCustomer ? $lastCustomer->id + 1 : 1;
        
        return 'CUS-' . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }
}
