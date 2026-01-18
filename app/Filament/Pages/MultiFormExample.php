<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class MultiFormExample extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected string $view = 'filament.pages.multi-form-example';

    protected static ?string $navigationLabel = 'ตัวอย่างหลาย Form';

    protected static ?string $title = 'ตัวอย่างหลาย Form - การส่งรับค่าหลายรูปแบบ';

    protected static ?int $navigationSort = 9;

    // ============================================
    // Form 1: แบบปกติ (Submit)
    // ============================================
    public ?string $form1_name = null;
    public ?string $form1_email = null;
    public ?string $form1_message = null;

    // ============================================
    // Form 2: แบบ Real-time (Live)
    // ============================================
    public ?string $form2_search = null;
    public ?int $form2_number = null;
    public ?string $form2_result = null;

    // ============================================
    // Form 3: แบบ Conditional (แสดงตามเงื่อนไข)
    // ============================================
    public ?string $form3_type = null;
    public ?string $form3_student_id = null;
    public ?string $form3_employee_code = null;
    public ?string $form3_company_name = null;

    // ============================================
    // Form 4: แบบ Multiple Steps
    // ============================================
    public int $currentStep = 1;
    public ?string $step1_name = null;
    public ?string $step1_email = null;
    public ?string $step2_address = null;
    public ?string $step2_phone = null;
    public ?string $step3_notes = null;

    // ============================================
    // Form 5: แบบ Auto-calculate
    // ============================================
    public ?float $form5_price = null;
    public ?int $form5_quantity = null;
    public ?float $form5_discount = null;
    public ?float $form5_total = null;

    /**
     * mount() - กำหนดค่าเริ่มต้น
     */
    public function mount(): void
    {
        $this->form2_number = 0;
        $this->form5_price = 100.0;
        $this->form5_quantity = 1;
        $this->form5_discount = 0.0;
    }

    /**
     * form() - Form หลัก (Schema)
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components([]); // ใช้ form แยก
    }

    /**
     * getForm1Schema() - Form แบบปกติ (Submit)
     * 
     * หลักการ:
     * - ค่าจะถูกเก็บใน properties
     * - เมื่อกด submit จะเรียก method submitForm1()
     * - ใช้ wire:submit="submitForm1" ใน view
     */
    public function getForm1Schema(): Schema
    {
        return Schema::make()
            ->livewire($this)
            ->components([
                TextInput::make('form1_name')
                    ->label('ชื่อ')
                    ->required()
                    ->helperText('Form 1: แบบปกติ - ค่าจะถูกส่งเมื่อกด Submit'),

                TextInput::make('form1_email')
                    ->label('อีเมล')
                    ->email()
                    ->required(),

                Textarea::make('form1_message')
                    ->label('ข้อความ')
                    ->rows(3)
                    ->required(),
            ]);
    }

    /**
     * getForm2Schema() - Form แบบ Real-time (Live)
     * 
     * หลักการ:
     * - ใช้ ->live() เพื่อให้อัปเดตแบบ real-time
     * - ค่าจะถูกส่งไปยัง property ทันทีเมื่อเปลี่ยน
     * - สามารถเรียก method อัตโนมัติเมื่อค่าเปลี่ยน
     */
    public function getForm2Schema(): Schema
    {
        return Schema::make()
            ->livewire($this)
            ->components([
                TextInput::make('form2_search')
                    ->label('ค้นหา')
                    ->placeholder('พิมพ์เพื่อค้นหา...')
                    ->live() // Real-time updates
                    ->debounce(500) // Debounce 500ms
                    ->helperText('Form 2: แบบ Real-time - ค่าจะถูกส่งทันทีเมื่อพิมพ์ (debounce 500ms)'),

                TextInput::make('form2_number')
                    ->label('ตัวเลข')
                    ->numeric()
                    ->live() // Real-time updates
                    ->debounce(500) // Debounce 500ms
                    ->helperText('ค่าจะถูกคำนวณอัตโนมัติเมื่อเปลี่ยน'),
            ]);
    }

    /**
     * getForm3Schema() - Form แบบ Conditional (แสดงตามเงื่อนไข)
     * 
     * หลักการ:
     * - ใช้ ->live() เพื่อให้อัปเดตแบบ real-time
     * - ใช้ ->visible() เพื่อแสดง/ซ่อน field ตามเงื่อนไข
     * - ใช้ $get() เพื่ออ่านค่าจาก field อื่น
     */
    public function getForm3Schema(): Schema
    {
        return Schema::make()
            ->livewire($this)
            ->components([
                Select::make('form3_type')
                    ->label('ประเภท')
                    ->options([
                        'student' => 'นักเรียน',
                        'employee' => 'พนักงาน',
                        'company' => 'บริษัท',
                    ])
                    ->live() // ต้องใช้ live() เพื่อให้ visible() ทำงาน
                    ->required()
                    ->helperText('Form 3: แบบ Conditional - แสดง field ตามเงื่อนไข'),

                TextInput::make('form3_student_id')
                    ->label('รหัสนักเรียน')
                    ->visible(fn ($get) => $get('form3_type') === 'student') // แสดงเมื่อเลือก student
                    ->required(fn ($get) => $get('form3_type') === 'student'),

                TextInput::make('form3_employee_code')
                    ->label('รหัสพนักงาน')
                    ->visible(fn ($get) => $get('form3_type') === 'employee') // แสดงเมื่อเลือก employee
                    ->required(fn ($get) => $get('form3_type') === 'employee'),

                TextInput::make('form3_company_name')
                    ->label('ชื่อบริษัท')
                    ->visible(fn ($get) => $get('form3_type') === 'company') // แสดงเมื่อเลือก company
                    ->required(fn ($get) => $get('form3_type') === 'company'),
            ]);
    }

    /**
     * getForm4Schema() - Form แบบ Multiple Steps
     * 
     * หลักการ:
     * - แบ่ง form เป็นหลายขั้นตอน
     * - ใช้ property $currentStep เพื่อเก็บขั้นตอนปัจจุบัน
     * - แสดง field ตามขั้นตอน
     */
    public function getForm4Schema(): Schema
    {
        return Schema::make()
            ->livewire($this)
            ->components([
                // Step 1: ข้อมูลส่วนตัว
                TextInput::make('step1_name')
                    ->label('ชื่อ')
                    ->visible(fn () => $this->currentStep === 1)
                    ->required(),

                TextInput::make('step1_email')
                    ->label('อีเมล')
                    ->email()
                    ->visible(fn () => $this->currentStep === 1)
                    ->required(),

                // Step 2: ที่อยู่ติดต่อ
                Textarea::make('step2_address')
                    ->label('ที่อยู่')
                    ->rows(3)
                    ->visible(fn () => $this->currentStep === 2)
                    ->required(),

                TextInput::make('step2_phone')
                    ->label('เบอร์โทร')
                    ->tel()
                    ->visible(fn () => $this->currentStep === 2)
                    ->required(),

                // Step 3: หมายเหตุ
                Textarea::make('step3_notes')
                    ->label('หมายเหตุ')
                    ->rows(4)
                    ->visible(fn () => $this->currentStep === 3),
            ]);
    }

    /**
     * getForm5Schema() - Form แบบ Auto-calculate
     * 
     * หลักการ:
     * - ใช้ ->live() เพื่อให้อัปเดตแบบ real-time
     * - ใช้ ->afterStateUpdated() เพื่อคำนวณอัตโนมัติ
     * - ใช้ $set() เพื่อกำหนดค่าให้ field อื่น
     */
    public function getForm5Schema(): Schema
    {
        return Schema::make()
            ->livewire($this)
            ->components([
                TextInput::make('form5_price')
                    ->label('ราคา')
                    ->numeric()
                    ->prefix('฿')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // คำนวณอัตโนมัติเมื่อราคาเปลี่ยน
                        $price = (float) ($get('form5_price') ?? 0);
                        $quantity = (int) ($get('form5_quantity') ?? 1);
                        $discount = (float) ($get('form5_discount') ?? 0);
                        $subtotal = $price * $quantity;
                        $discountAmount = $subtotal * ($discount / 100);
                        $total = $subtotal - $discountAmount;
                        $set('form5_total', round($total, 2));
                    }),

                TextInput::make('form5_quantity')
                    ->label('จำนวน')
                    ->numeric()
                    ->default(1)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // คำนวณอัตโนมัติเมื่อจำนวนเปลี่ยน
                        $price = (float) ($get('form5_price') ?? 0);
                        $quantity = (int) ($get('form5_quantity') ?? 1);
                        $discount = (float) ($get('form5_discount') ?? 0);
                        $subtotal = $price * $quantity;
                        $discountAmount = $subtotal * ($discount / 100);
                        $total = $subtotal - $discountAmount;
                        $set('form5_total', round($total, 2));
                    }),

                TextInput::make('form5_discount')
                    ->label('ส่วนลด (%)')
                    ->numeric()
                    ->suffix('%')
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // คำนวณอัตโนมัติเมื่อส่วนลดเปลี่ยน
                        $price = (float) ($get('form5_price') ?? 0);
                        $quantity = (int) ($get('form5_quantity') ?? 1);
                        $discount = (float) ($get('form5_discount') ?? 0);
                        $subtotal = $price * $quantity;
                        $discountAmount = $subtotal * ($discount / 100);
                        $total = $subtotal - $discountAmount;
                        $set('form5_total', round($total, 2));
                    }),

                TextInput::make('form5_total')
                    ->label('ยอดรวม')
                    ->numeric()
                    ->prefix('฿')
                    ->disabled() // ไม่ให้แก้ไข
                    ->dehydrated(), // แต่ยังส่งค่าไปด้วย
            ]);
    }

    // ============================================
    // Methods สำหรับ Form 1: Submit
    // ============================================

    /**
     * submitForm1() - เรียกเมื่อกด Submit Form 1
     * 
     * หลักการ:
     * - ค่าจะถูกส่งมาที่ properties ($this->form1_name, etc.)
     * - สามารถ validate และประมวลผลได้
     */
    public function submitForm1(): void
    {
        // อ่านค่าจาก properties
        $name = $this->form1_name;
        $email = $this->form1_email;
        $message = $this->form1_message;

        // แสดงผลลัพธ์
        \Filament\Notifications\Notification::make()
            ->title('Form 1: บันทึกสำเร็จ!')
            ->body("ชื่อ: {$name}, อีเมล: {$email}")
            ->success()
            ->send();

        // หรือใช้ session
        session()->flash('form1_success', 'บันทึกข้อมูลสำเร็จ!');
    }

    /**
     * resetForm1() - รีเซ็ต Form 1
     */
    public function resetForm1(): void
    {
        $this->form1_name = null;
        $this->form1_email = null;
        $this->form1_message = null;
    }

    // ============================================
    // Methods สำหรับ Form 2: Real-time
    // ============================================

    /**
     * updatedForm2Search() - เรียกอัตโนมัติเมื่อ form2_search เปลี่ยน (Livewire hook)
     * 
     * หลักการ:
     * - Livewire จะเรียก method นี้อัตโนมัติเมื่อ property form2_search เปลี่ยน
     * - ใช้ naming convention: updated{PropertyName}()
     */
    public function updatedForm2Search(): void
    {
        // ค่าถูกส่งมาที่ $this->form2_search อัตโนมัติ
        $search = $this->form2_search;
        
        if ($search) {
            // ประมวลผลการค้นหา
            $this->form2_result = "ผลการค้นหา: {$search}";
        } else {
            $this->form2_result = null;
        }
    }

    /**
     * updatedForm2Number() - เรียกอัตโนมัติเมื่อ form2_number เปลี่ยน (Livewire hook)
     * 
     * หลักการ:
     * - Livewire จะเรียก method นี้อัตโนมัติเมื่อ property form2_number เปลี่ยน
     * - ใช้ naming convention: updated{PropertyName}()
     */
    public function updatedForm2Number(): void
    {
        $number = $this->form2_number ?? 0;
        
        // คำนวณผลลัพธ์
        $result = $number * 2;
        $this->form2_result = "{$number} × 2 = {$result}";
    }

    // ============================================
    // Methods สำหรับ Form 4: Multiple Steps
    // ============================================

    /**
     * nextStep() - ไปขั้นตอนถัดไป
     */
    public function nextStep(): void
    {
        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    /**
     * previousStep() - กลับขั้นตอนก่อนหน้า
     */
    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * submitForm4() - Submit Form 4 (ขั้นตอนสุดท้าย)
     */
    public function submitForm4(): void
    {
        // อ่านค่าทั้งหมด
        $data = [
            'name' => $this->step1_name,
            'email' => $this->step1_email,
            'address' => $this->step2_address,
            'phone' => $this->step2_phone,
            'notes' => $this->step3_notes,
        ];

        \Filament\Notifications\Notification::make()
            ->title('Form 4: บันทึกสำเร็จ!')
            ->body('บันทึกข้อมูลทั้งหมดแล้ว')
            ->success()
            ->send();
    }

    // ============================================
    // Methods สำหรับ Form 5: Auto-calculate
    // ============================================

    /**
     * updatedForm5Price() - เรียกอัตโนมัติเมื่อ form5_price เปลี่ยน (Livewire hook)
     */
    public function updatedForm5Price(): void
    {
        $this->calculateTotal();
    }

    /**
     * updatedForm5Quantity() - เรียกอัตโนมัติเมื่อ form5_quantity เปลี่ยน (Livewire hook)
     */
    public function updatedForm5Quantity(): void
    {
        $this->calculateTotal();
    }

    /**
     * updatedForm5Discount() - เรียกอัตโนมัติเมื่อ form5_discount เปลี่ยน (Livewire hook)
     */
    public function updatedForm5Discount(): void
    {
        $this->calculateTotal();
    }

    /**
     * calculateTotal() - คำนวณยอดรวมอัตโนมัติ
     * 
     * หลักการ:
     * - เรียกจาก updatedForm5Price(), updatedForm5Quantity(), updatedForm5Discount()
     * - อ่านค่าจาก properties โดยตรง
     * - อัปเดต form5_total property
     */
    public function calculateTotal(): void
    {
        $price = (float) ($this->form5_price ?? 0);
        $quantity = (int) ($this->form5_quantity ?? 1);
        $discount = (float) ($this->form5_discount ?? 0);

        // คำนวณยอดรวม
        $subtotal = $price * $quantity;
        $discountAmount = $subtotal * ($discount / 100);
        $total = $subtotal - $discountAmount;

        // อัปเดต property
        $this->form5_total = round($total, 2);
    }

    /**
     * submitForm5() - Submit Form 5
     */
    public function submitForm5(): void
    {
        $total = $this->form5_total ?? 0;

        \Filament\Notifications\Notification::make()
            ->title('Form 5: บันทึกสำเร็จ!')
            ->body("ยอดรวม: ฿" . number_format($total, 2))
            ->success()
            ->send();
    }
}
