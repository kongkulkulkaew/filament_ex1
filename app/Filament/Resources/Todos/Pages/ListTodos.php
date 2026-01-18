<?php

namespace App\Filament\Resources\Todos\Pages;

use App\Filament\Resources\Todos\TodoResource;
use App\Models\Todo;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * ListTodos - หน้าแสดงรายการ Todo ทั้งหมด
 * 
 * ใน Yii1: คล้ายกับ actionIndex() ใน Controller
 * 
 * เปรียบเทียบกับ Yii1:
 * - Yii1:
 *   public function actionIndex() {
 *       $dataProvider = new CActiveDataProvider('Todo');
 *       $this->render('index', ['dataProvider' => $dataProvider]);
 *   }
 * 
 * - Filament: แต่ละ Page เป็น class แยกกัน และจัดการเอง
 */
class ListTodos extends ListRecords
{
    /**
     * กำหนด Resource ที่ใช้ (เหมือน $model ใน Yii1 Controller)
     */
    protected static string $resource = TodoResource::class;

    /**
     * getHeaderActions() - actions ที่แสดงด้านบน (เหมือน create button)
     * 
     * ใน Yii1: จะต้องเขียน link เองใน view
     * ใน Filament: กำหนดตรงนี้ได้เลย
     */
    protected function getHeaderActions(): array
    {
        return [
            /**
             * CreateAction - ปุ่มสร้างใหม่ (เหมือน link ไป actionCreate)
             * 
             * ใน Yii1: CHtml::link('สร้างใหม่', ['create'])
             * ใน Filament: CreateAction::make()
             */
            CreateAction::make()
                ->label('สร้าง Todo ใหม่'),
        ];
    }
    
    /**
     * getTableQuery() - ต้อง return Builder|Relation|null
     * 
     * เนื่องจาก Todo ไม่ใช้ Eloquent เราจะ return null
     * และใช้ getTableRecords() แทน
     */
    protected function getTableQuery(): Builder|Relation|null
    {
        // return null เพราะเราไม่ใช้ Eloquent Builder
        // ข้อมูลจะถูกดึงผ่าน getTableRecords() แทน
        return null;
    }
    
    /**
     * getTableRecords() - ดึงข้อมูล Todo ทั้งหมดจาก array
     * 
     * Override method นี้เพื่อใช้ custom data source แทน Eloquent
     * 
     * ใน Yii1: $dataProvider = new CActiveDataProvider('Todo');
     * ใน Filament: override method นี้เพื่อใช้ custom data source
     * 
     * หมายเหตุ: ต้องเป็น public เพราะ parent class กำหนดไว้เป็น public
     */
    public function getTableRecords(): Collection
    {
        // ดึง Todo ทั้งหมดจาก array และแปลงเป็น Collection
        // Collection เป็น Laravel helper class ที่ช่วยจัดการ array
        return Collection::make(Todo::all());
    }
}
