<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class BMICalculator extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected string $view = 'filament.pages.bmi-calculator';

    protected static ?string $navigationLabel = 'คำนวณ BMI';

    protected static ?string $title = 'โปรแกรมคำนวณ BMI (Body Mass Index)';

    protected static ?int $navigationSort = 6;

    // Properties สำหรับเก็บข้อมูล
    public ?float $weight = null; // น้ำหนัก (กิโลกรัม)
    public ?float $height = null; // ส่วนสูง (เซนติเมตร)

    /**
     * mount() - เรียกเมื่อโหลดหน้าแรก
     * กำหนดค่าเริ่มต้น
     */
    public function mount(): void
    {
        $this->weight = 70.0; // น้ำหนักเริ่มต้น 70 กิโลกรัม
        $this->height = 170.0; // ส่วนสูงเริ่มต้น 170 เซนติเมตร
    }

    /**
     * form() - กำหนดโครงสร้าง Form
     * 
     * หลักการ:
     * - TextInput::make('weight') จะ bind กับ $this->weight
     * - TextInput::make('height') จะ bind กับ $this->height
     * - ใช้ ->live() เพื่อให้แสดงผลแบบ real-time
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('weight')
                    ->label('น้ำหนัก')
                    ->numeric()
                    ->required()
                    ->default(70)
                    ->minValue(1)
                    ->maxValue(300)
                    ->step(0.1)
                    ->suffix('กิโลกรัม')
                    ->helperText('กรุณาป้อนน้ำหนัก (กิโลกรัม)')
                    ->live(),

                TextInput::make('height')
                    ->label('ส่วนสูง')
                    ->numeric()
                    ->required()
                    ->default(170)
                    ->minValue(50)
                    ->maxValue(250)
                    ->step(0.1)
                    ->suffix('เซนติเมตร')
                    ->helperText('กรุณาป้อนส่วนสูง (เซนติเมตร)')
                    ->live(),
            ]);
    }

    /**
     * calculateBMI() - คำนวณค่า BMI
     * 
     * สูตร: BMI = น้ำหนัก (kg) / (ส่วนสูง (m))²
     * 
     * @return float|null
     */
    public function calculateBMI(): ?float
    {
        if ($this->weight === null || $this->height === null || $this->height <= 0) {
            return null;
        }

        // แปลงส่วนสูงจากเซนติเมตรเป็นเมตร
        $heightInMeters = $this->height / 100;

        // คำนวณ BMI
        $bmi = $this->weight / ($heightInMeters * $heightInMeters);

        return round($bmi, 2);
    }

    /**
     * getBMICategory() - ตรวจสอบหมวดหมู่ BMI โดยใช้ if-else
     * 
     * หลักการ:
     * - ใช้ if-else เพื่อตรวจสอบช่วงค่า BMI
     * - แต่ละช่วงจะส่งคืนข้อมูลที่แตกต่างกัน
     * 
     * @return array ['category' => string, 'description' => string, 'color' => string, 'recommendation' => string]
     */
    public function getBMICategory(): array
    {
        $bmi = $this->calculateBMI();

        if ($bmi === null) {
            return [
                'category' => '-',
                'description' => 'กรุณาป้อนข้อมูลที่ถูกต้อง',
                'color' => 'gray',
                'recommendation' => '',
            ];
        }

        // ใช้ if-else เพื่อตรวจสอบช่วงค่า BMI
        if ($bmi < 18.5) {
            return [
                'category' => 'น้ำหนักน้อย / ผอม',
                'description' => 'คุณมีน้ำหนักน้อยกว่าปกติ',
                'color' => 'warning',
                'recommendation' => 'ควรรับประทานอาหารที่มีประโยชน์และออกกำลังกายเพื่อเพิ่มน้ำหนัก',
            ];
        } elseif ($bmi >= 18.5 && $bmi < 23) {
            return [
                'category' => 'ปกติ',
                'description' => 'คุณมีน้ำหนักอยู่ในเกณฑ์ปกติ',
                'color' => 'success',
                'recommendation' => 'รักษาน้ำหนักให้อยู่ในเกณฑ์นี้ต่อไป',
            ];
        } elseif ($bmi >= 23 && $bmi < 25) {
            return [
                'category' => 'ท้วม / โรคอ้วนระดับ 1',
                'description' => 'คุณมีน้ำหนักเกินเล็กน้อย',
                'color' => 'warning',
                'recommendation' => 'ควรควบคุมอาหารและออกกำลังกาย',
            ];
        } elseif ($bmi >= 25 && $bmi < 30) {
            return [
                'category' => 'อ้วน / โรคอ้วนระดับ 2',
                'description' => 'คุณมีน้ำหนักเกิน',
                'color' => 'danger',
                'recommendation' => 'ควรลดน้ำหนักโดยการควบคุมอาหารและออกกำลังกายอย่างสม่ำเสมอ',
            ];
        } else {
            // bmi >= 30
            return [
                'category' => 'อ้วนมาก / โรคอ้วนระดับ 3',
                'description' => 'คุณมีน้ำหนักเกินมาก',
                'color' => 'danger',
                'recommendation' => 'ควรปรึกษาแพทย์เพื่อวางแผนการลดน้ำหนักที่เหมาะสม',
            ];
        }
    }

    /**
     * getBMIScale() - ส่งคืนมาตรฐาน BMI ทั้งหมด
     * 
     * @return array
     */
    public function getBMIScale(): array
    {
        return [
            ['min' => 0, 'max' => 18.5, 'category' => 'น้ำหนักน้อย / ผอม', 'color' => 'warning'],
            ['min' => 18.5, 'max' => 23, 'category' => 'ปกติ', 'color' => 'success'],
            ['min' => 23, 'max' => 25, 'category' => 'ท้วม / โรคอ้วนระดับ 1', 'color' => 'warning'],
            ['min' => 25, 'max' => 30, 'category' => 'อ้วน / โรคอ้วนระดับ 2', 'color' => 'danger'],
            ['min' => 30, 'max' => 100, 'category' => 'อ้วนมาก / โรคอ้วนระดับ 3', 'color' => 'danger'],
        ];
    }

    /**
     * generateBMITable() - สร้างตาราง BMI ตัวอย่างโดยใช้ for loop
     * 
     * หลักการ:
     * - ใช้ for loop เพื่อสร้างข้อมูลหลายรายการ
     * - แต่ละรายการจะคำนวณ BMI จากน้ำหนักและส่วนสูงที่แตกต่างกัน
     * 
     * @return array
     */
    public function generateBMITable(): array
    {
        $table = [];

        // ใช้ for loop เพื่อสร้างตารางตัวอย่าง
        // วนลูปสำหรับส่วนสูงตั้งแต่ 150-190 เซนติเมตร (เพิ่มทีละ 10)
        for ($height = 150; $height <= 190; $height += 10) {
            $row = [
                'height' => $height,
                'weights' => [],
            ];

            // วนลูปสำหรับน้ำหนักตั้งแต่ 40-120 กิโลกรัม (เพิ่มทีละ 10)
            for ($weight = 40; $weight <= 120; $weight += 10) {
                // คำนวณ BMI
                $heightInMeters = $height / 100;
                $bmi = $weight / ($heightInMeters * $heightInMeters);
                $bmi = round($bmi, 1);

                // ใช้ if-else เพื่อกำหนดหมวดหมู่
                $category = '';
                $color = '';
                if ($bmi < 18.5) {
                    $category = 'ผอม';
                    $color = 'warning';
                } elseif ($bmi >= 18.5 && $bmi < 23) {
                    $category = 'ปกติ';
                    $color = 'success';
                } elseif ($bmi >= 23 && $bmi < 25) {
                    $category = 'ท้วม';
                    $color = 'warning';
                } elseif ($bmi >= 25 && $bmi < 30) {
                    $category = 'อ้วน';
                    $color = 'danger';
                } else {
                    $category = 'อ้วนมาก';
                    $color = 'danger';
                }

                $row['weights'][] = [
                    'weight' => $weight,
                    'bmi' => $bmi,
                    'category' => $category,
                    'color' => $color,
                ];
            }

            $table[] = $row;
        }

        return $table;
    }
}
