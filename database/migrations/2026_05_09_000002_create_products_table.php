<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('sku')->unique();
            $table->string('brand')->nullable();
            $table->text('custom_tags')->nullable();
            $table->enum('status', ['draft', 'published', 'private'])->default('draft');
            $table->enum('type', ['simple', 'variable'])->default('simple');
            $table->bigInteger('wc_product_id')->nullable()->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->text('internal_observation')->nullable();
            $table->timestamp('wc_status_checked_at')->nullable();
            $table->string('wc_publication_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
