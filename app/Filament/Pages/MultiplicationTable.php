<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class MultiplicationTable extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    protected string $view = 'filament.pages.multiplication-table';

    protected static ?string $navigationLabel = 'แม่สูตรคูณ';

    protected static ?string $title = 'โปรแกรมแม่สูตรคูณ';

    protected static ?int $navigationSort = 4;

    // Property สำหรับเก็บแม่สูตรคูณที่ผู้ใช้ป้อน
    public ?int $multiplier = null;

    /**
     * mount() - เรียกเมื่อโหลดหน้าแรก
     * กำหนดค่าเริ่มต้น
     */
    public function mount(): void
    {
        $this->multiplier = 2; // ค่าเริ่มต้นเป็น 2
    }

    /**
     * form() - กำหนดโครงสร้าง Form
     * 
     * หลักการ:
     * - TextInput::make('multiplier') จะ bind กับ $this->multiplier
     * - เมื่อผู้ใช้ป้อนค่า ค่าจะถูกส่งไปยัง $this->multiplier อัตโนมัติ
     * - ใช้ ->live() เพื่อให้แสดงผลแบบ real-time
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('multiplier')
                    ->label('แม่สูตรคูณ')
                    ->numeric()
                    ->required()
                    ->default(2)
                    ->minValue(1)
                    ->maxValue(100)
                    ->helperText('กรุณาป้อนแม่สูตรคูณที่ต้องการ (1-100)')
                    ->live() // อัปเดตแบบ real-time เมื่อค่าเปลี่ยน
                    ->afterStateUpdated(function ($state) {
                        // เมื่อค่าเปลี่ยน จะเรียก function นี้
                        // $state = ค่าปัจจุบันที่ผู้ใช้ป้อน
                    }),
            ]);
    }

    /**
     * getMultiplicationResults() - คำนวณและส่งคืนผลลัพธ์แม่สูตรคูณ
     * 
     * หลักการ:
     * - อ่านค่าจาก $this->multiplier
     * - คำนวณผลลัพธ์ตั้งแต่ 1 ถึง 12
     * - ส่งคืนเป็น array
     */
    public function getMultiplicationResults(): array
    {
        if ($this->multiplier === null || $this->multiplier < 1) {
            return [];
        }

        $results = [];
        for ($i = 1; $i <= 12; $i++) {
            $results[] = [
                'number' => $i,
                'result' => $this->multiplier * $i,
                'formula' => "{$this->multiplier} × {$i} = " . ($this->multiplier * $i),
            ];
        }

        return $results;
    }
}
