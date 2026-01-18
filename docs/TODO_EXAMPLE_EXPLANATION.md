# 📚 คู่มือเรียนรู้ Filament สำหรับผู้ที่เคยใช้ Yii1

## 🎯 ภาพรวม

ตัวอย่างนี้เป็น **Todo List Application** ที่สร้างด้วย **Filament 4.5** และ **Laravel 12** โดย**ไม่ใช้ฐานข้อมูล** (เก็บข้อมูลใน memory แทน) เพื่อให้เข้าใจโครงสร้างและแนวคิดของ Filament ได้ง่ายขึ้น

---

## 📁 โครงสร้างไฟล์

```
app/
├── Models/
│   └── Todo.php                    # Model (ไม่ใช้ Eloquent)
├── Filament/
│   └── Resources/
│       └── Todos/
│           ├── TodoResource.php   # Resource class (เหมือน Controller)
│           ├── Schemas/
│           │   └── TodoForm.php   # Form Schema (เหมือน form fields)
│           ├── Tables/
│           │   └── TodosTable.php # Table Schema (เหมือน CGridView)
│           └── Pages/
│               ├── ListTodos.php  # List page (เหมือน actionIndex)
│               ├── CreateTodo.php # Create page (เหมือน actionCreate)
│               └── EditTodo.php   # Edit page (เหมือน actionUpdate)
```

---

## 🔄 เปรียบเทียบ Yii1 vs Filament

### 1. Model (Todo.php)

#### Yii1:
```php
class Todo extends CActiveRecord {
    public function tableName() {
        return 'todos';
    }
    
    public function rules() {
        return [
            ['title', 'required'],
            ['title', 'length', 'max' => 255],
        ];
    }
}
```

#### Filament (ไม่ใช้ฐานข้อมูล):
```php
class Todo {
    protected static array $todos = [];  // เก็บข้อมูลใน array
    
    public static function all(): array {
        return self::$todos;
    }
    
    public function save(): bool {
        self::$todos[] = $this;
        return true;
    }
}
```

**ความแตกต่าง:**
- **Yii1**: ใช้ `CActiveRecord` ที่เชื่อมต่อกับ database
- **Filament**: ใช้ class ธรรมดา + static array (ไม่ต้องใช้ Eloquent)

---

### 2. Controller vs Resource

#### Yii1:
```php
class TodoController extends Controller {
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Todo');
        $this->render('index', ['dataProvider' => $dataProvider]);
    }
    
    public function actionCreate() {
        $model = new Todo();
        if (isset($_POST['Todo'])) {
            $model->attributes = $_POST['Todo'];
            if ($model->save()) {
                $this->redirect(['index']);
            }
        }
        $this->render('create', ['model' => $model]);
    }
}
```

#### Filament:
```php
class TodoResource extends Resource {
    protected static ?string $model = Todo::class;
    
    public static function form(Schema $schema): Schema {
        return TodoForm::configure($schema);
    }
    
    public static function table(Table $table): Table {
        return TodosTable::configure($table);
    }
    
    public static function getPages(): array {
        return [
            'index' => ListTodos::route('/'),
            'create' => CreateTodo::route('/create'),
            'edit' => EditTodo::route('/{record}/edit'),
        ];
    }
}
```

**ความแตกต่าง:**
- **Yii1**: Controller จัดการทั้ง logic และ view
- **Filament**: Resource เป็น configuration class ที่แยก Form, Table, และ Pages ออกจากกัน

---

### 3. Form Fields

#### Yii1:
```php
// ใน view file
<?php $form = $this->beginWidget('CActiveForm'); ?>
    <?php echo $form->textField($model, 'title'); ?>
    <?php echo $form->textArea($model, 'description'); ?>
    <?php echo $form->checkBox($model, 'is_completed'); ?>
<?php $this->endWidget(); ?>
```

#### Filament:
```php
// ใน TodoForm.php
public static function configure(Schema $schema): Schema {
    return $schema->components([
        TextInput::make('title')
            ->label('หัวข้อ')
            ->required(),
        Textarea::make('description')
            ->label('รายละเอียด'),
        Toggle::make('is_completed')
            ->label('สถานะ'),
    ]);
}
```

**ความแตกต่าง:**
- **Yii1**: เขียน form ใน view file
- **Filament**: เขียน form ใน class แยก (reusable และ testable)

---

### 4. Grid/Table

#### Yii1:
```php
// ใน view file
$this->widget('zii.widgets.grid.CGridView', [
    'dataProvider' => $dataProvider,
    'columns' => [
        'title',
        'description',
        ['name' => 'is_completed', 'type' => 'boolean'],
        [
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
        ],
    ],
]);
```

#### Filament:
```php
// ใน TodosTable.php
public static function configure(Table $table): Table {
    return $table
        ->columns([
            TextColumn::make('title')
                ->searchable()
                ->sortable(),
            IconColumn::make('is_completed')
                ->boolean(),
        ])
        ->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
}
```

**ความแตกต่าง:**
- **Yii1**: เขียน grid ใน view file
- **Filament**: เขียน table ใน class แยก (มี features มากกว่า)

---

## 🔍 การทำงานของแต่ละส่วน

### 1. Todo Model (`app/Models/Todo.php`)

**หน้าที่:**
- เก็บข้อมูล Todo ใน static array (`$todos`)
- จัดการ CRUD operations (Create, Read, Update, Delete)

**Methods สำคัญ:**
- `all()` - ดึง Todo ทั้งหมด (เหมือน `findAll()` ใน Yii1)
- `find($id)` - หา Todo ด้วย ID (เหมือน `findByPk()` ใน Yii1)
- `save()` - บันทึก Todo (เหมือน `save()` ใน Yii1)
- `delete()` - ลบ Todo (เหมือน `delete()` ใน Yii1)

