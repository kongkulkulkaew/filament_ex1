<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

/**
 * ListCustomers - หน้าแสดงรายการลูกค้า
 * 
 * หลักการ:
 * - ใช้ Filament ListRecords page
 * - เพิ่ม custom methods และ actions ที่เขียนเอง
 * - ผสมระหว่าง Filament features กับ custom code
 */
class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    /**
     * getHeaderActions() - Actions ที่แสดงด้านบน
     * 
     * ใช้ Filament Actions แต่เพิ่ม custom logic
     */
    protected function getHeaderActions(): array
    {
        return [
            // Filament Action: สร้างลูกค้าใหม่
            CreateAction::make()
                ->label('สร้างลูกค้าใหม่')
                ->icon('heroicon-o-plus')
                ->color('success'),

            // Custom Action: ส่งออกข้อมูล (Export)
            Action::make('export')
                ->label('ส่งออกข้อมูล')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->action(function () {
                    // Custom logic: ส่งออกข้อมูลลูกค้า
                    $customers = \App\Models\Customer::all();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('ส่งออกข้อมูล')
                        ->body('ส่งออกข้อมูลลูกค้า ' . $customers->count() . ' รายการ')
                        ->success()
                        ->send();
                    
                    // ในโปรเจคจริงควรส่งออกเป็น CSV หรือ Excel
                    // return response()->download($file);
                }),

            // Custom Action: นำเข้าข้อมูล (Import)
            Action::make('import')
                ->label('นำเข้าข้อมูล')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->action(function () {
                    // Custom logic: นำเข้าข้อมูลลูกค้า
                    \Filament\Notifications\Notification::make()
                        ->title('นำเข้าข้อมูล')
                        ->body('กรุณาเลือกไฟล์ที่ต้องการนำเข้า')
                        ->info()
                        ->send();
                    
                    // ในโปรเจคจริงควรเปิด modal เพื่ออัปโหลดไฟล์
                }),

            // Custom Action: สถิติลูกค้า
            Action::make('statistics')
                ->label('สถิติ')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->modalHeading('สถิติลูกค้า')
                ->modalContent(function () {
                    // Custom logic: คำนวณสถิติ
                    $totalCustomers = \App\Models\Customer::count();
                    $customersWithOrders = \App\Models\Customer::has('orders')->count();
                    $customersWithoutOrders = \App\Models\Customer::doesntHave('orders')->count();
                    $totalOrders = \App\Models\Customer::withCount('orders')->get()->sum('orders_count');
                    
                    return view('filament.resources.customers.statistics', [
                        'totalCustomers' => $totalCustomers,
                        'customersWithOrders' => $customersWithOrders,
                        'customersWithoutOrders' => $customersWithoutOrders,
                        'totalOrders' => $totalOrders,
                    ]);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('ปิด'),
        ];
    }

    /**
     * getHeaderWidgets() - Widgets ที่แสดงด้านบน
     * 
     * สามารถเพิ่ม widgets ได้ที่นี่
     */
    protected function getHeaderWidgets(): array
    {
        return [
            // สามารถเพิ่ม widgets ได้ที่นี่
        ];
    }
}
