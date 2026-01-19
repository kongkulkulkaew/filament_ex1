<?php

namespace App\Filament\Resources\TimeLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TimeLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลการลงเวลาเรียน')
                    ->description('กรุณากรอกข้อมูลการลงเวลาเรียน')
                    ->schema([
                        Select::make('classroom_id')
                            ->label('ห้องเรียน')
                            ->relationship('classroom', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(fn ($livewire) => empty($livewire->record) ? request()->query('classroom_id') : null)
                            ->helperText('เลือกห้องเรียนที่ต้องการลงเวลา'),

                        DatePicker::make('log_date')
                            ->label('วันที่')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->default(now())
                            ->required()
                            ->helperText('วันที่ลงเวลาเรียน'),

                        TimePicker::make('start_time')
                            ->label('เวลาเริ่ม')
                            ->seconds(false)
                            ->required()
                            ->default(now())
                            ->helperText('เวลาเริ่มต้นการเรียน'),

                        TimePicker::make('end_time')
                            ->label('เวลาสิ้นสุด')
                            ->seconds(false)
                            ->helperText('เวลาสิ้นสุดการเรียน (ถ้ามี)'),

                        Textarea::make('notes')
                            ->label('หมายเหตุ')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('หมายเหตุเพิ่มเติมเกี่ยวกับการลงเวลา')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
