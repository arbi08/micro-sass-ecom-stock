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
        Schema::create('article_variants', function (Blueprint $table) {
            $table->id();

            // 🔗 relation
            $table
                ->foreignId('article_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🧩 variant identity
            $table->string('sku')->nullable()->unique();

            // 🧠 attributes (core)
            $table->json('attributes');
            // example: { "color": "black", "size": "M" }

            // 💡 optional display name
            $table->string('label')->nullable();
            // example: "Black - M"

            // 💰 optional default pricing (not vendor price)
            $table->decimal('default_price', 10, 2)->nullable();

            // 📦 optional default cost (for analytics)
            $table->decimal('cost_price', 10, 2)->nullable();

            // 🖼️ variant-specific image
            $table->string('image')->nullable();

            // 🔢 sort for UI display
            $table->integer('sort_order')->default(0);

            // ⚡ status
            $table->boolean('is_active')->default(true);

            // 🧠 meta (future-proof)
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_variants');
    }
};