---

### 2. TodoResource (`app/Filament/Resources/Todos/TodoResource.php`)

**หน้าที่:**
- เป็น configuration class ที่รวม Form, Table, และ Pages เข้าด้วยกัน
- กำหนด navigation (เมนู, ไอคอน, ชื่อ)

**Properties สำคัญ:**
- `$model` - กำหนด Model ที่ใช้
- `$navigationIcon` - ไอคอนในเมนู
- `$navigationLabel` - ชื่อในเมนู

**Methods สำคัญ:**
- `form()` - กำหนด Form Schema
- `table()` - กำหนด Table Schema
- `getPages()` - กำหนด Pages (routes)

---

### 3. TodoForm (`app/Filament/Resources/Todos/Schemas/TodoForm.php`)

**หน้าที่:**
- กำหนด form fields ที่จะแสดงในหน้า Create และ Edit

**Components ที่ใช้:**
- `TextInput` - ช่องกรอกข้อความ (เหมือน `textField` ใน Yii1)
- `Textarea` - ช่องกรอกข้อความหลายบรรทัด (เหมือน `textArea` ใน Yii1)
- `Toggle` - สวิตช์เปิด/ปิด (เหมือน `checkBox` ใน Yii1)

---

### 4. TodosTable (`app/Filament/Resources/Todos/Tables/TodosTable.php`)

**หน้าที่:**
- กำหนด columns, filters, และ actions สำหรับ table

**Features:**
- `columns()` - กำหนด columns ที่จะแสดง
- `filters()` - กำหนด filters (ค้นหา)
- `recordActions()` - actions สำหรับแต่ละ record (แก้ไข, ลบ)
- `toolbarActions()` - actions สำหรับหลาย records พร้อมกัน

---

### 5. ListTodos (`app/Filament/Resources/Todos/Pages/ListTodos.php`)

**หน้าที่:**
- หน้าแสดงรายการ Todo ทั้งหมด (เหมือน `actionIndex()` ใน Yii1)

**Methods สำคัญ:**
- `getTableQuery()` - ดึงข้อมูล Todo ทั้งหมด (override เพื่อใช้ array)
- `getHeaderActions()` - actions ที่แสดงด้านบน (ปุ่มสร้างใหม่)

---

### 6. CreateTodo (`app/Filament/Resources/Todos/Pages/CreateTodo.php`)

**หน้าที่:**
- หน้าสร้าง Todo ใหม่ (เหมือน `actionCreate()` ใน Yii1)

**Methods สำคัญ:**
- `mutateFormDataBeforeCreate()` - แก้ไขข้อมูลก่อนบันทึก
- `handleRecordCreation()` - บันทึก Todo ใหม่ (override เพื่อใช้ array)
- `getRedirectUrl()` - URL ที่จะ redirect หลังสร้างเสร็จ

---

### 7. EditTodo (`app/Filament/Resources/Todos/Pages/EditTodo.php`)

**หน้าที่:**
- หน้าแก้ไข Todo (เหมือน `actionUpdate($id)` ใน Yii1)

**Methods สำคัญ:**
- `resolveRecord()` - ดึง Todo จาก array ด้วย ID (override เพื่อใช้ array)
- `handleRecordUpdate()` - บันทึก Todo ที่แก้ไข (override เพื่อใช้ array)
- `getRedirectUrl()` - URL ที่จะ redirect หลังแก้ไขเสร็จ

---

## 🚀 การใช้งาน

### 1. เข้าใช้งาน
- ไปที่ `/admin/todos` หรือคลิกที่เมนู "รายการ Todo"

### 2. สร้าง Todo ใหม่
- คลิกปุ่ม "สร้าง Todo ใหม่"
- กรอกข้อมูล (หัวข้อ, รายละเอียด, สถานะ)
- คลิก "สร้าง"

### 3. แก้ไข Todo
- คลิกปุ่ม "แก้ไข" ในแถวที่ต้องการ
- แก้ไขข้อมูล
- คลิก "บันทึก"

### 4. ลบ Todo
- คลิกปุ่ม "ลบ" ในแถวที่ต้องการ
- ยืนยันการลบ

---

## 💡 สรุปความแตกต่างหลัก

| Yii1 | Filament |
|------|----------|
| Controller จัดการทุกอย่าง | Resource เป็น configuration class |
| Form เขียนใน view | Form เขียนใน Schema class |
| Grid เขียนใน view | Table เขียนใน Table class |
| แต่ละ action เป็น method | แต่ละ page เป็น class |
| ต้องเขียน routing เอง | Routing สร้างอัตโนมัติ |
| ต้องเขียน validation เอง | Validation built-in |

---

## 🎓 สิ่งที่ได้เรียนรู้

1. **Filament Resource** = Controller + Form + Table + Routes
2. **Schema API** = Form fields ที่แยกออกมาเป็น class
3. **Table API** = Grid ที่แยกออกมาเป็น class
4. **Pages** = แต่ละหน้าเป็น class แยกกัน
5. **ไม่ต้องใช้ Eloquent** = สามารถใช้ custom Model ได้

---

## 📝 หมายเหตุ

- ข้อมูลจะหายเมื่อรีสตาร์ทเซิร์ฟเวอร์ (เพราะเก็บใน memory)
- ตัวอย่างนี้ใช้เพื่อการเรียนรู้เท่านั้น
- ในโปรเจกต์จริงควรใช้ Eloquent Model + Database

---

## 🔗 เอกสารเพิ่มเติม

- [Filament Documentation](https://filamentphp.com/docs)
- [Laravel Documentation](https://laravel.com/docs)
