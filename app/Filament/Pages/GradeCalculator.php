<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class GradeCalculator extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected string $view = 'filament.pages.grade-calculator';

    protected static ?string $navigationLabel = 'ตัดเกรด';

    protected static ?string $title = 'โปรแกรมตัดเกรด';

    protected static ?int $navigationSort = 5;

    // Property สำหรับเก็บคะแนนดิบที่ผู้ใช้ป้อน
    public ?float $score = null;

    /**
     * mount() - เรียกเมื่อโหลดหน้าแรก
     * กำหนดค่าเริ่มต้น
     */
    public function mount(): void
    {
        $this->score = 75.0; // ค่าเริ่มต้นเป็น 75
    }

    /**
     * form() - กำหนดโครงสร้าง Form
     * 
     * หลักการ:
     * - TextInput::make('score') จะ bind กับ $this->score
     * - เมื่อผู้ใช้ป้อนค่า ค่าจะถูกส่งไปยัง $this->score อัตโนมัติ
     * - ใช้ ->live() เพื่อให้แสดงผลแบบ real-time
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('score')
                    ->label('คะแนนดิบ')
                    ->numeric()
                    ->required()
                    ->default(75)
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01) // รับทศนิยม 2 ตำแหน่ง
                    ->suffix('คะแนน')
                    ->helperText('กรุณาป้อนคะแนนดิบ (0-100)')
                    ->live() // อัปเดตแบบ real-time เมื่อค่าเปลี่ยน
                    ->afterStateUpdated(function ($state) {
                        // เมื่อค่าเปลี่ยน จะเรียก function นี้
                        // $state = ค่าปัจจุบันที่ผู้ใช้ป้อน
                    }),
            ]);
    }

    /**
     * calculateGrade() - คำนวณเกรดจากคะแนนดิบ
     * 
     * มาตรฐานการตัดเกรด:
     * - A: 80-100
     * - B+: 75-79
     * - B: 70-74
     * - C+: 65-69
     * - C: 60-64
     * - D+: 55-59
     * - D: 50-54
     * - F: 0-49
     * 
     * @return array ['grade' => string, 'description' => string, 'color' => string]
     */
    public function calculateGrade(): array
    {
        if ($this->score === null || $this->score < 0 || $this->score > 100) {
            return [
                'grade' => '-',
                'description' => 'กรุณาป้อนคะแนนที่ถูกต้อง',
                'color' => 'gray',
            ];
        }

        $score = (float) $this->score;

        if ($score >= 80) {
            return [
                'grade' => 'A',
                'description' => 'ดีเยี่ยม',
                'color' => 'success',
                'min_score' => 80,
                'max_score' => 100,
            ];
        } elseif ($score >= 75) {
            return [
                'grade' => 'B+',
                'description' => 'ดีมาก',
                'color' => 'info',
                'min_score' => 75,
                'max_score' => 79,
            ];
        } elseif ($score >= 70) {
            return [
                'grade' => 'B',
                'description' => 'ดี',
                'color' => 'info',
                'min_score' => 70,
                'max_score' => 74,
            ];
        } elseif ($score >= 65) {
            return [
                'grade' => 'C+',
                'description' => 'พอใช้',
                'color' => 'warning',
                'min_score' => 65,
                'max_score' => 69,
            ];
        } elseif ($score >= 60) {
            return [
                'grade' => 'C',
                'description' => 'พอใช้',
                'color' => 'warning',
                'min_score' => 60,
                'max_score' => 64,
            ];
        } elseif ($score >= 55) {
            return [
                'grade' => 'D+',
                'description' => 'อ่อน',
                'color' => 'danger',
                'min_score' => 55,
                'max_score' => 59,
            ];
        } elseif ($score >= 50) {
            return [
                'grade' => 'D',
                'description' => 'อ่อน',
                'color' => 'danger',
                'min_score' => 50,
                'max_score' => 54,
            ];
        } else {
            return [
                'grade' => 'F',
                'description' => 'ตก',
                'color' => 'danger',
                'min_score' => 0,
                'max_score' => 49,
            ];
        }
    }

    /**
     * getGradeScale() - ส่งคืนมาตรฐานการตัดเกรดทั้งหมด
     */
    public function getGradeScale(): array
    {
        return [
            ['grade' => 'A', 'min' => 80, 'max' => 100, 'description' => 'ดีเยี่ยม', 'color' => 'success'],
            ['grade' => 'B+', 'min' => 75, 'max' => 79, 'description' => 'ดีมาก', 'color' => 'info'],
            ['grade' => 'B', 'min' => 70, 'max' => 74, 'description' => 'ดี', 'color' => 'info'],
            ['grade' => 'C+', 'min' => 65, 'max' => 69, 'description' => 'พอใช้', 'color' => 'warning'],
            ['grade' => 'C', 'min' => 60, 'max' => 64, 'description' => 'พอใช้', 'color' => 'warning'],
            ['grade' => 'D+', 'min' => 55, 'max' => 59, 'description' => 'อ่อน', 'color' => 'danger'],
            ['grade' => 'D', 'min' => 50, 'max' => 54, 'description' => 'อ่อน', 'color' => 'danger'],
            ['grade' => 'F', 'min' => 0, 'max' => 49, 'description' => 'ตก', 'color' => 'danger'],
        ];
    }
}
