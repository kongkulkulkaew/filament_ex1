<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade')->comment('ห้องเรียน');
            $table->string('student_code')->unique()->comment('รหัสนักเรียน');
            $table->string('first_name')->comment('ชื่อ');
            $table->string('last_name')->comment('นามสกุล');
            $table->date('birth_date')->nullable()->comment('วันเกิด');
            $table->string('phone')->nullable()->comment('เบอร์โทรศัพท์');
            $table->text('address')->nullable()->comment('ที่อยู่');
            $table->boolean('is_active')->default(true)->comment('สถานะใช้งาน');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
