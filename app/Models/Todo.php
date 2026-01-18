<?php

namespace App\Models;

/**
 * Todo Model - ตัวอย่างที่ไม่ใช้ฐานข้อมูล
 * 
 * ใน Yii1: คล้ายกับ CActiveRecord แต่เราใช้ array แทน database
 * 
 * เปรียบเทียบกับ Yii1:
 * - Yii1: class Todo extends CActiveRecord { ... }
 * - Laravel: class Todo { ... } (ไม่ extend Eloquent)
 * 
 * เราใช้ static array เพื่อเก็บข้อมูลใน memory แทนฐานข้อมูล
 */
class Todo
{
    /**
     * เก็บข้อมูล Todo ทั้งหมดใน memory (เหมือน database table)
     * 
     * ใน Yii1: จะเป็น $this->findAll() หรือ $this->findByPk()
     * ที่ดึงข้อมูลจาก database
     */
    protected static array $todos = [];
    
    /**
     * ID สำหรับ auto-increment
     */
    protected static int $nextId = 1;
    
    /**
     * Properties ของ Todo (เหมือน columns ใน database table)
     */
    public ?int $id = null;
    public string $title = '';
    public string $description = '';
    public bool $is_completed = false;
    public string $created_at = '';
    
    /**
     * Constructor - สร้าง Todo object
     * 
     * ใน Yii1: จะเป็น $model = new Todo(); $model->attributes = [...];
     */
    public function __construct(array $attributes = [])
    {
        // กำหนดค่าให้กับ properties จาก array
        // ใน Yii1: จะเป็น $model->setAttributes($attributes);
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        // ถ้ายังไม่มี id ให้สร้างใหม่
        if (is_null($this->id)) {
            $this->id = self::$nextId++;
            $this->created_at = date('Y-m-d H:i:s');
        }
    }
    
    /**
     * ดึง Todo ทั้งหมด (เหมือน findAll() ใน Yii1)
     * 
     * ใน Yii1: Todo::model()->findAll();
     * ใน Laravel: Todo::all();
     */
    public static function all(): array
    {
        return self::$todos;
    }
    
    /**
     * หา Todo ด้วย ID (เหมือน findByPk() ใน Yii1)
     * 
     * ใน Yii1: Todo::model()->findByPk($id);
     * ใน Laravel: Todo::find($id);
     */
    public static function find(int $id): ?self
    {
        foreach (self::$todos as $todo) {
            if ($todo->id === $id) {
                return $todo;
            }
        }
        return null;
    }
    
    /**
     * บันทึก Todo (เหมือน save() ใน Yii1)
     * 
     * ใน Yii1: $model->save();
     * ใน Laravel: $model->save();
     */
    public function save(): bool
    {
        // ถ้าเป็น Todo ใหม่ (ยังไม่มีใน array)
        if (!$this->exists()) {
            self::$todos[] = $this;
        } else {
            // ถ้ามีอยู่แล้ว ให้อัปเดต
            foreach (self::$todos as $index => $todo) {
                if ($todo->id === $this->id) {
                    self::$todos[$index] = $this;
                    break;
                }
            }
        }
        return true;
    }
    
    /**
     * ลบ Todo (เหมือน delete() ใน Yii1)
     * 
     * ใน Yii1: $model->delete();
     * ใน Laravel: $model->delete();
     */
    public function delete(): bool
    {
        foreach (self::$todos as $index => $todo) {
            if ($todo->id === $this->id) {
                unset(self::$todos[$index]);
                self::$todos = array_values(self::$todos); // reindex array
                return true;
            }
        }
        return false;
    }
    
    /**
     * ตรวจสอบว่า Todo นี้มีอยู่ใน array หรือไม่
     */
    protected function exists(): bool
    {
        foreach (self::$todos as $todo) {
            if ($todo->id === $this->id) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * แปลง Todo เป็น array (ใช้สำหรับ form binding)
     * 
     * ใน Yii1: $model->attributes
     * ใน Laravel: $model->toArray()
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'created_at' => $this->created_at,
        ];
    }
    
