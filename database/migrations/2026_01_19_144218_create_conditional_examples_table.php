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
        Schema::create('conditional_examples', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable(); // individual, company, organization
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('has_discount')->default(false);
            $table->string('discount_type')->nullable(); // percentage, fixed
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_tax_id')->nullable();
            $table->string('organization_type')->nullable(); // ngo, foundation, association
            $table->text('organization_description')->nullable();
            $table->boolean('needs_shipping')->default(false);
            $table->text('shipping_address')->nullable();
            $table->string('shipping_method')->nullable(); // standard, express, overnight
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conditional_examples');
    }
};
