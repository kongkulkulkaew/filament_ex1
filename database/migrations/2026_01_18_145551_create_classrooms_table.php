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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ชื่อห้องเรียน');
            $table->string('code')->unique()->comment('รหัสห้องเรียน');
            $table->text('description')->nullable()->comment('รายละเอียด');
            $table->boolean('is_active')->default(true)->comment('สถานะใช้งาน');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
