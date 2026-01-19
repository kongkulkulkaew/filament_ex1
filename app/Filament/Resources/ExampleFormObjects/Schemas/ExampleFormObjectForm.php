<?php

namespace App\Filament\Resources\ExampleFormObjects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

/**
 * ExampleFormObjectForm - ตัวอย่าง Form Object ที่ครบถ้วน
 *
 * ฟีเจอร์ที่รวมอยู่:
 * 1. Wizard 3 steps
 * 2. Tabs + Sections + Grid
 * 3. Realtime placeholder (full name) จาก first_name/last_name (reactive)
 * 4. status ถ้า suspended บังคับ note
 * 5. salary > 100000 ให้แจ้งเตือน (afterStateUpdated)
 * 6. start_date ถ้าน้อยกว่าวันนี้ให้ reset + warning
 * 7. FileUpload avatar + Repeater documents
 * 8. Select department แล้ว options ของ skills เปลี่ยนตาม department
 *
 * หมายเหตุ: ใน Filament 4 ใช้ Schema แทน Form
 * สำหรับ Resource ให้ใช้ method configure() แทน form()
 */
class ExampleFormObjectForm
{
    /**
     * form() - ใช้รูปแบบ Form $form สำหรับ Resource
     *
     * ใน Filament 4 Resource ใช้ Schema แต่สามารถใช้รูปแบบนี้ได้
     * โดยแปลง Schema เป็น array ของ components
     *
     * หมายเหตุ: วิธีนี้ใช้ได้กับ Resource ที่ต้องการใช้ Form object โดยตรง
     * แต่แนะนำให้ใช้ configure() แทน
     */
    public static function form(Schema $schema): Schema
    {
        return static::configure($schema);
    }

