<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // 🧠 identity
            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            // 🏷️ classification
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();

            $table->string('brand')->nullable();

            // 🖼️ media
            $table->string('image')->nullable();
            $table->json('images')->nullable();

            // 🧩 variant structure (IMPORTANT)
            $table->json('attributes')->nullable();
            // example:
            // {
            //   "color": ["black", "red"],
            //   "size": ["S", "M", "L"]
            // }

            // ⚡ status
            $table->boolean('is_active')->default(true);

            // 🔢 sorting / featured
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);

            // 🧠 SEO (important for ecommerce)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};