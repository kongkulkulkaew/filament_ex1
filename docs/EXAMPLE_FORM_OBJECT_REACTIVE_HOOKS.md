# 📝 คำอธิบาย Reactive Hooks ใน ExampleFormObjectForm

## 🎯 ภาพรวม

ไฟล์ `ExampleFormObjectForm.php` เป็นตัวอย่าง Form Object ที่ครบถ้วนใน Filament 4 (Laravel 12 + Livewire 3) ที่แสดงการใช้ reactive hooks ต่างๆ เพื่อสร้าง form ที่ตอบสนองแบบ real-time

---

## 🔄 Reactive Hooks ที่ใช้

### 1. **`live()` / `live(onBlur: false)`** - Real-time Updates

**ใช้ที่ไหน:**
- `first_name`, `last_name` - อัปเดตแบบ real-time ทันทีที่พิมพ์
- `full_name` - อัปเดต placeholder แบบ real-time
- `department` - อัปเดต options ของ skills ทันทีที่เปลี่ยนแผนก
- `status` - แสดง/ซ่อน note field ทันทีที่เปลี่ยนสถานะ

**คำอธิบาย:**
```php
TextInput::make('first_name')
    ->live(onBlur: false) // อัปเดตทันทีที่พิมพ์ (ไม่ต้องรอ blur)
```

- `live()` - ส่ง request ไปยัง server ทันทีที่ค่าเปลี่ยน
- `live(onBlur: true)` - ส่ง request เมื่อ field สูญเสีย focus (default)
- `live(onBlur: false)` - ส่ง request ทันทีที่พิมพ์ (real-time)

**เมื่อไหร่ควรใช้:**
- เมื่อต้องการให้ field อื่นๆ เปลี่ยนตามค่า field นี้ทันที
- เมื่อต้องการแสดง placeholder หรือ helper text ที่เปลี่ยนตามค่า

---

### 2. **`placeholder(function (Get $get): string)`** - Dynamic Placeholder

**ใช้ที่ไหน:**
- `full_name` - แสดงชื่อ-นามสกุลเต็มแบบ real-time

**คำอธิบาย:**
```php
TextInput::make('full_name')
    ->placeholder(function (Get $get): string {
        $firstName = $get('first_name') ?? '';
        $lastName = $get('last_name') ?? '';
        
        if (empty($firstName) && empty($lastName)) {
            return 'กรุณากรอกชื่อและนามสกุล';
        }
        
        return trim("{$firstName} {$lastName}");
    })
    ->live() // ต้องใช้ live() เพื่อให้ placeholder อัปเดต
```

**หลักการ:**
- ใช้ `Get $get` เพื่อดึงค่าจาก field อื่นๆ
- ต้องใช้ `live()` เพื่อให้ placeholder อัปเดตแบบ real-time
- ใช้ closure function เพื่อคำนวณ placeholder แบบ dynamic

**เมื่อไหร่ควรใช้:**
- เมื่อต้องการแสดง placeholder ที่เปลี่ยนตามค่าของ field อื่นๆ
- เมื่อต้องการแสดงตัวอย่างค่าที่จะได้จากการกรอกข้อมูล

---

### 3. **`afterStateUpdated(function ($state, Set $set, Get $get))`** - After State Update Hook

**ใช้ที่ไหน:**
- `full_name` - อัปเดตค่า full_name เมื่อ first_name หรือ last_name เปลี่ยน
- `department` - Reset skills เมื่อเปลี่ยนแผนก
- `salary` - แจ้งเตือนเมื่อเงินเดือน > 100,000
- `start_date` - Reset และแจ้งเตือนเมื่อวันที่น้อยกว่าวันนี้

**คำอธิบาย:**

#### ตัวอย่างที่ 1: อัปเดต field อื่น
```php
TextInput::make('full_name')
    ->afterStateUpdated(function ($state, Set $set, Get $get) {
        $firstName = $get('first_name') ?? '';
        $lastName = $get('last_name') ?? '';
        $fullName = trim("{$firstName} {$lastName}");
        $set('full_name', $fullName);
    })
```

#### ตัวอย่างที่ 2: Reset field อื่น
```php
Select::make('department')
    ->afterStateUpdated(function ($state, Set $set) {
        $set('skills', null); // Reset skills เมื่อเปลี่ยนแผนก
    })
```

#### ตัวอย่างที่ 3: แจ้งเตือน (Notification)
```php
TextInput::make('salary')
    ->afterStateUpdated(function ($state, Set $set) {
        if ($state && $state > 100000) {
            Notification::make()
                ->title('เงินเดือนสูง')
                ->body('เงินเดือนที่กรอกสูงกว่า 100,000 บาท')
                ->warning()
                ->send();
        }
    })
```

