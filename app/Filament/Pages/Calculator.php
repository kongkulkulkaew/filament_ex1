<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Calculator extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;
    
    protected string $view = 'filament.pages.calculator';
    
    protected static ?string $navigationLabel = 'เครื่องคิดเลข';
    
    protected static ?string $title = 'เครื่องคิดเลข';
    
    protected static ?int $navigationSort = 2;
    
    // Properties สำหรับเครื่องคิดเลข
    public string $display = '0';
    public string $previousValue = '';
    public string $operator = '';
    public bool $waitingForNewValue = false;
    
    public function mount(): void
    {
        $this->display = '0';
    }
    
    // ฟังก์ชันสำหรับกดตัวเลข
    public function inputNumber(string $number): void
    {
        if ($this->waitingForNewValue) {
            $this->display = $number;
            $this->waitingForNewValue = false;
        } else {
            $this->display = $this->display === '0' ? $number : $this->display . $number;
        }
    }
    
    // ฟังก์ชันสำหรับกดจุดทศนิยม
    public function inputDecimal(): void
    {
        if ($this->waitingForNewValue) {
            $this->display = '0.';
            $this->waitingForNewValue = false;
        } elseif (strpos($this->display, '.') === false) {
            $this->display .= '.';
        }
    }
    
    // ฟังก์ชันสำหรับการคำนวณ
    public function calculate(): void
    {
        if ($this->previousValue === '' || $this->operator === '') {
            return;
        }
        
        $prev = (float) $this->previousValue;
        $current = (float) $this->display;
        $result = 0;
        
        switch ($this->operator) {
            case '+':
                $result = $prev + $current;
                break;
            case '-':
                $result = $prev - $current;
                break;
            case '*':
                $result = $prev * $current;
                break;
            case '/':
                if ($current != 0) {
                    $result = $prev / $current;
                } else {
                    $this->display = 'Error';
                    $this->clear();
                    return;
                }
                break;
        }
        
        // แสดงผลลัพธ์โดยตัดทศนิยมที่ไม่จำเป็น
        $this->display = (string) $result;
        if (strpos($this->display, '.') !== false) {
            $this->display = rtrim(rtrim($this->display, '0'), '.');
        }
        
        $this->operator = '';
        $this->previousValue = '';
        $this->waitingForNewValue = true;
    }
    
    // ฟังก์ชันสำหรับตั้งค่า operator
    public function setOperator(string $op): void
    {
        if ($this->operator !== '' && !$this->waitingForNewValue) {
            $this->calculate();
        }
        
        $this->previousValue = $this->display;
        $this->operator = $op;
        $this->waitingForNewValue = true;
    }
    
    // ฟังก์ชันสำหรับล้างค่า
    public function clear(): void
    {
        $this->display = '0';
        $this->previousValue = '';
        $this->operator = '';
        $this->waitingForNewValue = false;
    }
    
    // ฟังก์ชันสำหรับลบตัวเลขตัวสุดท้าย
    public function backspace(): void
    {
        if (strlen($this->display) > 1) {
            $this->display = substr($this->display, 0, -1);
        } else {
            $this->display = '0';
        }
    }
}