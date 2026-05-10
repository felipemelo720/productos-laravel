<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('version_number');
            $table->string('name');
            $table->string('slug');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('sku');
            $table->string('brand')->nullable();
            $table->enum('status', ['draft', 'published', 'private'])->default('draft');
            $table->enum('type', ['simple', 'variable'])->default('simple');
            $table->text('custom_tags')->nullable();
            $table->text('internal_observation')->nullable();
            $table->json('categories_json')->nullable();
            $table->json('attributes_json')->nullable();
            $table->json('images_json')->nullable();
            $table->json('variations_json')->nullable();
            $table->json('wc_tags_json')->nullable();
            $table->string('change_type')->nullable(); // create, update, delete
            $table->text('change_description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['product_id', 'version_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_versions');
    }
};
