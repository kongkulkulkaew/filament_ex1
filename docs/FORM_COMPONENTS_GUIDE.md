# 📝 คู่มือ Form Components ใน Filament 4.5

## 🎯 หลักการรับส่งค่าใน Filament

### 1. การประกาศตัวแปร (Properties)

```php
class FormExample extends Page implements HasForms
{
    use InteractsWithForms;
    
    // ประกาศ properties เพื่อเก็บค่าจาก form
    public ?string $name = null;        // TextInput, Textarea
    public ?int $age = null;            // Select (int)
    public bool $is_active = false;     // Toggle, Checkbox
    public ?string $gender = null;      // Radio, Select
    public ?string $birth_date = null;  // DatePicker
    public ?array $attachments = null;  // FileUpload
}
```

**หลักการ:**
- Properties จะเก็บค่าจาก form อัตโนมัติ
- ใช้ type hint เพื่อกำหนดประเภทข้อมูล
- ใช้ `?` สำหรับ nullable (สามารถเป็น null ได้)

---

### 2. การ Bind Component กับ Property

```php
public function form(Form $form): Form
{
    return $form->schema([
        // Bind กับ $this->name
        TextInput::make('name')
            ->label('ชื่อ')
            ->required(),
        
        // Bind กับ $this->is_active
        Toggle::make('is_active')
            ->label('เปิดใช้งาน'),
    ]);
}
```

**หลักการ:**
- `->make('property_name')` จะ bind component กับ property
- ชื่อใน `make()` ต้องตรงกับชื่อ property
- ค่าจะถูกส่งไปยัง property อัตโนมัติเมื่อผู้ใช้กรอกข้อมูล

---

### 3. Two-Way Data Binding

Filament ใช้ Livewire ทำให้มี Two-way binding:

```php
// เมื่อผู้ใช้กรอกข้อมูลใน TextInput
// ค่าจะถูกส่งไปยัง $this->name อัตโนมัติ

// เมื่อต้องการอ่านค่า
$name = $this->name;

// เมื่อต้องการกำหนดค่า
$this->name = 'John Doe';
```

**หลักการ:**
- **View → Model**: เมื่อผู้ใช้กรอกข้อมูล → ค่าถูกส่งไปยัง property
- **Model → View**: เมื่อเปลี่ยนค่าใน property → ฟอร์มอัปเดตอัตโนมัติ

---

### 4. การรับค่าเมื่อ Submit

```php
public function submit(): void
{
    // วิธีที่ 1: อ่านค่าจาก properties โดยตรง
    $name = $this->name;
    $email = $this->email;
    
    // วิธีที่ 2: ใช้ getState() เพื่อดึงข้อมูลทั้งหมด
    $data = $this->form->getState();
    // $data = [
    //     'name' => 'John Doe',
    //     'email' => 'john@example.com',
    //     ...
    // ]
    
    // วิธีที่ 3: Validate ก่อนรับค่า
    $validated = $this->form->getState();
    // จะ validate ตาม rules ที่กำหนดไว้
}
```

---

### 5. Real-time Updates (Live)

```php
TextInput::make('name')
    ->live() // อัปเดตแบบ real-time
    ->afterStateUpdated(function ($state) {
        // เมื่อค่าเปลี่ยน จะเรียก function นี้ทันที
        // $state = ค่าปัจจุบัน
        if ($state === 'admin') {
            // ทำอะไรก็ได้ เช่น แสดง field เพิ่มเติม
        }
    })
```

**หลักการ:**
- `->live()` ทำให้ component อัปเดตแบบ real-time
- `->afterStateUpdated()` เรียกเมื่อค่าเปลี่ยน
- มีค่า `->live(onBlur: true)` สำหรับอัปเดตเมื่อ focus ออก

---

## 📋 Form Components ที่ใช้บ่อย

### 1. TextInput - รับข้อความสั้นๆ

```php
TextInput::make('name')
    ->label('ชื่อ')
    ->placeholder('กรุณากรอกชื่อ')
    ->required()
    ->maxLength(255)
    ->helperText('คำอธิบาย')
    ->prefix('฿')        // เพิ่มข้อความด้านหน้า
    ->suffix('บาท')      // เพิ่มข้อความด้านหลัง
    ->mask('999-999-9999') // กำหนดรูปแบบ mask
    ->email()            // validate เป็น email
    ->tel()              // validate เป็นเบอร์โทร
    ->numeric()          // รับเฉพาะตัวเลข
    ->live()
```

**ประเภทข้อมูล:** `string`

---

### 2. Textarea - รับข้อความหลายบรรทัด

```php
Textarea::make('description')
    ->label('รายละเอียด')
    ->rows(4)            // จำนวนบรรทัดที่แสดง
    ->maxLength(500)
    ->helperText('คำอธิบาย')
    ->live()
```

**ประเภทข้อมูล:** `string`

---

### 3. Select - Dropdown list

```php
Select::make('country')
    ->label('ประเทศ')
    ->options([
        'th' => 'Thailand',
        'us' => 'United States',
    ])
    ->searchable()       // ค้นหาได้
    ->preload()          // โหลดข้อมูลล่วงหน้า
    ->multiple()         // เลือกหลายค่า (return array)
    ->native(false)      // ใช้ Filament select แทน native
    ->live()
```

