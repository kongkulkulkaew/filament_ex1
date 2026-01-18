<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    // factory คือ class ที่ใช้ในการสร้างข้อมูลตัวอย่าง (test data) สำหรับ model นี้ เช่น ใช้ในการทดสอบ หรือเติมข้อมูลจำลองในฐานข้อมูล
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
