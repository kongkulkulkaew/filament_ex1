<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * CustomerForm - Form Schema สำหรับ Customer
 * 
 * หลักการ:
 * - ใช้ Filament Form Components
 * - เพิ่ม custom validation และ logic
 * - แบ่งเป็น Sections เพื่อจัดกลุ่ม
 */
class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ============================================
                // Section 1: ข้อมูลพื้นฐาน
                // ============================================
                Section::make('ข้อมูลพื้นฐาน')
                    ->description('กรุณากรอกข้อมูลพื้นฐานของลูกค้า')
                    ->schema([
                        TextInput::make('name')
                            ->label('ชื่อ-นามสกุล')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('เช่น สมชาย ใจดี')
                            ->live() // Real-time update
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Custom logic: แปลงชื่อเป็นตัวพิมพ์ใหญ่ตัวแรก
                                if ($state) {
                                    $set('name', mb_convert_case($state, MB_CASE_TITLE, 'UTF-8'));
                                }
                            })
                            ->helperText('ตัวอย่าง: TextInput พร้อม custom logic (แปลงชื่อเป็นตัวพิมพ์ใหญ่ตัวแรก)'),

                        TextInput::make('email')
                            ->label('อีเมล')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('example@email.com')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Custom logic: แปลงอีเมลเป็นตัวพิมพ์เล็ก
                                if ($state) {
                                    $set('email', strtolower($state));
                                }
                            })
                            ->helperText('ตัวอย่าง: TextInput พร้อม custom validation และ logic'),

                        TextInput::make('phone')
                            ->label('เบอร์โทรศัพท์')
                            ->tel()
                            ->required()
                            ->mask('999-999-9999')
                            ->placeholder('08X-XXX-XXXX')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Custom logic: ลบเครื่องหมายขีดออกเพื่อเก็บใน database
                                // แต่แสดงผลแบบมีขีด
                                if ($state) {
                                    $cleanPhone = str_replace('-', '', $state);
                                    // ตรวจสอบว่าเป็นเบอร์โทรไทยหรือไม่
                                    if (preg_match('/^0[689]\d{8}$/', $cleanPhone)) {
                                        // เบอร์โทรถูกต้อง
                                    }
                                }
                            })
                            ->helperText('ตัวอย่าง: TextInput พร้อม mask และ custom validation'),

                        Textarea::make('address')
                            ->label('ที่อยู่')
                            ->required()
                            ->rows(4)
                            ->maxLength(500)
                            ->placeholder('กรุณากรอกที่อยู่ที่สมบูรณ์')
                            ->helperText('ตัวอย่าง: Textarea - รับค่า string (ข้อความหลายบรรทัด)'),
                    ])
                    ->columns(2),

                // ============================================
                // Section 2: ข้อมูลเพิ่มเติม (Optional)
                // ============================================
                Section::make('ข้อมูลเพิ่มเติม')
                    ->description('ข้อมูลเพิ่มเติม (ไม่บังคับ)')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('tax_id')
                            ->label('เลขประจำตัวผู้เสียภาษี')
                            ->mask('9-9999-99999-99-9')
                            ->placeholder('X-XXXX-XXXXX-XX-X')
                            ->helperText('ตัวอย่าง: TextInput พร้อม mask (รูปแบบบัตรประชาชน)'),

                        Textarea::make('notes')
                            ->label('หมายเหตุ')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('หมายเหตุเพิ่มเติมเกี่ยวกับลูกค้า')
                            ->helperText('ตัวอย่าง: Textarea - รับค่า string (ข้อความหลายบรรทัด)'),
                    ])
                    ->columns(2),
            ]);
    }
}
