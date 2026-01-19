<?php

namespace App\Filament\Resources\ClassRooms\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * ClassRoomForm - Form Schema สำหรับห้องเรียน
 *
 * หลักการ:
 * - ใช้ Filament Form Components
 * - แบ่งเป็น Sections เพื่อจัดกลุ่ม
 */
class ClassRoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลห้องเรียน')
                    ->description('กรุณากรอกข้อมูลห้องเรียน')
                    ->schema([
                        TextInput::make('name')
                            ->label('ชื่อห้องเรียน')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('เช่น ม.1/1')
                            ->helperText('ชื่อห้องเรียนที่แสดงในระบบ'),

                        TextInput::make('code')
                            ->label('รหัสห้องเรียน')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('เช่น M1-1')
                            ->helperText('รหัสห้องเรียนต้องไม่ซ้ำกัน'),

                        Textarea::make('description')
                            ->label('รายละเอียด')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('รายละเอียดเพิ่มเติมเกี่ยวกับห้องเรียน'),

                        Toggle::make('is_active')
                            ->label('สถานะใช้งาน')
                            ->default(true)
                            ->helperText('เปิด/ปิดการใช้งานห้องเรียน'),
                    ])
                    ->columns(2),
            ]);
    }
}