    /**
     * configure() - ใช้รูปแบบ Schema สำหรับ Resource
     *
     * วิธีนี้ใช้ได้กับ Resource ที่ใช้ Schema โดยตรง (แนะนำ)
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ============================================
            // Wizard 3 Steps
            // ============================================
            Wizard::make([
                Step::make('ข้อมูลส่วนตัว')
                    ->description('กรอกข้อมูลส่วนตัวพื้นฐาน')
                    ->schema([
                        // ============================================
                        // Tab 1: ข้อมูลพื้นฐาน
                        // ============================================
                        Tabs::make('personal_info_tabs')
                            ->tabs([
                                Tab::make('ข้อมูลส่วนตัว')
                                    ->schema([
                                        Section::make('ข้อมูลส่วนตัว')
                                            ->description('กรอกชื่อ-นามสกุล')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('first_name')
                                                            ->label('ชื่อ')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->live(onBlur: false) // Real-time update
                                                            ->placeholder('เช่น สมชาย')
                                                            ->helperText('ชื่อจริง'),

                                                        TextInput::make('last_name')
                                                            ->label('นามสกุล')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->live(onBlur: false) // Real-time update
                                                            ->placeholder('เช่น ใจดี')
                                                            ->helperText('นามสกุล'),
                                                    ]),

                                                // ============================================
                                                // Realtime placeholder (full name) จาก first_name/last_name (reactive)
                                                // ============================================
                                                TextInput::make('full_name')
                                                    ->label('ชื่อ-นามสกุลเต็ม')
                                                    ->disabled()
                                                    ->dehydrated() // เก็บค่าไว้ใน form state
                                                    ->placeholder(function (Get $get): string {
                                                        $firstName = $get('first_name') ?? '';
                                                        $lastName = $get('last_name') ?? '';

                                                        if (empty($firstName) && empty($lastName)) {
                                                            return 'กรุณากรอกชื่อและนามสกุล';
                                                        }

                                                        return trim("{$firstName} {$lastName}");
                                                    })
                                                    ->live() // ต้องใช้ live() เพื่อให้ placeholder อัปเดตแบบ real-time
                                                    ->helperText('ชื่อ-นามสกุลจะอัปเดตอัตโนมัติเมื่อกรอกชื่อและนามสกุล')
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        // อัปเดต full_name เมื่อ first_name หรือ last_name เปลี่ยน
                                                        $firstName = $get('first_name') ?? '';
                                                        $lastName = $get('last_name') ?? '';
                                                        $fullName = trim("{$firstName} {$lastName}");
                                                        $set('full_name', $fullName);
                                                    }),

                                                // ============================================
                                                // FileUpload avatar
                                                // ============================================
                                                FileUpload::make('avatar')
                                                    ->label('รูปโปรไฟล์')
                                                    ->image()
                                                    ->imageEditor()
                                                    ->imageEditorAspectRatios([
                                                        '1:1',
                                                    ])
                                                    ->maxSize(5120) // 5MB
                                                    ->directory('avatars')
                                                    ->visibility('public')
                                                    ->helperText('อัปโหลดรูปโปรไฟล์ (ขนาดสูงสุด 5MB)')
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(2),
                                    ]),

                                Tab::make('ข้อมูลการติดต่อ')
                                    ->schema([
                                        Section::make('ข้อมูลการติดต่อ')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('email')
                                                            ->label('อีเมล')
                                                            ->email()
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->placeholder('example@email.com'),

                                                        TextInput::make('phone')
                                                            ->label('เบอร์โทรศัพท์')
                                                            ->tel()
                                                            ->required()
                                                            ->mask('999-999-9999')
                                                            ->placeholder('08X-XXX-XXXX'),
                                                    ]),

                                                Textarea::make('address')
                                                    ->label('ที่อยู่')
                                                    ->rows(3)
                                                    ->maxLength(500)
                                                    ->placeholder('กรุณากรอกที่อยู่ที่สมบูรณ์')
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(2),
                                    ]),
                            ]),
                    ]),

                Step::make('ข้อมูลการทำงาน')
                    ->description('กรอกข้อมูลการทำงานและเงินเดือน')
                    ->schema([
                        Section::make('ข้อมูลการทำงาน')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        // ============================================
                                        // Select department แล้ว options ของ skills เปลี่ยนตาม department
                                        // ============================================
                                        Select::make('department')
                                            ->label('แผนก')
                                            ->options([
                                                'it' => 'IT',
                                                'hr' => 'HR',
                                                'finance' => 'Finance',
                                                'marketing' => 'Marketing',
                                            ])
                                            ->required()
                                            ->live() // ต้องใช้ live() เพื่อให้ form re-render เมื่อค่าเปลี่ยน
                                            ->placeholder('เลือกแผนก')
                                            ->helperText('เลือกแผนก - ทักษะที่เกี่ยวข้องจะเปลี่ยนตามแผนก')
                                            ->afterStateUpdated(function ($state, Set $set) {
                                                // Reset skills เมื่อเปลี่ยน department
                                                $set('skills', null);
                                            }),

                                        // ============================================
                                        // Skills ที่เปลี่ยนตาม department (dependsOn)
                                        // ============================================
                                        Select::make('skills')
                                            ->label('ทักษะ')
                                            ->multiple()
                                            ->options(function (Get $get): array {
                                                $department = $get('department');

                                                // กำหนดทักษะตามแผนก
                                                return match ($department) {
                                                    'it' => [
                                                        'php' => 'PHP',
                                                        'javascript' => 'JavaScript',
                                                        'python' => 'Python',
                                                        'laravel' => 'Laravel',
                                                        'react' => 'React',
                                                    ],
                                                    'hr' => [
                                                        'recruitment' => 'Recruitment',
                                                        'training' => 'Training',
                                                        'payroll' => 'Payroll',
                                                        'employee_relations' => 'Employee Relations',
                                                    ],
                                                    'finance' => [
                                                        'accounting' => 'Accounting',
                                                        'tax' => 'Tax',
                                                        'auditing' => 'Auditing',
                                                        'financial_analysis' => 'Financial Analysis',
                                                    ],
                                                    'marketing' => [
                                                        'seo' => 'SEO',
                                                        'social_media' => 'Social Media',
                                                        'content_marketing' => 'Content Marketing',
                                                        'analytics' => 'Analytics',
                                                    ],
                                                    default => [],
                                                };
                                            })
                                            ->required()
                                            ->visible(fn (Get $get): bool => filled($get('department')))
                                            ->helperText('เลือกทักษะที่เกี่ยวข้องกับแผนก')
                                            ->searchable(),

                                        // ============================================
                                        // salary > 100000 ให้แจ้งเตือน (afterStateUpdated)
                                        // ============================================
                                        TextInput::make('salary')
                                            ->label('เงินเดือน')
                                            ->numeric()
                                            ->required()
                                            ->prefix('฿')
                                            ->minValue(0)
                                            ->maxValue(1000000)
                                            ->step(1000)
                                            ->placeholder('เช่น 50000')
                                            ->helperText(function (Get $get): string {
                                                $salary = $get('salary');
                                                if ($salary && $salary > 100000) {
                                                    return '⚠️ เงินเดือนที่กรอกสูงกว่า 100,000 บาท กรุณาตรวจสอบความถูกต้อง';
                                                }

                                                return 'กรอกเงินเดือน (บาท)';
                                            })
                                            ->live(),

                                        // ============================================
                                        // start_date ถ้าน้อยกว่าวันนี้ให้ reset + warning
                                        // ============================================
                                        DatePicker::make('start_date')
                                            ->label('วันที่เริ่มงาน')
                                            ->required()
                                            ->native(false)
                                            ->displayFormat('d/m/Y')
                                            ->helperText('เลือกวันที่เริ่มงาน (ต้องไม่น้อยกว่าวันนี้)')
                                            ->afterStateUpdated(function ($state, Set $set) {
                                                // ตรวจสอบว่าวันที่น้อยกว่าวันนี้หรือไม่
                                                if ($state) {
                                                    $selectedDate = \Carbon\Carbon::parse($state);
                                                    $today = \Carbon\Carbon::today();

                                                    if ($selectedDate->lt($today)) {
                                                        // Reset ค่า
                                                        $set('start_date', null);
                                                    }
                                                }
                                            })
                                            ->minDate(now())
                                            ->default(now())
                                            ->live(),
                                    ]),
                            ])
                            ->columns(2),
                    ]),

                Step::make('เอกสารและสถานะ')
                    ->description('อัปโหลดเอกสารและกำหนดสถานะ')
                    ->schema([
                        Section::make('เอกสาร')
                            ->schema([
                                // ============================================
                                // Repeater documents
                                // ============================================
                                Repeater::make('documents')
                                    ->label('เอกสาร')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('ชื่อเอกสาร')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('เช่น สำเนาบัตรประชาชน'),

                                        FileUpload::make('file')
                                            ->label('ไฟล์')
                                            ->required()
                                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                                            ->maxSize(10240) // 10MB
                                            ->directory('documents')
                                            ->visibility('public')
                                            ->downloadable()
                                            ->previewable(),

                                        Textarea::make('description')
                                            ->label('รายละเอียด')
                                            ->rows(2)
                                            ->maxLength(500)
                                            ->placeholder('รายละเอียดเพิ่มเติม'),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(1)
                                    ->minItems(1)
                                    ->maxItems(10)
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'เอกสารใหม่')
                                    ->collapsible()
                                    ->reorderable()
                                    ->helperText('เพิ่มเอกสารที่เกี่ยวข้อง (อย่างน้อย 1 ไฟล์)')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('สถานะ')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        // ============================================
                                        // status ถ้า suspended บังคับ note
                                        // ============================================
                                        Select::make('status')
                                            ->label('สถานะ')
                                            ->options([
                                                'active' => 'Active',
                                                'inactive' => 'Inactive',
                                                'suspended' => 'Suspended',
                                            ])
                                            ->required()
                                            ->default('active')
                                            ->live() // ต้องใช้ live() เพื่อให้ form re-render เมื่อค่าเปลี่ยน
                                            ->helperText('เลือกสถานะ'),

                                        // ============================================
                                        // Note ที่บังคับเมื่อ status = suspended
                                        // ============================================
                                        Textarea::make('note')
                                            ->label('หมายเหตุ')
                                            ->rows(3)
                                            ->maxLength(1000)
                                            ->required(fn (Get $get): bool => $get('status') === 'suspended')
                                            ->visible(fn (Get $get): bool => $get('status') === 'suspended')
                                            ->placeholder('กรุณากรอกหมายเหตุเมื่อสถานะเป็น Suspended')
                                            ->helperText('หมายเหตุจำเป็นเมื่อสถานะเป็น Suspended')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columns(2),
                    ]),
            ])
                ->columnSpanFull(),
        ]);
    }
}
