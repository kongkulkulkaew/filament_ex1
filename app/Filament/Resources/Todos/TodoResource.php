<?php

namespace App\Filament\Resources\Todos;

use App\Filament\Resources\Todos\Pages\CreateTodo;
use App\Filament\Resources\Todos\Pages\EditTodo;
use App\Filament\Resources\Todos\Pages\ListTodos;
use App\Filament\Resources\Todos\Schemas\TodoForm;
use App\Filament\Resources\Todos\Tables\TodosTable;
use App\Models\Todo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * TodoResource - Resource class สำหรับจัดการ Todo
 * 
 * ใน Yii1: คล้ายกับ Controller ที่จัดการ CRUD operations
 * แต่ใน Filament Resource จะรวม Form, Table, และ Pages เข้าด้วยกัน
 * 
 * เปรียบเทียบกับ Yii1:
 * - Yii1: class TodoController extends Controller { actionIndex(), actionCreate(), ... }
 * - Filament: TodoResource จัดการทั้ง Form, Table, และ Routes อัตโนมัติ
 */
class TodoResource extends Resource
{
    /**
     * กำหนด Model ที่ใช้ (เหมือน $model ใน Yii1 Controller)
     * 
     * ใน Yii1: public $model = 'Todo';
     */
    protected static ?string $model = Todo::class;

    /**
     * ไอคอนในเมนู (เหมือน navigation icon ใน Yii1)
     */
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;
    
    /**
     * ชื่อในเมนู
     */
    protected static ?string $navigationLabel = 'รายการ Todo';
    
    /**
     * ชื่อ Model แบบเอกพจน์
     */
    protected static ?string $modelLabel = 'Todo';
    
    /**
     * ชื่อ Model แบบพหูพจน์
     */
    protected static ?string $pluralModelLabel = 'Todos';
    
    /**
     * กลุ่มเมนู
     */
    protected static UnitEnum|string|null $navigationGroup = 'ตัวอย่าง';
    
    /**
     * ลำดับในเมนู
     */
    protected static ?int $navigationSort = 1;

    /**
     * กำหนด Form Schema (เหมือน form widget ใน Yii1)
     * 
     * ใน Yii1: 
     * $form = $this->beginWidget('CActiveForm');
     * echo $form->textField($model, 'title');
     * 
     * ใน Filament: ใช้ Schema API ที่แยกออกมาเป็น class
     */
    public static function form(Schema $schema): Schema
    {
        return TodoForm::configure($schema);
    }

    /**
     * กำหนด Table Schema (เหมือน CGridView ใน Yii1)
     * 
     * ใน Yii1:
     * $this->widget('zii.widgets.grid.CGridView', [
     *     'dataProvider' => $dataProvider,
     *     'columns' => [...]
     * ]);
     * 
     * ใน Filament: ใช้ Table API ที่แยกออกมาเป็น class
     */
    public static function table(Table $table): Table
    {
        return TodosTable::configure($table);
    }

    /**
     * กำหนด Relations (ถ้ามี) - ตัวอย่างนี้ไม่มี
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * กำหนด Pages (เหมือน actions ใน Yii1 Controller)
     * 
     * ใน Yii1:
     * public function actionIndex() { ... }  // List
     * public function actionCreate() { ... } // Create
     * public function actionUpdate($id) { ... } // Edit
     * 
     * ใน Filament: แต่ละ Page เป็น class แยกกัน
     */
    public static function getPages(): array
    {
        return [
            'index' => ListTodos::route('/'),           // List page
            'create' => CreateTodo::route('/create'),    // Create page
            'edit' => EditTodo::route('/{record}/edit'), // Edit page
        ];
    }
}