#### ตัวอย่างที่ 4: Reset และแจ้งเตือน
```php
DatePicker::make('start_date')
    ->afterStateUpdated(function ($state, Set $set) {
        if ($state) {
            $selectedDate = \Carbon\Carbon::parse($state);
            $today = \Carbon\Carbon::today();
            
            if ($selectedDate->lt($today)) {
                $set('start_date', null); // Reset ค่า
                
                Notification::make()
                    ->title('วันที่ไม่ถูกต้อง')
                    ->body('วันที่เริ่มงานต้องไม่น้อยกว่าวันนี้')
                    ->warning()
                    ->send();
            }
        }
    })
```

**Parameters:**
- `$state` - ค่าใหม่ของ field นี้
- `Set $set` - ฟังก์ชันสำหรับตั้งค่าของ field อื่นๆ
- `Get $get` - ฟังก์ชันสำหรับดึงค่าของ field อื่นๆ

**เมื่อไหร่ควรใช้:**
- เมื่อต้องการอัปเดต field อื่นๆ หลังจาก field นี้เปลี่ยนค่า
- เมื่อต้องการ reset field อื่นๆ
- เมื่อต้องการแสดง notification หรือ warning
- เมื่อต้องการ validate แบบ custom

---

### 4. **`options(function (Get $get): array)`** - Dynamic Options

**ใช้ที่ไหน:**
- `skills` - เปลี่ยน options ตาม department ที่เลือก

**คำอธิบาย:**
```php
Select::make('skills')
    ->options(function (Get $get): array {
        $department = $get('department');
        
        return match ($department) {
            'it' => [
                'php' => 'PHP',
                'javascript' => 'JavaScript',
                // ...
            ],
            'hr' => [
                'recruitment' => 'Recruitment',
                // ...
            ],
            default => [],
        };
    })
    ->visible(fn (Get $get): bool => filled($get('department')))
```

**หลักการ:**
- ใช้ `Get $get` เพื่อดึงค่าจาก field อื่นๆ
- ใช้ closure function เพื่อคำนวณ options แบบ dynamic
- ต้องใช้ `live()` บน field ที่ options ขึ้นอยู่กับ (department) เพื่อให้ form re-render

**เมื่อไหร่ควรใช้:**
- เมื่อต้องการให้ options เปลี่ยนตามค่าของ field อื่นๆ
- เมื่อต้องการ filter options ตามเงื่อนไข

---

### 5. **`visible(fn (Get $get): bool)`** - Conditional Visibility

**ใช้ที่ไหน:**
- `skills` - แสดงเมื่อเลือก department แล้ว
- `note` - แสดงเมื่อ status = 'suspended'

**คำอธิบาย:**
```php
Textarea::make('note')
    ->visible(fn (Get $get): bool => $get('status') === 'suspended')
    ->required(fn (Get $get): bool => $get('status') === 'suspended')
```

**หลักการ:**
- ใช้ `Get $get` เพื่อดึงค่าจาก field อื่นๆ
- ใช้ closure function เพื่อคำนวณว่า field นี้ควรแสดงหรือไม่
- ต้องใช้ `live()` บน field ที่ visibility ขึ้นอยู่กับ (status) เพื่อให้ form re-render

**เมื่อไหร่ควรใช้:**
- เมื่อต้องการแสดง/ซ่อน field ตามเงื่อนไข
- เมื่อต้องการแสดง field เฉพาะเมื่อเงื่อนไขบางอย่างเป็นจริง

---

### 6. **`required(fn (Get $get): bool)`** - Conditional Required

**ใช้ที่ไหน:**
- `note` - บังคับกรอกเมื่อ status = 'suspended'

**คำอธิบาย:**
```php
Textarea::make('note')
    ->required(fn (Get $get): bool => $get('status') === 'suspended')
```

**หลักการ:**
- ใช้ `Get $get` เพื่อดึงค่าจาก field อื่นๆ
- ใช้ closure function เพื่อคำนวณว่า field นี้ควร required หรือไม่
- ต้องใช้ `live()` บน field ที่ required ขึ้นอยู่กับ (status) เพื่อให้ form re-render

**เมื่อไหร่ควรใช้:**
- เมื่อต้องการบังคับกรอก field เฉพาะเมื่อเงื่อนไขบางอย่างเป็นจริง
- เมื่อต้องการเปลี่ยน validation rules แบบ dynamic

---

## 🔗 การทำงานร่วมกันของ Hooks

### ตัวอย่าง: Full Name (Reactive Placeholder)

```php
// Step 1: first_name และ last_name ใช้ live(onBlur: false)
TextInput::make('first_name')
    ->live(onBlur: false) // อัปเดตทันทีที่พิมพ์

TextInput::make('last_name')
    ->live(onBlur: false) // อัปเดตทันทีที่พิมพ์

// Step 2: full_name ใช้ placeholder() และ afterStateUpdated()
TextInput::make('full_name')
    ->live() // ต้องใช้ live() เพื่อให้ placeholder อัปเดต
    ->placeholder(function (Get $get): string {
        // คำนวณ placeholder จาก first_name และ last_name
        return trim("{$get('first_name')} {$get('last_name')}");
    })
    ->afterStateUpdated(function ($state, Set $set, Get $get) {
        // อัปเดตค่า full_name
        $set('full_name', trim("{$get('first_name')} {$get('last_name')}"));
    })
```

