<?php

namespace App\Filament\Resources\Todos\Pages;

use App\Filament\Resources\Todos\TodoResource;
use App\Models\Todo;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * CreateTodo - หน้าสร้าง Todo ใหม่
 * 
 * ใน Yii1: คล้ายกับ actionCreate() ใน Controller
 * 
 * เปรียบเทียบกับ Yii1:
 * - Yii1:
 *   public function actionCreate() {
 *       $model = new Todo();
 *       if (isset($_POST['Todo'])) {
 *           $model->attributes = $_POST['Todo'];
 *           if ($model->save()) {
 *               $this->redirect(['view', 'id' => $model->id]);
 *           }
 *       }
 *       $this->render('create', ['model' => $model]);
 *   }
 * 
 * - Filament: จัดการเองอัตโนมัติ เราแค่กำหนด Resource
 */
class CreateTodo extends CreateRecord
{
    /**
     * กำหนด Resource ที่ใช้
     */
    protected static string $resource = TodoResource::class;
    
    /**
     * mutateFormDataBeforeCreate() - แก้ไขข้อมูลก่อนบันทึก
     * 
     * ใน Yii1: จะทำใน actionCreate() ก่อน $model->save()
     * ใน Filament: override method นี้
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // ตัวอย่าง: กำหนดค่าเริ่มต้น
        $data['is_completed'] = $data['is_completed'] ?? false;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $data;
    }
    
    /**
     * handleRecordCreation() - บันทึก Todo ใหม่ (override เพื่อใช้ array แทน database)
     * 
     * ใน Yii1: $model->save()
     * ใน Filament: override method นี้เพื่อใช้ custom save logic
     * 
     * หมายเหตุ: return type ต้องเป็น Model เพื่อให้ compatible กับ parent class
     * แต่เราจะ return Todo object แทน (Todo ไม่ extend Model แต่ทำงานได้)
     */
    protected function handleRecordCreation(array $data): Model
    {
        // สร้าง Todo object ใหม่
        $todo = new Todo($data);
        
        // บันทึกลง array (ไม่ใช่ database)
        $todo->save();
        
        // Cast เป็น Model เพื่อให้ compatible กับ return type
        // ในกรณีนี้ Todo ไม่ได้ extend Model แต่เราสามารถ return ได้
        // เพราะ PHP จะตรวจสอบ runtime type
        return $todo;
    }
    
    /**
     * getRedirectUrl() - URL ที่จะ redirect หลังสร้างเสร็จ
     * 
     * ใน Yii1: $this->redirect(['view', 'id' => $model->id]);
     * ใน Filament: override method นี้
     */
    protected function getRedirectUrl(): string
    {
        // กลับไปหน้า list หลังสร้างเสร็จ
        return $this->getResource()::getUrl('index');
    }
}
