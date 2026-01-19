<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    /**
     * Fields ที่สามารถ mass assignment ได้
     */
    protected $fillable = [
        'classroom_id',
        'student_code',
        'first_name',
        'last_name',
        'birth_date',
        'phone',
        'address',
        'is_active',
    ];

    /**
     * Casts สำหรับแปลงประเภทข้อมูล
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * ความสัมพันธ์: นักเรียนอยู่ในห้องเรียนเดียว
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'classroom_id');
    }

    /**
     * Scope: กรองเฉพาะนักเรียนที่ใช้งานอยู่
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: กรองตามห้องเรียน
     */
    public function scopeInClassroom($query, int $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }

    /**
     * Accessor: แสดงชื่อเต็ม
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
