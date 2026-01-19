<?php

namespace App\Filament\Resources\TimeLogs\Pages;

use App\Filament\Resources\TimeLogs\TimeLogResource;
use App\Models\ClassRoom;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListTimeLogs extends ListRecords
{
    protected static string $resource = TimeLogResource::class;

    public ?int $selectedClassroomId = null;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn () => $this->selectedClassroomId
                    ? TimeLogResource::getUrl('create', ['classroom_id' => $this->selectedClassroomId])
                    : TimeLogResource::getUrl('create')),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // ตรวจสอบ query parameter classroom_id
        $classroomId = request()->query('classroom_id');
        if ($classroomId) {
            $this->selectedClassroomId = (int) $classroomId;
        }
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getTableQuery();

        // ถ้าเลือกห้องเรียนแล้ว ให้กรองตามห้องเรียนนั้น
        if ($this->selectedClassroomId) {
            $query->where('classroom_id', $this->selectedClassroomId);
        } else {
            // ถ้ายังไม่เลือกห้องเรียน ให้ไม่แสดงข้อมูล
            $query->whereRaw('1 = 0');
        }

        return $query->latest();
    }

    public function getHeader(): ?View
    {
        return view('filament.resources.time-logs.classroom-selector', [
            'classrooms' => ClassRoom::where('is_active', true)->orderBy('name')->get(),
            'selectedClassroomId' => $this->selectedClassroomId,
        ]);
    }
}
