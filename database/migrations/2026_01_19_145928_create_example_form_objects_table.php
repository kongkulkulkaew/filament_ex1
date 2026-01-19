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
        Schema::create('example_form_objects', function (Blueprint $table) {
            $table->id();
            
            // ข้อมูลส่วนตัว
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            
            // ข้อมูลการทำงาน
            $table->string('department')->nullable();
            $table->json('skills')->nullable(); // Array of skills
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            
            // เอกสารและสถานะ
            $table->json('documents')->nullable(); // Array of documents
            $table->string('status')->default('active');
            $table->text('note')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('example_form_objects');
    }
};
