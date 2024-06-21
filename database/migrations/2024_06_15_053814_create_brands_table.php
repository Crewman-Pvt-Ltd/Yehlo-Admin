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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name');
            $table->string('image');
            $table->string('brand_class')->nullable();
            $table->string('trademark')->nullable();
            $table->string('file')->nullable();
            $table->string('slug')->nullable();
            $table->integer('status')->default(1); 
            $table->integer('is_approved')->default(0); 
            $table->integer('vendor_id')->nullable();
            $table->integer('items_count')->default(0); 
            $table->integer('module_id'); 
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by')->nullable(); 
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