    /**
     * กำหนดค่าให้กับ Todo จาก array
     * 
     * ใน Yii1: $model->setAttributes($data);
     * ใน Laravel: $model->fill($data);
     */
    public function fill(array $data): self
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }
    
    /**
     * creating() - Eloquent event hook (Filament ต้องการ method นี้)
     * 
     * เนื่องจาก Filament พยายามเรียก method นี้เพื่อตรวจสอบ tenant feature
     * แต่ Todo ไม่ได้ extend Eloquent Model ดังนั้นเราต้องเพิ่ม method นี้
     * เพื่อให้ Filament ทำงานได้โดยไม่ error
     * 
     * หมายเหตุ: method นี้จะไม่ทำอะไร เพราะเราไม่ใช้ Eloquent events
     * แต่ต้องมี signature ที่ตรงกับ Eloquent Model
     */
    public static function creating($callback)
    {
        // ไม่ต้องทำอะไร เพราะเราไม่ใช้ Eloquent events
        // เพียงแค่มี method นี้เพื่อให้ Filament เรียกได้โดยไม่ error
        // Return null เพื่อให้ compatible กับ Eloquent event system
        return null;
    }
    
    /**
     * created() - Eloquent event hook (Filament ต้องการ method นี้)
     * 
     * เหมือนกับ creating() แต่เรียกหลังจากสร้าง record เสร็จแล้ว
     */
    public static function created($callback)
    {
        // ไม่ต้องทำอะไร เพราะเราไม่ใช้ Eloquent events
        return null;
    }
    
    /**
     * updating() - Eloquent event hook (Filament อาจต้องการ method นี้)
     * 
     * เหมือนกับ creating() แต่สำหรับการอัปเดต
     */
    public static function updating($callback)
    {
        // ไม่ต้องทำอะไร เพราะเราไม่ใช้ Eloquent events
        return null;
    }
    
    /**
     * updated() - Eloquent event hook (Filament อาจต้องการ method นี้)
     * 
     * เหมือนกับ updating() แต่เรียกหลังจากอัปเดต record เสร็จแล้ว
     */
    public static function updated($callback)
    {
        // ไม่ต้องทำอะไร เพราะเราไม่ใช้ Eloquent events
        return null;
    }
    
    /**
     * deleting() - Eloquent event hook (Filament อาจต้องการ method นี้)
     * 
     * เหมือนกับ creating() แต่สำหรับการลบ
     */
    public static function deleting($callback)
    {
        // ไม่ต้องทำอะไร เพราะเราไม่ใช้ Eloquent events
        return null;
    }
    
    /**
     * deleted() - Eloquent event hook (Filament อาจต้องการ method นี้)
     * 
     * เหมือนกับ deleting() แต่เรียกหลังจากลบ record เสร็จแล้ว
     */
    public static function deleted($callback)
    {
        // ไม่ต้องทำอะไร เพราะเราไม่ใช้ Eloquent events
        return null;
    }
    
    /**
     * hasGlobalScope() - ตรวจสอบว่า Model มี global scope หรือไม่
     * 
     * Filament ต้องการ method นี้เพื่อตรวจสอบ tenant scopes
     * แต่ Todo ไม่ได้ extend Eloquent Model ดังนั้นเราจะ return false เสมอ
     */
    public static function hasGlobalScope($scope): bool
    {
        // ไม่มี global scopes เพราะเราไม่ใช้ Eloquent
        return false;
    }
    
    /**
     * addGlobalScope() - เพิ่ม global scope ให้กับ Model
     * 
     * Filament ต้องการ method นี้เพื่อเพิ่ม tenant scopes
     * แต่ Todo ไม่ได้ extend Eloquent Model ดังนั้นเราจะไม่ทำอะไร
     */
    public static function addGlobalScope($scope, $implementation = null)
    {
        // ไม่ต้องทำอะไร เพราะเราไม่ใช้ Eloquent global scopes
        // Return null เพื่อให้ compatible กับ Eloquent
        return null;
    }
    
    /**
     * addGlobalScopes() - เพิ่ม global scopes หลายตัวให้กับ Model
     * 
     * Filament อาจต้องการ method นี้
     */
    public static function addGlobalScopes(array $scopes)
    {
        // ไม่ต้องทำอะไร เพราะเราไม่ใช้ Eloquent global scopes
        return null;
    }
}
