<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ASCIITable extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;

    protected string $view = 'filament.pages.ascii-table';

    protected static ?string $navigationLabel = 'ตาราง ASCII';

    protected static ?string $title = 'โปรแกรมตารางรหัส ASCII';

    protected static ?int $navigationSort = 8;

    // Properties สำหรับเก็บช่วงรหัส ASCII
    public ?int $startCode = null; // รหัส ASCII เริ่มต้น
    public ?int $endCode = null; // รหัส ASCII สิ้นสุด
    public ?int $columnsPerRow = null; // จำนวนคอลัมน์ต่อแถว

    /**
     * mount() - เรียกเมื่อโหลดหน้าแรก
     * กำหนดค่าเริ่มต้น
     */
    public function mount(): void
    {
        $this->startCode = 32; // เริ่มจาก Space (32)
        $this->endCode = 126; // สิ้นสุดที่ ~ (126)
        $this->columnsPerRow = 8; // 8 คอลัมน์ต่อแถว
    }

    /**
     * form() - กำหนดโครงสร้าง Form
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('startCode')
                    ->label('รหัส ASCII เริ่มต้น')
                    ->numeric()
                    ->required()
                    ->default(32)
                    ->minValue(0)
                    ->maxValue(255)
                    ->helperText('รหัส ASCII เริ่มต้น (0-255)')
                    ->live(),

                TextInput::make('endCode')
                    ->label('รหัส ASCII สิ้นสุด')
                    ->numeric()
                    ->required()
                    ->default(126)
                    ->minValue(0)
                    ->maxValue(255)
                    ->helperText('รหัส ASCII สิ้นสุด (0-255)')
                    ->live(),

                TextInput::make('columnsPerRow')
                    ->label('จำนวนคอลัมน์ต่อแถว')
                    ->numeric()
                    ->required()
                    ->default(8)
                    ->minValue(1)
                    ->maxValue(16)
                    ->helperText('จำนวนคอลัมน์ที่ต้องการแสดงต่อแถว (1-16)')
                    ->live(),
            ]);
    }



    /**
     * generateASCIITable() - สร้างตาราง ASCII โดยใช้ Nested Loop
     * 
     * หลักการ:
     * - ใช้ for loop เพื่อวนลูปจาก startCode ถึง endCode
     * - แบ่งเป็นแถวตาม columnsPerRow
     * - แต่ละเซลล์แสดง: รหัส ASCII, ตัวอักษร, รหัสฐาน 16
     * 
     * @return array
     */
    public function generateASCIITable(): array
    {
        if ($this->startCode === null || $this->endCode === null || 
            $this->columnsPerRow === null || 
            $this->startCode < 0 || $this->endCode > 255 ||
            $this->startCode > $this->endCode ||
            $this->columnsPerRow < 1) {
            return [];
        }

        $table = [];
        $currentRow = [];

        // ============================================
        // NESTED LOOP - Loop ซ้อน Loop
        // ============================================
        
        // Outer Loop: วนลูปสำหรับรหัส ASCII ทั้งหมด
        for ($code = $this->startCode; $code <= $this->endCode; $code++) {
            // ใช้ if-else เพื่อตรวจสอบประเภทตัวอักษร
            $type = '';
            $color = '';
            
            if ($code >= 32 && $code <= 47) {
                $type = 'เครื่องหมาย';
                $color = 'warning';
            } elseif ($code >= 48 && $code <= 57) {
                $type = 'ตัวเลข';
                $color = 'info';
            } elseif ($code >= 65 && $code <= 90) {
                $type = 'ตัวพิมพ์ใหญ่';
                $color = 'success';
            } elseif ($code >= 97 && $code <= 122) {
                $type = 'ตัวพิมพ์เล็ก';
                $color = 'success';
            } elseif ($code >= 58 && $code <= 64) {
                $type = 'เครื่องหมาย';
                $color = 'warning';
            } elseif ($code >= 91 && $code <= 96) {
                $type = 'เครื่องหมาย';
                $color = 'warning';
            } elseif ($code >= 123 && $code <= 126) {
                $type = 'เครื่องหมาย';
                $color = 'warning';
            } else {
                $type = 'Control';
                $color = 'danger';
            }

            // ใช้ if-else เพื่อตรวจสอบว่าสามารถแสดงตัวอักษรได้หรือไม่
            $displayChar = '';
            if ($code >= 32 && $code <= 126) {
                $displayChar = chr($code);
            } else {
                $displayChar = 'N/A'; // Control characters
            }

            $currentRow[] = [
                'code' => $code,
                'char' => $displayChar,
                'hex' => strtoupper(dechex($code)),
                'type' => $type,
                'color' => $color,
            ];

            // ใช้ if เพื่อตรวจสอบว่าครบจำนวนคอลัมน์แล้วหรือยัง
            if (count($currentRow) >= $this->columnsPerRow) {
                $table[] = $currentRow;
                $currentRow = [];
            }
        }

        // ถ้ายังมีข้อมูลเหลือ (ไม่ครบแถวสุดท้าย)
        if (!empty($currentRow)) {
            $table[] = $currentRow;
        }

        return $table;
    }

    /**
     * getASCIICategories() - ส่งคืนหมวดหมู่ ASCII
     * 
     * @return array
     */
    public function getASCIICategories(): array
    {
        return [
            ['name' => 'Control Characters', 'range' => '0-31', 'description' => 'ตัวอักษรควบคุม', 'color' => 'danger'],
            ['name' => 'Printable Characters', 'range' => '32-126', 'description' => 'ตัวอักษรที่พิมพ์ได้', 'color' => 'success'],
            ['name' => 'Extended ASCII', 'range' => '128-255', 'description' => 'ตัวอักษรขยาย', 'color' => 'info'],
        ];
    }

    /**
     * searchASCII() - ค้นหารหัส ASCII จากตัวอักษรหรือรหัส
     * 
     * ใช้ for loop เพื่อค้นหา
     * 
     * @param string|int $search
     * @return array|null
     */
    public function searchASCII($search): ?array
    {
        if ($search === null || $search === '') {
            return null;
        }

        // ใช้ for loop เพื่อค้นหาจากรหัส ASCII ทั้งหมด
        for ($code = 0; $code <= 255; $code++) {
            // ใช้ if เพื่อตรวจสอบว่าตรงกับที่ค้นหาหรือไม่
            if (is_numeric($search)) {
                if ($code == (int) $search) {
                    return [
                        'code' => $code,
                        'char' => $code >= 32 && $code <= 126 ? chr($code) : 'N/A',
                        'hex' => strtoupper(dechex($code)),
                    ];
                }
            } else {
                if (chr($code) === $search) {
                    return [
                        'code' => $code,
                        'char' => chr($code),
                        'hex' => strtoupper(dechex($code)),
                    ];
                }
            }
        }

        return null;
    }
}
