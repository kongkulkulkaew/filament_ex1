<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassRoomFactory> */
    use HasFactory;

    /**
     * ชื่อ table ที่ใช้ (Laravel จะแปลง ClassRoom เป็น class_rooms แต่เราต้องการใช้ classrooms)
     */
    protected $table = 'classrooms';

    /**
     * Fields ที่สามารถ mass assignment ได้
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    /**
     * Casts สำหรับแปลงประเภทข้อมูล
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * ความสัมพันธ์: ห้องเรียนมีนักเรียนหลายคน
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'classroom_id');
    }

    /**
     * ความสัมพันธ์: ห้องเรียนมีการลงเวลาหลายครั้ง
     */
    public function timeLogs(): HasMany
    {
        return $this->hasMany(\App\Models\TimeLog::class, 'classroom_id');
    }

    /**
     * Scope: กรองเฉพาะห้องเรียนที่ใช้งานอยู่
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor: แสดงชื่อห้องเรียนพร้อมรหัส
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }
}