**Flow:**
1. ผู้ใช้พิมพ์ใน `first_name` → `live(onBlur: false)` ส่ง request ทันที
2. Form re-render → `full_name` placeholder อัปเดต
3. `afterStateUpdated()` ทำงาน → อัปเดตค่า `full_name`

---

### ตัวอย่าง: Department → Skills (Dynamic Options)

```php
// Step 1: department ใช้ live() และ afterStateUpdated()
Select::make('department')
    ->live() // อัปเดตทันทีที่เลือก
    ->afterStateUpdated(function ($state, Set $set) {
        $set('skills', null); // Reset skills
    })

// Step 2: skills ใช้ options() และ visible()
Select::make('skills')
    ->options(function (Get $get): array {
        // คำนวณ options จาก department
        return match ($get('department')) {
            'it' => [...],
            'hr' => [...],
            default => [],
        };
    })
    ->visible(fn (Get $get): bool => filled($get('department')))
```

**Flow:**
1. ผู้ใช้เลือก `department` → `live()` ส่ง request ทันที
2. `afterStateUpdated()` ทำงาน → Reset `skills`
3. Form re-render → `skills` options เปลี่ยนตาม `department`
4. `visible()` ตรวจสอบ → แสดง `skills` เมื่อมี `department`

---

### ตัวอย่าง: Status → Note (Conditional Required)

```php
// Step 1: status ใช้ live()
Select::make('status')
    ->live() // อัปเดตทันทีที่เลือก

// Step 2: note ใช้ visible() และ required()
Textarea::make('note')
    ->visible(fn (Get $get): bool => $get('status') === 'suspended')
    ->required(fn (Get $get): bool => $get('status') === 'suspended')
```

**Flow:**
1. ผู้ใช้เลือก `status` = 'suspended' → `live()` ส่ง request ทันที
2. Form re-render → `visible()` ตรวจสอบ → แสดง `note`
3. `required()` ตรวจสอบ → บังคับกรอก `note`

---

## 📋 สรุป Reactive Hooks

| Hook | ใช้เมื่อ | Parameters | ต้องใช้ live()? |
|------|---------|------------|----------------|
| `live()` | ต้องการอัปเดตแบบ real-time | - | - |
| `placeholder()` | ต้องการ placeholder แบบ dynamic | `Get $get` | ✅ ใช่ |
| `afterStateUpdated()` | ต้องการทำอะไรหลังจากค่าเปลี่ยน | `$state`, `Set $set`, `Get $get` | ✅ ใช่ (ถ้าต้องการอัปเดต field อื่น) |
| `options()` | ต้องการ options แบบ dynamic | `Get $get` | ✅ ใช่ (บน field ที่ options ขึ้นอยู่กับ) |
| `visible()` | ต้องการแสดง/ซ่อน field | `Get $get` | ✅ ใช่ (บน field ที่ visibility ขึ้นอยู่กับ) |
| `required()` | ต้องการ required แบบ dynamic | `Get $get` | ✅ ใช่ (บน field ที่ required ขึ้นอยู่กับ) |

---

## ⚠️ ข้อควรระวัง

1. **ต้องใช้ `live()` บน field ที่ field อื่นๆ ขึ้นอยู่กับ**
   - ถ้า `skills` ขึ้นอยู่กับ `department` → `department` ต้องใช้ `live()`
   - ถ้า `note` ขึ้นอยู่กับ `status` → `status` ต้องใช้ `live()`

2. **`live()` ส่ง request ไปยัง server**
   - ใช้ `live(onBlur: false)` เฉพาะเมื่อจำเป็นจริงๆ
   - ใช้ `live()` หรือ `live(onBlur: true)` สำหรับ field ที่ไม่ต้องการ real-time

3. **`afterStateUpdated()` ไม่ทำงานเมื่อใช้ `$set()`**
   - ถ้าใช้ `$set('field', 'value')` → `afterStateUpdated()` ของ field นั้นจะไม่ทำงาน
   - ใช้ `shouldCallUpdatedHooks: true` ถ้าต้องการให้ทำงาน: `$set('field', 'value', shouldCallUpdatedHooks: true)`

4. **Performance**
   - ใช้ `live()` มากเกินไปอาจทำให้ form ช้า
   - ใช้ `live(onBlur: false)` เฉพาะเมื่อจำเป็นจริงๆ

---

## 🎓 ตัวอย่างเพิ่มเติม

ดูโค้ดเต็มใน `app/Filament/Resources/ExampleFormObject/Schemas/ExampleFormObjectForm.php`
