<?php

namespace App\Filament\Resources\Todos\Pages;

use App\Filament\Resources\Todos\TodoResource;
use App\Models\Todo;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * EditTodo - หน้าแก้ไข Todo
 * 
 * ใน Yii1: คล้ายกับ actionUpdate($id) ใน Controller
 * 
 * เปรียบเทียบกับ Yii1:
 * - Yii1:
 *   public function actionUpdate($id) {
 *       $model = Todo::model()->findByPk($id);
 *       if (isset($_POST['Todo'])) {
 *           $model->attributes = $_POST['Todo'];
 *           if ($model->save()) {
 *               $this->redirect(['view', 'id' => $model->id]);
 *           }
 *       }
 *       $this->render('update', ['model' => $model]);
 *   }
 * 
 * - Filament: จัดการเองอัตโนมัติ เราแค่กำหนด Resource
 */
class EditTodo extends EditRecord
{
    /**
     * กำหนด Resource ที่ใช้
     */
    protected static string $resource = TodoResource::class;

    /**
     * getHeaderActions() - actions ที่แสดงด้านบน
     * 
     * ใน Yii1: จะต้องเขียน link เองใน view
     * ใน Filament: กำหนดตรงนี้ได้เลย
     */
    protected function getHeaderActions(): array
    {
        return [
            /**
             * DeleteAction - ปุ่มลบ (เหมือน link ไป actionDelete)
             * 
             * ใน Yii1: CHtml::link('ลบ', ['delete', 'id' => $model->id])
             * ใน Filament: DeleteAction::make()
             */
            DeleteAction::make()
                ->label('ลบ Todo')
                ->action(function () {
                    // Override delete action เพื่อใช้ custom delete logic
                    $this->record->delete();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
    
    /**
     * resolveRecord() - ดึง Todo จาก array ด้วย ID (override เพื่อใช้ array แทน database)
     * 
     * ใน Yii1: $model = Todo::model()->findByPk($id);
     * ใน Filament: override method นี้เพื่อใช้ custom find logic
     * 
     * หมายเหตุ: return type ต้องเป็น Model เพื่อให้ compatible กับ parent class
     */
    protected function resolveRecord(int | string $key): Model
    {
        // หา Todo จาก array ด้วย ID
        $todo = Todo::find((int) $key);
        
        if (!$todo) {
            abort(404);
        }
        
        // Cast เป็น Model เพื่อให้ compatible กับ return type
        return $todo;
    }
    
    /**
     * mutateFormDataBeforeSave() - แก้ไขข้อมูลก่อนบันทึก
     * 
     * ใน Yii1: จะทำใน actionUpdate() ก่อน $model->save()
     * ใน Filament: override method นี้
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // ตัวอย่าง: อัปเดต timestamp
        // $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $data;
    }
    
    /**
     * handleRecordUpdate() - บันทึก Todo ที่แก้ไข (override เพื่อใช้ array แทน database)
     * 
     * ใน Yii1: $model->save()
     * ใน Filament: override method นี้เพื่อใช้ custom save logic
     * 
     * หมายเหตุ: return type ต้องเป็น Model เพื่อให้ compatible กับ parent class
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Cast เป็น Todo เพื่อใช้ methods ของ Todo
        /** @var Todo $todo */
        $todo = $record;
        
        // อัปเดตข้อมูล Todo
        $todo->fill($data);
        
        // บันทึกลง array (ไม่ใช่ database)
        $todo->save();
        
        return $todo;
    }
    
    /**
     * getRedirectUrl() - URL ที่จะ redirect หลังแก้ไขเสร็จ
     * 
     * ใน Yii1: $this->redirect(['view', 'id' => $model->id]);
     * ใน Filament: override method นี้
     */
    protected function getRedirectUrl(): string
    {
        // กลับไปหน้า list หลังแก้ไขเสร็จ
        return $this->getResource()::getUrl('index');
    }
}
