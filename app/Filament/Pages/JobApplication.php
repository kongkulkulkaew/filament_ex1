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

class JobApplication extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected string $view = 'filament.pages.job-application';

    protected static ?string $navigationLabel = 'ใบรับสมัครงาน';

    protected static ?string $title = 'ใบรับสมัครงาน - ตัวอย่าง Form Components ครบถ้วน';

    protected static ?int $navigationSort = 10;

    // ============================================
    // Form 1: ข้อมูลส่วนตัว (Personal Information)
    // ============================================
    
    // TextInput - ข้อความสั้นๆ
    public ?string $first_name = null;
    public ?string $last_name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $id_card = null; // บัตรประชาชน
    
    // DatePicker - วันที่
    public ?string $birth_date = null;
    public ?string $available_start_date = null;
    
    // Select - เลือกจากรายการ
    public ?string $gender = null;
    public ?string $marital_status = null;
    public ?string $nationality = null;
    public ?string $religion = null;
    public ?string $education_level = null;
    public ?string $position_applied = null;
    public ?string $expected_salary = null;
    
    // Textarea - ข้อความหลายบรรทัด
    public ?string $address = null;
    public ?string $current_address = null;
    
    // Radio - เลือกตัวเลือกเดียว
    public ?string $work_experience = null; // มีประสบการณ์/ไม่มีประสบการณ์
    
    // Toggle - สวิตช์เปิด/ปิด
    public bool $has_vehicle = false;
    public bool $can_travel = false;
    public bool $can_relocate = false;
    
    // Checkbox - ช่องทำเครื่องหมาย
    public bool $agree_terms = false;
    public bool $agree_data_collection = false;

    // ============================================
    // Form 2: ข้อมูลเพิ่มเติม (Additional Information)
    // ============================================
    
    // TextInput - ตัวเลข
    public ?int $years_of_experience = null;
    public ?int $previous_salary = null;
    
    // Textarea - ประสบการณ์การทำงาน
    public ?string $work_history = null;
    public ?string $skills = null;
    public ?string $languages = null;
    public ?string $references = null;
    public ?string $additional_info = null;
    
    // FileUpload - อัปโหลดไฟล์
    public ?array $resume = null; // Resume/CV
    public ?array $portfolio = null; // Portfolio
    public ?array $certificates = null; // ใบรับรอง
    
    // Select - Multiple selection
    public ?array $preferred_work_days = null; // วันทำงานที่ต้องการ
    public ?array $preferred_work_time = null; // เวลาทำงานที่ต้องการ
    
    // Toggle
    public bool $has_references = false;
    
    // TextInput - Conditional fields
    public ?string $reference_name = null;
    public ?string $reference_position = null;
    public ?string $reference_phone = null;
    public ?string $reference_company = null;

    /**
     * mount() - กำหนดค่าเริ่มต้น
     */
    public function mount(): void
    {
        // กำหนดค่าเริ่มต้นบางค่า
        $this->nationality = 'thai';
        $this->religion = 'buddhism';
        $this->marital_status = 'single';
        $this->work_experience = 'no';
        $this->can_travel = true;
        $this->agree_terms = false;
        $this->agree_data_collection = false;
    }

    /**
     * form() - Form หลัก (Schema)
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components([]); // ใช้ form แยก
    }

    /**
     * getPersonalInfoSchema() - Form 1: ข้อมูลส่วนตัว
     * 
     * ประกอบด้วย:
     * - TextInput (ชื่อ, นามสกุล, อีเมล, เบอร์โทร, บัตรประชาชน)
     * - DatePicker (วันเกิด, วันที่เริ่มงานได้)
     * - Select (เพศ, สถานะสมรส, สัญชาติ, ศาสนา, ระดับการศึกษา, ตำแหน่งที่สมัคร, เงินเดือนที่คาดหวัง)
     * - Textarea (ที่อยู่, ที่อยู่ปัจจุบัน)
     * - Radio (มีประสบการณ์/ไม่มีประสบการณ์)
     * - Toggle (มีรถยนต์, สามารถเดินทางได้, สามารถย้ายที่อยู่ได้)
     * - Checkbox (ยอมรับข้อกำหนด)
     */
    public function getPersonalInfoSchema(): Schema
    {
        return Schema::make()
            ->livewire($this)
            ->components([
                // ============================================
                // ส่วนที่ 1: ข้อมูลพื้นฐาน
                // ============================================
                TextInput::make('first_name')
                    ->label('ชื่อ')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('กรุณากรอกชื่อ')
                    ->helperText('ตัวอย่าง: TextInput - รับค่า string (ข้อความสั้นๆ)'),

                TextInput::make('last_name')
                    ->label('นามสกุล')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('กรุณากรอกนามสกุล'),

                TextInput::make('email')
                    ->label('อีเมล')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('example@email.com')
                    ->helperText('ตัวอย่าง: TextInput พร้อม email validation'),

                TextInput::make('phone')
                    ->label('เบอร์โทรศัพท์')
                    ->tel()
                    ->required()
                    ->mask('999-999-9999')
                    ->placeholder('08X-XXX-XXXX')
                    ->helperText('ตัวอย่าง: TextInput พร้อม mask (รูปแบบเบอร์โทร)'),

                TextInput::make('id_card')
                    ->label('เลขบัตรประชาชน')
                    ->required()
                    ->mask('9-9999-99999-99-9')
                    ->placeholder('X-XXXX-XXXXX-XX-X')
                    ->helperText('ตัวอย่าง: TextInput พร้อม mask (รูปแบบบัตรประชาชน)'),

                // ============================================
                // ส่วนที่ 2: ข้อมูลส่วนตัว
                // ============================================
                DatePicker::make('birth_date')
                    ->label('วันเกิด')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->maxDate(now()->subYears(18))
                    ->minDate(now()->subYears(65))
                    ->helperText('ตัวอย่าง: DatePicker - รับค่า string (วันที่)'),

                Select::make('gender')
                    ->label('เพศ')
                    ->options([
                        'male' => 'ชาย',
                        'female' => 'หญิง',
                        'other' => 'อื่นๆ',
                    ])
                    ->required()
                    ->native(false)
                    ->helperText('ตัวอย่าง: Select - รับค่า string จาก options'),

                Select::make('marital_status')
                    ->label('สถานะสมรส')
                    ->options([
                        'single' => 'โสด',
                        'married' => 'สมรส',
                        'divorced' => 'หย่า',
                        'widowed' => 'หม้าย',
                    ])
                    ->required()
                    ->native(false)
                    ->default('single'),

                Select::make('nationality')
                    ->label('สัญชาติ')
                    ->options([
                        'thai' => 'ไทย',
                        'other' => 'อื่นๆ',
                    ])
                    ->required()
                    ->native(false)
                    ->default('thai')
                    ->live()
                    ->helperText('ตัวอย่าง: Select พร้อม ->live() เพื่อให้อัปเดตแบบ real-time'),

                Select::make('religion')
                    ->label('ศาสนา')
                    ->options([
                        'buddhism' => 'พุทธ',
                        'christianity' => 'คริสต์',
                        'islam' => 'อิสลาม',
                        'hinduism' => 'ฮินดู',
                        'other' => 'อื่นๆ',
                        'none' => 'ไม่มี',
                    ])
                    ->required()
                    ->native(false)
                    ->default('buddhism'),

                // ============================================
                // ส่วนที่ 3: ที่อยู่
                // ============================================
                Textarea::make('address')
                    ->label('ที่อยู่ตามบัตรประชาชน')
                    ->required()
                    ->rows(4)
                    ->maxLength(500)
                    ->placeholder('กรุณากรอกที่อยู่ตามบัตรประชาชน')
                    ->helperText('ตัวอย่าง: Textarea - รับค่า string (ข้อความหลายบรรทัด)'),

                Textarea::make('current_address')
                    ->label('ที่อยู่ปัจจุบัน (ถ้าแตกต่างจากบัตรประชาชน)')
                    ->rows(4)
                    ->maxLength(500)
                    ->placeholder('กรุณากรอกที่อยู่ปัจจุบัน')
                    ->visible(fn ($get) => $get('address') !== null)
                    ->helperText('ตัวอย่าง: Textarea พร้อม ->visible() เพื่อแสดงเมื่อมีที่อยู่ตามบัตรแล้ว'),

                // ============================================
                // ส่วนที่ 4: การศึกษาและตำแหน่ง
                // ============================================
                Select::make('education_level')
                    ->label('ระดับการศึกษา')
                    ->options([
                        'high_school' => 'มัธยมศึกษาตอนปลาย',
                        'vocational' => 'ประกาศนียบัตรวิชาชีพ (ปวช.)',
                        'diploma' => 'ประกาศนียบัตรวิชาชีพชั้นสูง (ปวส.)',
                        'bachelor' => 'ปริญญาตรี',
                        'master' => 'ปริญญาโท',
                        'doctorate' => 'ปริญญาเอก',
                    ])
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->helperText('ตัวอย่าง: Select พร้อม ->searchable() และ ->preload()'),

                Select::make('position_applied')
                    ->label('ตำแหน่งที่สมัคร')
                    ->options([
                        'developer' => 'นักพัฒนาโปรแกรม',
                        'designer' => 'นักออกแบบ',
                        'marketing' => 'นักการตลาด',
                        'sales' => 'พนักงานขาย',
                        'accountant' => 'นักบัญชี',
                        'hr' => 'ทรัพยากรบุคคล',
                        'manager' => 'ผู้จัดการ',
                        'other' => 'อื่นๆ',
                    ])
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->preload(),

                Select::make('expected_salary')
                    ->label('เงินเดือนที่คาดหวัง')
                    ->options([
                        '15000-20000' => '15,000 - 20,000 บาท',
                        '20000-25000' => '20,000 - 25,000 บาท',
                        '25000-30000' => '25,000 - 30,000 บาท',
                        '30000-40000' => '30,000 - 40,000 บาท',
                        '40000-50000' => '40,000 - 50,000 บาท',
                        '50000+' => '50,000 บาทขึ้นไป',
                        'negotiable' => 'ตามตกลง',
                    ])
                    ->required()
                    ->native(false)
                    ->prefix('฿'),

                DatePicker::make('available_start_date')
                    ->label('วันที่สามารถเริ่มงานได้')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->minDate(now())
                    ->helperText('ตัวอย่าง: DatePicker พร้อม ->minDate() เพื่อกำหนดวันที่เริ่มต้นที่เลือกได้'),

                // ============================================
                // ส่วนที่ 5: ประสบการณ์และความสามารถ
                // ============================================
                Radio::make('work_experience')
                    ->label('มีประสบการณ์การทำงานหรือไม่')
                    ->options([
                        'yes' => 'มีประสบการณ์',
                        'no' => 'ไม่มีประสบการณ์',
                    ])
                    ->required()
                    ->default('no')
                    ->inline()
                    ->live()
                    ->helperText('ตัวอย่าง: Radio - รับค่า string (เลือกตัวเลือกเดียว) พร้อม ->live()'),

                TextInput::make('years_of_experience')
                    ->label('จำนวนปีที่ทำงาน (ปี)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(50)
                    ->visible(fn ($get) => $get('work_experience') === 'yes')
                    ->required(fn ($get) => $get('work_experience') === 'yes')
                    ->helperText('ตัวอย่าง: TextInput พร้อม ->visible() เพื่อแสดงเมื่อเลือก "มีประสบการณ์"'),

                // ============================================
                // ส่วนที่ 6: ความสามารถพิเศษ
                // ============================================
                Toggle::make('has_vehicle')
                    ->label('มีรถยนต์ส่วนตัว')
                    ->default(false)
                    ->helperText('ตัวอย่าง: Toggle - รับค่า boolean (true/false)'),

                Toggle::make('can_travel')
                    ->label('สามารถเดินทางไปต่างจังหวัดได้')
                    ->default(true)
                    ->helperText('ตัวอย่าง: Toggle พร้อม default value'),

                Toggle::make('can_relocate')
                    ->label('สามารถย้ายที่อยู่ได้')
                    ->default(false)
                    ->helperText('ตัวอย่าง: Toggle - สวิตช์เปิด/ปิด'),

                // ============================================
                // ส่วนที่ 7: ข้อกำหนดและเงื่อนไข
                // ============================================
                Checkbox::make('agree_terms')
                    ->label('ยอมรับข้อกำหนดและเงื่อนไขการสมัครงาน')
                    ->required()
                    ->default(false)
                    ->helperText('ตัวอย่าง: Checkbox - รับค่า boolean (true/false)'),

                Checkbox::make('agree_data_collection')
                    ->label('ยินยอมให้เก็บรวบรวมข้อมูลส่วนบุคคล')
                    ->required()
                    ->default(false)
                    ->helperText('ตัวอย่าง: Checkbox - ช่องทำเครื่องหมาย'),
            ]);
    }

    /**
     * getAdditionalInfoSchema() - Form 2: ข้อมูลเพิ่มเติม
     * 
     * ประกอบด้วย:
     * - Textarea (ประวัติการทำงาน, ทักษะ, ภาษา, ข้อมูลอ้างอิง, ข้อมูลเพิ่มเติม)
     * - TextInput (เงินเดือนเดิม)
     * - FileUpload (Resume, Portfolio, ใบรับรอง)
     * - Select Multiple (วันทำงานที่ต้องการ, เวลาทำงานที่ต้องการ)
     * - Conditional fields (ข้อมูลผู้อ้างอิง)
     */
    public function getAdditionalInfoSchema(): Schema
    {
        return Schema::make()
            ->livewire($this)
            ->components([
                // ============================================
                // ส่วนที่ 1: ประวัติการทำงาน
                // ============================================
                Textarea::make('work_history')
                    ->label('ประวัติการทำงาน')
                    ->rows(6)
                    ->maxLength(1000)
                    ->placeholder('กรุณาระบุประวัติการทำงานที่ผ่านมา (ชื่อบริษัท, ตำแหน่ง, ระยะเวลา, หน้าที่ความรับผิดชอบ)')
                    ->visible(fn ($get) => $get('work_experience') === 'yes')
                    ->helperText('ตัวอย่าง: Textarea พร้อม ->visible() เพื่อแสดงเมื่อมีประสบการณ์'),

                TextInput::make('previous_salary')
                    ->label('เงินเดือนเดิม (บาท)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(1000000)
                    ->prefix('฿')
                    ->visible(fn ($get) => $get('work_experience') === 'yes')
                    ->helperText('ตัวอย่าง: TextInput พร้อม ->prefix() เพื่อแสดงสัญลักษณ์'),

                // ============================================
                // ส่วนที่ 2: ทักษะและความสามารถ
                // ============================================
                Textarea::make('skills')
                    ->label('ทักษะและความสามารถพิเศษ')
                    ->rows(4)
                    ->maxLength(500)
                    ->placeholder('เช่น PHP, JavaScript, Laravel, Photoshop, Excel, etc.')
                    ->helperText('ตัวอย่าง: Textarea - รับค่า string (ข้อความหลายบรรทัด)'),

                Textarea::make('languages')
                    ->label('ภาษาที่สามารถใช้ได้')
                    ->rows(3)
                    ->maxLength(300)
                    ->placeholder('เช่น ไทย (ภาษาแม่), อังกฤษ (ดีมาก), จีน (พอใช้)')
                    ->helperText('ตัวอย่าง: Textarea - ระบุภาษาที่สามารถใช้ได้'),

                // ============================================
                // ส่วนที่ 3: วันและเวลาทำงานที่ต้องการ
                // ============================================
                Select::make('preferred_work_days')
                    ->label('วันทำงานที่ต้องการ')
                    ->options([
                        'monday' => 'จันทร์',
                        'tuesday' => 'อังคาร',
                        'wednesday' => 'พุธ',
                        'thursday' => 'พฤหัสบดี',
                        'friday' => 'ศุกร์',
                        'saturday' => 'เสาร์',
                        'sunday' => 'อาทิตย์',
                    ])
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->helperText('ตัวอย่าง: Select พร้อม ->multiple() เพื่อเลือกได้หลายค่า'),

                Select::make('preferred_work_time')
                    ->label('เวลาทำงานที่ต้องการ')
                    ->options([
                        'full_time' => 'เต็มเวลา (Full-time)',
                        'part_time' => ' part-time',
                        'flexible' => 'ยืดหยุ่น',
                        'shift' => 'กะ (Shift)',
                    ])
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->helperText('ตัวอย่าง: Select พร้อม ->multiple() - เลือกได้หลายค่า'),

                // ============================================
                // ส่วนที่ 4: ไฟล์แนบ
                // ============================================
                FileUpload::make('resume')
                    ->label('Resume / CV')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(5120) // 5MB
                    ->directory('job-applications/resumes')
                    ->visibility('private')
                    ->downloadable()
                    ->previewable()
                    ->openable()
                    ->required()
                    ->helperText('ตัวอย่าง: FileUpload - รับค่า array (file paths) พร้อม validation'),

                FileUpload::make('portfolio')
                    ->label('Portfolio (ถ้ามี)')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240) // 10MB
                    ->directory('job-applications/portfolios')
                    ->visibility('private')
                    ->downloadable()
                    ->previewable()
                    ->openable()
                    ->multiple()
                    ->helperText('ตัวอย่าง: FileUpload พร้อม ->multiple() เพื่ออัปโหลดได้หลายไฟล์'),

                FileUpload::make('certificates')
                    ->label('ใบรับรอง / ประกาศนียบัตร (ถ้ามี)')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(5120) // 5MB
                    ->directory('job-applications/certificates')
                    ->visibility('private')
                    ->downloadable()
                    ->previewable()
                    ->openable()
                    ->multiple()
                    ->helperText('ตัวอย่าง: FileUpload - อัปโหลดได้หลายไฟล์'),

                // ============================================
                // ส่วนที่ 5: ข้อมูลผู้อ้างอิง
                // ============================================
                Toggle::make('has_references')
                    ->label('มีข้อมูลผู้อ้างอิง')
                    ->default(false)
                    ->live()
                    ->helperText('ตัวอย่าง: Toggle พร้อม ->live() เพื่อให้อัปเดตแบบ real-time'),

                TextInput::make('reference_name')
                    ->label('ชื่อผู้อ้างอิง')
                    ->maxLength(100)
                    ->visible(fn ($get) => $get('has_references') === true)
                    ->required(fn ($get) => $get('has_references') === true)
                    ->helperText('ตัวอย่าง: TextInput พร้อม ->visible() เพื่อแสดงเมื่อเปิด Toggle'),

                TextInput::make('reference_position')
                    ->label('ตำแหน่งผู้อ้างอิง')
                    ->maxLength(100)
                    ->visible(fn ($get) => $get('has_references') === true)
                    ->required(fn ($get) => $get('has_references') === true),

                TextInput::make('reference_company')
                    ->label('บริษัท/องค์กร')
                    ->maxLength(200)
                    ->visible(fn ($get) => $get('has_references') === true)
                    ->required(fn ($get) => $get('has_references') === true),

                TextInput::make('reference_phone')
                    ->label('เบอร์โทรศัพท์ผู้อ้างอิง')
                    ->tel()
                    ->mask('999-999-9999')
                    ->visible(fn ($get) => $get('has_references') === true)
                    ->required(fn ($get) => $get('has_references') === true)
                    ->helperText('ตัวอย่าง: TextInput พร้อม ->mask() และ ->visible()'),

                // ============================================
                // ส่วนที่ 6: ข้อมูลเพิ่มเติม
                // ============================================
                Textarea::make('additional_info')
                    ->label('ข้อมูลเพิ่มเติม (ถ้ามี)')
                    ->rows(4)
                    ->maxLength(500)
                    ->placeholder('ข้อมูลอื่นๆ ที่ต้องการแจ้งให้ทราบ')
                    ->helperText('ตัวอย่าง: Textarea - รับค่า string (ข้อความหลายบรรทัด)'),

                Textarea::make('references')
                    ->label('ข้อมูลผู้อ้างอิงเพิ่มเติม (ถ้ามี)')
                    ->rows(3)
                    ->maxLength(300)
                    ->placeholder('ข้อมูลผู้อ้างอิงเพิ่มเติม')
                    ->visible(fn ($get) => $get('has_references') === true)
                    ->helperText('ตัวอย่าง: Textarea พร้อม ->visible()'),
            ]);
    }

    // ============================================
    // Methods สำหรับ Form Submission
    // ============================================

    /**
     * submitPersonalInfo() - Submit Form 1: ข้อมูลส่วนตัว
     */
    public function submitPersonalInfo(): void
    {
        // Validate data (Filament handles validation automatically)
        // อ่านค่าจาก properties
        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'nationality' => $this->nationality,
            'religion' => $this->religion,
            'address' => $this->address,
            'education_level' => $this->education_level,
            'position_applied' => $this->position_applied,
            'expected_salary' => $this->expected_salary,
            'available_start_date' => $this->available_start_date,
            'work_experience' => $this->work_experience,
            'years_of_experience' => $this->years_of_experience,
            'has_vehicle' => $this->has_vehicle,
            'can_travel' => $this->can_travel,
            'can_relocate' => $this->can_relocate,
            'agree_terms' => $this->agree_terms,
            'agree_data_collection' => $this->agree_data_collection,
        ];

        \Filament\Notifications\Notification::make()
            ->title('บันทึกข้อมูลส่วนตัวสำเร็จ!')
            ->body('ข้อมูลส่วนตัวถูกบันทึกแล้ว กรุณากรอกข้อมูลเพิ่มเติมใน Form 2')
            ->success()
            ->send();
    }

    /**
     * submitAdditionalInfo() - Submit Form 2: ข้อมูลเพิ่มเติม
     */
    public function submitAdditionalInfo(): void
    {
        // อ่านค่าจาก properties
        $data = [
            'work_history' => $this->work_history,
            'previous_salary' => $this->previous_salary,
            'skills' => $this->skills,
            'languages' => $this->languages,
            'preferred_work_days' => $this->preferred_work_days,
            'preferred_work_time' => $this->preferred_work_time,
            'resume' => $this->resume,
            'portfolio' => $this->portfolio,
            'certificates' => $this->certificates,
            'has_references' => $this->has_references,
            'reference_name' => $this->reference_name,
            'reference_position' => $this->reference_position,
            'reference_company' => $this->reference_company,
            'reference_phone' => $this->reference_phone,
            'additional_info' => $this->additional_info,
        ];

        \Filament\Notifications\Notification::make()
            ->title('บันทึกข้อมูลเพิ่มเติมสำเร็จ!')
            ->body('ข้อมูลเพิ่มเติมถูกบันทึกแล้ว')
            ->success()
            ->send();
    }

    /**
     * submitAll() - Submit ทั้ง 2 Form พร้อมกัน
     */
    public function submitAll(): void
    {
        // รวมข้อมูลทั้งหมด
        $allData = [
            'personal_info' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'birth_date' => $this->birth_date,
                'gender' => $this->gender,
                'marital_status' => $this->marital_status,
                'nationality' => $this->nationality,
                'religion' => $this->religion,
                'address' => $this->address,
                'current_address' => $this->current_address,
                'education_level' => $this->education_level,
                'position_applied' => $this->position_applied,
                'expected_salary' => $this->expected_salary,
                'available_start_date' => $this->available_start_date,
                'work_experience' => $this->work_experience,
                'years_of_experience' => $this->years_of_experience,
                'has_vehicle' => $this->has_vehicle,
                'can_travel' => $this->can_travel,
                'can_relocate' => $this->can_relocate,
                'agree_terms' => $this->agree_terms,
                'agree_data_collection' => $this->agree_data_collection,
            ],
            'additional_info' => [
                'work_history' => $this->work_history,
                'previous_salary' => $this->previous_salary,
                'skills' => $this->skills,
                'languages' => $this->languages,
                'preferred_work_days' => $this->preferred_work_days,
                'preferred_work_time' => $this->preferred_work_time,
                'resume' => $this->resume,
                'portfolio' => $this->portfolio,
                'certificates' => $this->certificates,
                'has_references' => $this->has_references,
                'reference_name' => $this->reference_name,
                'reference_position' => $this->reference_position,
                'reference_company' => $this->reference_company,
                'reference_phone' => $this->reference_phone,
                'additional_info' => $this->additional_info,
            ],
        ];

        // ในที่นี้เราจะแสดง notification เท่านั้น
        // ในโปรเจคจริงควรบันทึกลง database
        \Filament\Notifications\Notification::make()
            ->title('ส่งใบสมัครงานสำเร็จ!')
            ->body('ขอบคุณที่สมัครงานกับเรา เราจะติดต่อกลับไปในเร็วๆ นี้')
            ->success()
            ->send();
    }

    /**
     * resetForm() - รีเซ็ตทั้ง 2 Form
     */
    public function resetForm(): void
    {
        // Reset Form 1
        $this->first_name = null;
        $this->last_name = null;
        $this->email = null;
        $this->phone = null;
        $this->id_card = null;
        $this->birth_date = null;
        $this->gender = null;
        $this->marital_status = 'single';
        $this->nationality = 'thai';
        $this->religion = 'buddhism';
        $this->address = null;
        $this->current_address = null;
        $this->education_level = null;
        $this->position_applied = null;
        $this->expected_salary = null;
        $this->available_start_date = null;
        $this->work_experience = 'no';
        $this->years_of_experience = null;
        $this->has_vehicle = false;
        $this->can_travel = true;
        $this->can_relocate = false;
        $this->agree_terms = false;
        $this->agree_data_collection = false;

        // Reset Form 2
        $this->work_history = null;
        $this->previous_salary = null;
        $this->skills = null;
        $this->languages = null;
        $this->preferred_work_days = null;
        $this->preferred_work_time = null;
        $this->resume = null;
        $this->portfolio = null;
        $this->certificates = null;
        $this->has_references = false;
        $this->reference_name = null;
        $this->reference_position = null;
        $this->reference_company = null;
        $this->reference_phone = null;
        $this->additional_info = null;
        $this->references = null;
    }
}
