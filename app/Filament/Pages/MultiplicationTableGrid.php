<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class MultiplicationTableGrid extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    protected string $view = 'filament.pages.multiplication-table-grid';

    protected static ?string $navigationLabel = 'ตารางสูตรคูณ (Nested Loop)';

    protected static ?string $title = 'โปรแกรมตารางสูตรคูณ - Nested Loop';

    protected static ?int $navigationSort = 7;

    // Property สำหรับเก็บจำนวนแถวและคอลัมน์
    public ?int $rows = null; // จำนวนแถว (แม่สูตรคูณ)
    public ?int $columns = null; // จำนวนคอลัมน์ (ตัวคูณ)

    /**
     * mount() - เรียกเมื่อโหลดหน้าแรก
     * กำหนดค่าเริ่มต้น
     */
    public function mount(): void
    {
        $this->rows = 12; // แถวเริ่มต้น 12 (แม่สูตรคูณ 1-12)
        $this->columns = 12; // คอลัมน์เริ่มต้น 12 (ตัวคูณ 1-12)
    }

    /**
     * form() - กำหนดโครงสร้าง Form
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rows')
                    ->label('จำนวนแถว (แม่สูตรคูณ)')
                    ->numeric()
                    ->required()
                    ->default(12)
                    ->minValue(1)
                    ->maxValue(20)
                    ->helperText('จำนวนแม่สูตรคูณที่ต้องการแสดง (1-20)')
                    ->live(),

                TextInput::make('columns')
                    ->label('จำนวนคอลัมน์ (ตัวคูณ)')
                    ->numeric()
                    ->required()
                    ->default(12)
                    ->minValue(1)
                    ->maxValue(20)
                    ->helperText('จำนวนตัวคูณที่ต้องการแสดง (1-20)')
                    ->live(),
            ]);
    }

    /**
     * generateTable() - สร้างตารางสูตรคูณโดยใช้ Nested Loop
     * 
     * หลักการ:
     * - ใช้ for loop ภายนอก (outer loop) สำหรับแถว (แม่สูตรคูณ)
     * - ใช้ for loop ภายใน (inner loop) สำหรับคอลัมน์ (ตัวคูณ)
     * - แต่ละเซลล์ = แถว × คอลัมน์
     * 
     * @return array
     */
    public function generateTable(): array
    {
        if ($this->rows === null || $this->columns === null || 
            $this->rows < 1 || $this->columns < 1) {
            return [];
        }

        $table = [];

        // ============================================
        // NESTED LOOP - Loop ซ้อน Loop
        // ============================================
        
        // Outer Loop: วนลูปสำหรับแถว (แม่สูตรคูณ)
        for ($row = 1; $row <= $this->rows; $row++) {
            $tableRow = [
                'multiplier' => $row, // แม่สูตรคูณ
                'cells' => [],
            ];

            // Inner Loop: วนลูปสำหรับคอลัมน์ (ตัวคูณ)
            for ($col = 1; $col <= $this->columns; $col++) {
                // คำนวณผลลัพธ์
                $result = $row * $col;

                // ใช้ if-else เพื่อกำหนดสีตามผลลัพธ์
                $color = 'default';
                if ($result % 10 == 0) {
                    $color = 'success'; // ผลลัพธ์ที่หาร 10 ลงตัว
                } elseif ($result % 5 == 0) {
                    $color = 'info'; // ผลลัพธ์ที่หาร 5 ลงตัว
                } elseif ($result % 2 == 0) {
                    $color = 'warning'; // ผลลัพธ์ที่เป็นจำนวนคู่
                } else {
                    $color = 'danger'; // ผลลัพธ์ที่เป็นจำนวนคี่
                }

                $tableRow['cells'][] = [
                    'multiplier' => $col, // ตัวคูณ
                    'result' => $result,
                    'formula' => "{$row} × {$col}",
                    'color' => $color,
                ];
            }

            $table[] = $tableRow;
        }

        return $table;
    }

    /**
     * generatePattern() - สร้าง Pattern โดยใช้ Nested Loop
     * 
     * ตัวอย่าง Pattern:
     * *
     * **
     * ***
     * ****
     * 
     * @return array
     */
    public function generatePattern(): array
    {
        if ($this->rows === null || $this->rows < 1) {
            return [];
        }

        $pattern = [];

        // Outer Loop: วนลูปสำหรับแถว
        for ($row = 1; $row <= $this->rows; $row++) {
            $patternRow = [
                'row_number' => $row,
                'stars' => '',
            ];

            // Inner Loop: วนลูปสำหรับคอลัมน์ (จำนวน * ในแต่ละแถว)
            for ($col = 1; $col <= $row; $col++) {
                $patternRow['stars'] .= '*';
            }

            $pattern[] = $patternRow;
        }

        return $pattern;
    }

    /**
     * generateNumberPattern() - สร้าง Pattern ตัวเลขโดยใช้ Nested Loop
     * 
     * ตัวอย่าง:
     * 1
     * 12
     * 123
     * 1234
     * 
     * @return array
     */
    public function generateNumberPattern(): array
    {
        if ($this->rows === null || $this->rows < 1) {
            return [];
        }

        $pattern = [];

        // Outer Loop: วนลูปสำหรับแถว
        for ($row = 1; $row <= $this->rows; $row++) {
            $patternRow = [
                'row_number' => $row,
                'numbers' => [],
            ];

            // Inner Loop: วนลูปสำหรับตัวเลขในแต่ละแถว
            for ($col = 1; $col <= $row; $col++) {
                $patternRow['numbers'][] = $col;
            }

            $pattern[] = $patternRow;
        }

        return $pattern;
    }

    /**
     * generatePyramidPattern() - สร้าง Pattern พีระมิดโดยใช้ Nested Loop
     * 
     * ตัวอย่าง:
     *     *
     *    ***
     *   *****
     *  *******
     * 
     * @return array
     */
    public function generatePyramidPattern(): array
    {
        if ($this->rows === null || $this->rows < 1) {
            return [];
        }

        $pattern = [];

        // Outer Loop: วนลูปสำหรับแถว
        for ($row = 1; $row <= $this->rows; $row++) {
            $patternRow = [
                'row_number' => $row,
                'spaces' => '',
                'stars' => '',
            ];

            // Inner Loop 1: สร้างช่องว่าง (spaces)
            for ($space = 1; $space <= ($this->rows - $row); $space++) {
                $patternRow['spaces'] .= '&nbsp;';
            }

            // Inner Loop 2: สร้างดาว (stars)
            // จำนวนดาว = (2 * row) - 1
            for ($star = 1; $star <= (2 * $row - 1); $star++) {
                $patternRow['stars'] .= '*';
            }

            $pattern[] = $patternRow;
        }

        return $pattern;
    }
}