**ประเภทข้อมูล:** `string | int | array` (ถ้า multiple)

---

### 4. Toggle - สวิตช์เปิด/ปิด

```php
Toggle::make('is_active')
    ->label('เปิดใช้งาน')
    ->default(true)      // ค่าเริ่มต้น
    ->inline(false)      // แสดงแบบ inline
    ->live()
```

**ประเภทข้อมูล:** `bool`

---

### 5. Checkbox - ช่องทำเครื่องหมาย

```php
Checkbox::make('agree_terms')
    ->label('ยอมรับข้อกำหนด')
    ->required()
    ->live()
```

**ประเภทข้อมูล:** `bool`

---

### 6. Radio - ปุ่มตัวเลือกเดียว

```php
Radio::make('gender')
    ->label('เพศ')
    ->options([
        'male' => 'ชาย',
        'female' => 'หญิง',
    ])
    ->default('male')
    ->inline()           // แสดงแบบ inline
    ->live()
```

**ประเภทข้อมูล:** `string`

---

### 7. DatePicker - เลือกวันที่

```php
DatePicker::make('birth_date')
    ->label('วันเกิด')
    ->displayFormat('d/m/Y')  // รูปแบบที่แสดง
    ->native(false)           // ใช้ Filament date picker
    ->minDate(now()->subYears(100)) // วันที่น้อยที่สุด
    ->maxDate(now())          // วันที่มากที่สุด
    ->live()
```

**ประเภทข้อมูล:** `string` (date format: Y-m-d)

---

### 8. FileUpload - อัปโหลดไฟล์

```php
FileUpload::make('attachments')
    ->label('ไฟล์แนบ')
    ->multiple()         // อัปโหลดหลายไฟล์
    ->acceptedFileTypes(['image/*', 'application/pdf'])
    ->maxSize(5120)      // ขนาดสูงสุด (KB)
    ->directory('attachments') // โฟลเดอร์ที่เก็บ
    ->visibility('public') // public หรือ private
```

**ประเภทข้อมูล:** `array` (array of file paths)

---

## 🔄 ตัวอย่างการใช้งาน

### ตัวอย่างที่ 1: Form แบบง่าย

```php
class SimpleForm extends Page implements HasForms
{
    use InteractsWithForms;
    
    public ?string $name = null;
    public ?string $email = null;
    
    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('ชื่อ')
                ->required(),
            
            TextInput::make('email')
                ->label('อีเมล')
                ->email()
                ->required(),
        ]);
    }
    
    public function submit(): void
    {
        $data = $this->form->getState();
        
        // บันทึกลง database หรือทำอะไรก็ได้
        User::create($data);
    }
}
```

---

### ตัวอย่างที่ 2: Conditional Fields (แสดง field ตามเงื่อนไข)

```php
Select::make('user_type')
    ->label('ประเภทผู้ใช้')
    ->options([
        'admin' => 'Admin',
        'user' => 'User',
    ])
    ->live()
    ->required(),

TextInput::make('admin_code')
    ->label('รหัส Admin')
    ->visible(fn ($get) => $get('user_type') === 'admin')
    ->required(fn ($get) => $get('user_type') === 'admin'),
```

**หลักการ:**
- ใช้ `->visible()` เพื่อซ่อน/แสดง field
- ใช้ `->required()` แบบ dynamic
- ใช้ `$get()` เพื่ออ่านค่าจาก field อื่น

---

### ตัวอย่างที่ 3: Auto-calculation

```php
TextInput::make('quantity')
    ->label('จำนวน')
    ->numeric()
    ->default(1)
    ->live()
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $unitPrice = $get('unit_price') ?? 0;
        $set('line_total', $state * $unitPrice);
    }),

TextInput::make('unit_price')
    ->label('ราคาต่อหน่วย')
    ->numeric()
    ->live()
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $quantity = $get('quantity') ?? 1;
        $set('line_total', $quantity * $state);
    }),

TextInput::make('line_total')
    ->label('รวม')
    ->disabled() // ไม่ให้แก้ไข
    ->dehydrated(), // แต่ยังส่งค่าไปด้วย
```

**หลักการ:**
- ใช้ `->afterStateUpdated()` เพื่อคำนวณอัตโนมัติ
- ใช้ `$set()` เพื่อกำหนดค่าให้ field อื่น
- ใช้ `$get()` เพื่ออ่านค่าจาก field อื่น

---

## 🎓 สรุป

1. **ประกาศ Properties**: กำหนดตัวแปรเพื่อเก็บค่า
2. **Bind Components**: ใช้ `->make('property_name')` เพื่อ bind
3. **Two-Way Binding**: ค่าถูก sync อัตโนมัติระหว่าง View และ Model
4. **Submit**: อ่านค่าจาก properties หรือ `getState()`
5. **Live Updates**: ใช้ `->live()` สำหรับ real-time updates

---

## 📚 เอกสารเพิ่มเติม

- [Filament Forms Documentation](https://filamentphp.com/docs/forms)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Laravel Validation](https://laravel.com/docs/validation)
