<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class FormExample extends Page
{

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'ตัวอย่าง Form';

    protected static ?string $title = 'ตัวอย่าง Form Components';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.form-example';

    // ============================================
    // ตัวแปรสำหรับเก็บค่าจาก Form
    // ============================================
    
    // TextInput - รับค่า string
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    
    // Textarea - รับค่า string (หลายบรรทัด)
    public ?string $description = null;
    
    // Select - รับค่า string หรือ int
    public ?string $country = null;
    public ?int $age = null;
    
    // Toggle/Checkbox - รับค่า boolean (true/false)
    public bool $is_active = false;
    public bool $agree_terms = false;
    
    // Radio - รับค่า string
    public ?string $gender = null;
    
    // DatePicker - รับค่า string (date format)
    public ?string $birth_date = null;
    
    // FileUpload - รับค่า array ของ file paths
    public ?array $attachments = null;

    /**
     * mount() - เรียกเมื่อโหลดหน้าแรก
     * ใช้สำหรับกำหนดค่าเริ่มต้นให้กับตัวแปร
     */
    public function mount(): void
    {
        // กำหนดค่าเริ่มต้น
        $this->name = 'John Doe';
        $this->is_active = true;
        $this->gender = 'male';
    }

    /**
     * form() - กำหนดโครงสร้าง Form
     * 
     * หลักการ:
     * 1. แต่ละ component จะ bind กับ property โดยใช้ ->make('property_name')
     * 2. ค่าจะถูกส่งไปยัง property อัตโนมัติเมื่อผู้ใช้กรอกข้อมูล
     * 3. ใช้ ->live() เพื่อให้อัปเดตแบบ real-time
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ============================================
                // 1. TextInput - รับข้อความสั้นๆ
                // ============================================
                TextInput::make('name')
                    ->label('ชื่อ')
                    ->placeholder('กรุณากรอกชื่อ')
                    ->required() // บังคับกรอก
                    ->maxLength(255) // จำกัดความยาว
                    ->helperText('ตัวอย่าง: TextInput รับค่า string')
                    ->live() // อัปเดตแบบ real-time
                    ->afterStateUpdated(function ($state) {
                        // เมื่อค่าเปลี่ยน จะเรียก function นี้
                        // $state = ค่าปัจจุบัน
                        // สามารถทำอะไรก็ได้ เช่น validate, calculate, etc.
                    }),

                TextInput::make('email')
                    ->label('อีเมล')
                    ->email() // validate เป็น email format
                    ->required()
                    ->helperText('ตัวอย่าง: TextInput พร้อม email validation'),

                TextInput::make('phone')
                    ->label('เบอร์โทร')
                    ->tel() // validate เป็นเบอร์โทร
                    ->mask('999-999-9999') // กำหนดรูปแบบ mask
                    ->helperText('ตัวอย่าง: TextInput พร้อม mask'),

                // ============================================
                // 2. Textarea - รับข้อความหลายบรรทัด
                // ============================================
                Textarea::make('description')
                    ->label('รายละเอียด')
                    ->rows(4) // จำนวนบรรทัดที่แสดง
                    ->maxLength(500)
                    ->helperText('ตัวอย่าง: Textarea รับค่า string หลายบรรทัด'),

                // ============================================
                // 3. Select - Dropdown list
                // ============================================
                Select::make('country')
                    ->label('ประเทศ')
                    ->options([
                        'th' => 'Thailand',
                        'us' => 'United States',
                        'uk' => 'United Kingdom',
                        'jp' => 'Japan',
                    ])
                    ->searchable() // ค้นหาได้
                    ->preload() // โหลดข้อมูลล่วงหน้า
                    ->helperText('ตัวอย่าง: Select รับค่า string จาก options'),

                Select::make('age')
                    ->label('อายุ')
                    ->options(array_combine(range(18, 80), range(18, 80)))
                    ->searchable()
                    ->helperText('ตัวอย่าง: Select รับค่า int'),

                // ============================================
                // 4. Toggle - สวิตช์เปิด/ปิด
                // ============================================
                Toggle::make('is_active')
                    ->label('เปิดใช้งาน')
                    ->default(true) // ค่าเริ่มต้น
                    ->helperText('ตัวอย่าง: Toggle รับค่า boolean (true/false)'),

                // ============================================
                // 5. Checkbox - ช่องทำเครื่องหมาย
                // ============================================
                Checkbox::make('agree_terms')
                    ->label('ยอมรับข้อกำหนดและเงื่อนไข')
                    ->required()
                    ->helperText('ตัวอย่าง: Checkbox รับค่า boolean'),

                // ============================================
                // 6. Radio - ปุ่มตัวเลือกเดียว
                // ============================================
                Radio::make('gender')
                    ->label('เพศ')
                    ->options([
                        'male' => 'ชาย',
                        'female' => 'หญิง',
                        'other' => 'อื่นๆ',
                    ])
                    ->default('male')
                    ->helperText('ตัวอย่าง: Radio รับค่า string'),

                // ============================================
                // 7. DatePicker - เลือกวันที่
                // ============================================
                DatePicker::make('birth_date')
                    ->label('วันเกิด')
                    ->displayFormat('d/m/Y') // รูปแบบที่แสดง
                    ->native(false) // ใช้ Filament date picker แทน native
                    ->helperText('ตัวอย่าง: DatePicker รับค่า string (date format)'),

                // ============================================
                // 8. FileUpload - อัปโหลดไฟล์
                // ============================================
                FileUpload::make('attachments')
                    ->label('ไฟล์แนบ')
                    ->multiple() // อัปโหลดหลายไฟล์
                    ->acceptedFileTypes(['image/*', 'application/pdf']) // รับเฉพาะรูปภาพและ PDF
                    ->maxSize(5120) // ขนาดสูงสุด 5MB
                    ->directory('attachments') // โฟลเดอร์ที่เก็บไฟล์
                    ->helperText('ตัวอย่าง: FileUpload รับค่า array ของ file paths'),
            ]);
    }

    /**
     * submit() - เรียกเมื่อกดปุ่ม Submit
     * 
     * หลักการ:
     * 1. ค่าทั้งหมดจะถูกส่งมาที่ properties ที่กำหนดไว้แล้ว
     * 2. สามารถเข้าถึงค่าผ่าน $this->property_name
     * 3. ใช้ validate() เพื่อตรวจสอบข้อมูล
     */
    public function submit(): void
    {
        // ใน Filament 4.5 เมื่อใช้ Schema ค่าจะถูก sync ไปยัง properties อัตโนมัติ
        // ดังนั้นเราสามารถเข้าถึงค่าจาก properties โดยตรง
        
        $name = $this->name;
        $email = $this->email;
        $isActive = $this->is_active;

        // แสดงผลลัพธ์ (ในกรณีจริงจะบันทึกลง database)
        \Filament\Notifications\Notification::make()
            ->title('บันทึกข้อมูลสำเร็จ!')
            ->body("ชื่อ: {$name}, อีเมล: {$email}, สถานะ: " . ($isActive ? 'เปิดใช้งาน' : 'ปิดใช้งาน'))
            ->success()
            ->send();

        // หรือใช้ session flash message
        session()->flash('message', 'บันทึกข้อมูลสำเร็จ!');
    }

    /**
     * resetForm() - รีเซ็ตฟอร์ม
     */
    public function resetForm(): void
    {
        // รีเซ็ตค่าทั้งหมดโดยกำหนดค่าให้ properties โดยตรง
        $this->name = null;
        $this->email = null;
        $this->phone = null;
        $this->description = null;
        $this->country = null;
        $this->age = null;
        $this->is_active = false;
        $this->agree_terms = false;
        $this->gender = null;
        $this->birth_date = null;
        $this->attachments = null;
    }

    /**
     * getFormData() - ดึงข้อมูลจาก properties มาแสดงในฟอร์ม
     * 
     * หลักการ:
     * - เมื่อโหลดหน้า ฟอร์มจะดึงค่าจาก properties อัตโนมัติ
     * - หรือใช้ fill() เพื่อกำหนดค่า
     */
    protected function getFormData(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'description' => $this->description,
            'country' => $this->country,
            'age' => $this->age,
            'is_active' => $this->is_active,
            'agree_terms' => $this->agree_terms,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'attachments' => $this->attachments,
        ];
    }
}
