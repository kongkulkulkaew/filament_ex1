<?php

namespace App\Filament\Resources\Todos\Tables;

use App\Models\Todo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

/**
 * TodosTable - Table Schema สำหรับแสดงรายการ Todo
 * 
 * ใน Yii1: คล้ายกับ CGridView widget
 * 
 * เปรียบเทียบกับ Yii1:
 * - Yii1:
 *   $this->widget('zii.widgets.grid.CGridView', [
 *       'dataProvider' => $dataProvider,
 *       'columns' => [
 *           'title',
 *           'description',
 *           ['name' => 'is_completed', 'type' => 'boolean'],
 *       ]
 *   ]);
 * 
 * - Filament: ใช้ Table API ที่แยกออกมาเป็น class
 */
class TodosTable
{
    /**
     * configure() - กำหนด table columns, filters, และ actions
     * 
     * ใน Yii1: จะเขียนใน view file
     * ใน Filament: เขียนใน class นี้ แล้วเรียกใช้ใน Resource
     */
    public static function configure(Table $table): Table
    {
        return $table
            /**
             * modifyQueryUsing() - แก้ไข query เพื่อใช้ Collection แทน Eloquent Builder
             * 
             * เนื่องจาก Todo ไม่ใช้ Eloquent เราต้องใช้ Collection แทน
             * แต่ Filament Table ต้องการ Builder ดังนั้นเราจะใช้วิธีนี้
             */
            ->modifyQueryUsing(function ($query) {
                // ไม่ต้องทำอะไร เพราะเราจะใช้ getTableRecords() ใน ListTodos แทน
                return $query;
            })
            /**
             * columns() - กำหนด columns ที่จะแสดง (เหมือน 'columns' ใน CGridView)
             * 
             * ใน Yii1: 'columns' => ['title', 'description']
             * ใน Filament: columns([TextColumn::make('title'), ...])
             */
            ->columns([
                /**
                 * TextColumn - แสดงข้อความ (เหมือน column 'title' ใน Yii1)
                 * 
                 * ใน Yii1: 'title'
                 * ใน Filament: TextColumn::make('title')
                 */
                TextColumn::make('title')
                    ->label('หัวข้อ')
                    ->searchable()  // สามารถค้นหาได้
                    ->sortable(),    // สามารถเรียงลำดับได้
                
                TextColumn::make('description')
                    ->label('รายละเอียด')
                    ->limit(50)     // จำกัดความยาวที่แสดง
                    ->tooltip(function ($record) {
                        // แสดง tooltip เมื่อ hover
                        return $record->description;
                    }),
                
                /**
                 * IconColumn - แสดงไอคอน (เหมือน column type 'boolean' ใน Yii1)
                 * 
                 * ใน Yii1: ['name' => 'is_completed', 'type' => 'boolean']
                 * ใน Filament: IconColumn::make('is_completed')->boolean()
                 */
                IconColumn::make('is_completed')
                    ->label('สถานะ')
                    ->boolean()      // แสดงเป็น ✓ หรือ ✗
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                TextColumn::make('created_at')
                    ->label('สร้างเมื่อ')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // ซ่อนไว้โดย default
            ])
            /**
             * filters() - กำหนด filters (เหมือน filter ใน CGridView)
             * 
             * ใน Yii1: จะต้องเขียน filter logic เอง
             * ใน Filament: มี built-in filters ให้ใช้
             */
            ->filters([
                /**
                 * TernaryFilter - filter แบบ 3 สถานะ (ใช่/ไม่ใช่/ทั้งหมด)
                 * 
                 * ใน Yii1: จะต้องเขียนเอง
                 * ใน Filament: มีให้ใช้เลย
                 */
                TernaryFilter::make('is_completed')
                    ->label('สถานะการเสร็จสิ้น')
                    ->placeholder('ทั้งหมด')
                    ->trueLabel('เสร็จแล้ว')
                    ->falseLabel('ยังไม่เสร็จ'),
            ])
            /**
             * recordActions() - actions สำหรับแต่ละ record (เหมือน buttons ใน CGridView)
             * 
             * ใน Yii1: จะต้องเขียน link หรือ button เอง
             * ใน Filament: มี built-in actions ให้ใช้
             */
            ->recordActions([
                EditAction::make(),    // ปุ่มแก้ไข
                DeleteAction::make(),  // ปุ่มลบ
            ])
            /**
             * toolbarActions() - actions สำหรับหลาย records พร้อมกัน
             * 
             * ใน Yii1: จะต้องเขียนเอง
             * ใน Filament: มีให้ใช้เลย
             */
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), // ลบหลายรายการพร้อมกัน
                ]),
            ])
            /**
             * defaultSort() - เรียงลำดับเริ่มต้น
             * 
             * ใน Yii1: จะต้องกำหนดใน dataProvider
             * ใน Filament: กำหนดตรงนี้ได้เลย
             */
            ->defaultSort('created_at', 'desc');
    }
}
