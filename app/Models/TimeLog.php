<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeLog extends Model
{
    /** @use HasFactory<\Database\Factories\TimeLogFactory> */
    use HasFactory;

    /**
     * Fields ที่สามารถ mass assignment ได้
     */
    protected $fillable = [
        'classroom_id',
        'log_date',
        'start_time',
        'end_time',
        'notes',
    ];

    /**
     * Casts สำหรับแปลงประเภทข้อมูล
     */
    protected function casts(): array
    {
        return [
            'log_date' => 'date',
        ];
    }

    /**
     * ความสัมพันธ์: การลงเวลาอยู่ในห้องเรียนเดียว
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'classroom_id');
    }

    /**
     * Scope: เรียงตามวันที่ล่าสุดก่อน
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('log_date', 'desc')->orderBy('start_time', 'desc');
    }

    /**
     * Scope: กรองตามห้องเรียน
     */
    public function scopeInClassroom($query, int $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }
}
