<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * CustomerResource - Resource class สำหรับจัดการ CRUD ลูกค้า
 * 
 * หลักการ:
 * - ใช้ Filament Resource เพื่อจัดการ CRUD อัตโนมัติ
 * - เพิ่ม custom methods และ logic ที่เขียนเอง
 * - ผสมระหว่าง Filament features กับ custom code
 */
class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'ลูกค้า';

    protected static ?string $modelLabel = 'ลูกค้า';

    protected static ?string $pluralModelLabel = 'ลูกค้าทั้งหมด';

    protected static UnitEnum|string|null $navigationGroup = 'การขาย';

    protected static ?int $navigationSort = 2;

    /**
     * form() - กำหนด Form Schema
     * 
     * ใช้ Filament Form Components แต่เพิ่ม custom logic
     */
    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    /**
     * table() - กำหนด Table Schema
     * 
     * ใช้ Filament Table Components แต่เพิ่ม custom actions และ logic
     */
    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
    }

    /**
     * getRelations() - กำหนด Relations
     * 
     * แสดง relations ที่เกี่ยวข้อง (เช่น Orders)
     */
    public static function getRelations(): array
    {
        return [
            // สามารถเพิ่ม Relations ได้ที่นี่
        ];
    }

    /**
     * getPages() - กำหนด Pages
     * 
     * แต่ละ Page จะมี custom methods ที่เขียนเอง
     */
    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
