<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('wc_attribute_id')->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('product_attribute', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_attribute_id')->constrained('product_attributes')->onDelete('cascade');
            $table->string('value');
            $table->bigInteger('wc_term_id')->nullable();
            $table->primary(['product_id', 'product_attribute_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute');
        Schema::dropIfExists('product_attributes');
    }
};
