<?php

namespace App\Filament\Resources\Todos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

/**
 * TodoForm - Form Schema สำหรับ Todo
 * 
 * ใน Yii1: คล้ายกับ form fields ที่เขียนใน view
 * 
 * เปรียบเทียบกับ Yii1:
 * - Yii1: 
 *   <?php echo $form->textField($model, 'title'); ?>
 *   <?php echo $form->textArea($model, 'description'); ?>
 * 
 * - Filament: ใช้ Schema API ที่แยกออกมาเป็น class
 *   ทำให้สามารถ reuse และ test ได้ง่ายขึ้น
 */
class TodoForm
{
    /**
     * configure() - กำหนด form fields
     * 
     * ใน Yii1: จะเขียน form fields ใน view file
     * ใน Filament: เขียนใน class นี้ แล้วเรียกใช้ใน Resource
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                /**
                 * TextInput - ช่องกรอกข้อความ (เหมือน textField ใน Yii1)
                 * 
                 * ใน Yii1: echo $form->textField($model, 'title');
                 * ใน Filament: TextInput::make('title')
                 */
                TextInput::make('title')
                    ->label('หัวข้อ')  // label ของ field
                    ->required()        // บังคับกรอก
                    ->maxLength(255)   // จำกัดความยาว
                    ->placeholder('กรอกหัวข้อ Todo')
                    ->columnSpanFull(), // ขยายเต็มความกว้าง
                
                /**
                 * Textarea - ช่องกรอกข้อความหลายบรรทัด (เหมือน textArea ใน Yii1)
                 * 
                 * ใน Yii1: echo $form->textArea($model, 'description');
                 * ใน Filament: Textarea::make('description')
                 */
                Textarea::make('description')
                    ->label('รายละเอียด')
                    ->rows(4)           // จำนวนบรรทัด
                    ->placeholder('กรอกรายละเอียด Todo')
                    ->columnSpanFull(),
                
                /**
                 * Toggle - สวิตช์เปิด/ปิด (เหมือน checkBox ใน Yii1)
                 * 
                 * ใน Yii1: echo $form->checkBox($model, 'is_completed');
                 * ใน Filament: Toggle::make('is_completed')
                 */
                Toggle::make('is_completed')
                    ->label('สถานะ')
                    ->helperText('เปิดใช้งานเมื่อ Todo เสร็จแล้ว')
                    ->default(false),   // ค่าเริ่มต้น
            ]);
    }
}
