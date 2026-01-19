<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * StudentForm - Form Schema สำหรับนักเรียน
 */
class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลนักเรียน')
                    ->description('กรุณากรอกข้อมูลนักเรียน')
                    ->schema([
                        Select::make('classroom_id')
                            ->label('ห้องเรียน')
                            ->relationship('classroom', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('ชื่อห้องเรียน')
                                    ->required(),
                                TextInput::make('code')
                                    ->label('รหัสห้องเรียน')
                                    ->required()
                                    ->unique(),
                            ])
                            ->helperText('เลือกห้องเรียนที่นักเรียนสังกัด'),

                        TextInput::make('student_code')
                            ->label('รหัสนักเรียน')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('เช่น ST001')
                            ->helperText('รหัสนักเรียนต้องไม่ซ้ำกัน'),

                        TextInput::make('first_name')
                            ->label('ชื่อ')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('เช่น สมชาย'),

                        TextInput::make('last_name')
                            ->label('นามสกุล')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('เช่น ใจดี'),

                        DatePicker::make('birth_date')
                            ->label('วันเกิด')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->maxDate(now()->subYears(5))
                            ->helperText('อายุต้องมากกว่า 5 ปี'),

                        TextInput::make('phone')
                            ->label('เบอร์โทรศัพท์')
                            ->tel()
                            ->mask('999-999-9999')
                            ->placeholder('08X-XXX-XXXX'),

                        Textarea::make('address')
                            ->label('ที่อยู่')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('สถานะใช้งาน')
                            ->default(true)
                            ->helperText('เปิด/ปิดการใช้งาน'),
                    ])
                    ->columns(2),
            ]);
    }
}
